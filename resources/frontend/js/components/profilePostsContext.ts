import axios from 'axios';
import { Alpine } from '../livewire';
import { apiErrorMessage, apiValidationErrors, Decimal } from '../utils';
import { Comment, CommentPreview, Post, PostPreview } from '../models';
import { indexPosts, IndexPostsIncludeKey } from '../api/posts';
import { ROUTE_PROFILE_EDIT } from '../routes';

interface ProfilePostsContextProps {
    userId: Decimal;
    profileId: Decimal;
    authProfileId: Decimal;
}

Alpine.data('profilePostsContext', (props: ProfilePostsContextProps) => {
    function build(posts: Post[]): PostPreview[] {
        return posts.map((post: Post) => {
            const doYouLike = post.likes?.find((profile) => profile.id == props.authProfileId) != null;
            const canEdit = post.profileId == props.authProfileId;
            const profileLink = post.profile ? ROUTE_PROFILE_EDIT(post.profile.nickname) : null;

            if (post.comments == null) {
                return {
                    ...post,
                    doYouLike: doYouLike,
                    canEdit: canEdit,
                    commentPreviews: [],
                    profileLink: profileLink,
                } as PostPreview;
            }

            const commentPreviews = post.comments
                .map((comment: Comment) => {
                    const doYouLike = comment.likes?.find((profile) => profile.id == props.authProfileId) != null;
                    const canEdit = comment.profileId == props.authProfileId;
                    const profileLink = comment.profile ? ROUTE_PROFILE_EDIT(comment.profile.nickname) : null;
                    const likesCount = comment.likes?.length;
                    return {
                        ...comment,
                        likesCount: likesCount,
                        doYouLike: doYouLike,
                        canEdit: canEdit,
                        profileLink: profileLink,
                    } as CommentPreview;
                })
                .slice(0, 2);

            return {
                ...post,
                doYouLike: doYouLike,
                canEdit: canEdit,
                commentPreviews: commentPreviews,
                profileLink: profileLink,
            } as PostPreview;
        });
    }

    return {
        errors: {},
        saving: false,
        posts: [] as PostPreview[],

        async init() {
            await this.fetchPosts();
        },

        async fetchPosts() {
            if (this.saving) {
                return;
            }
            this.saving = true;
            this.errors = {};

            try {
                const posts = await indexPosts(props.userId, props.profileId, {
                    include: [
                        IndexPostsIncludeKey.Profile,
                        IndexPostsIncludeKey.LikesCount,
                        IndexPostsIncludeKey.Likes,
                        IndexPostsIncludeKey.Comments,
                        IndexPostsIncludeKey.CommentsLikes,
                        IndexPostsIncludeKey.CommentsCount,
                        IndexPostsIncludeKey.CommentsProfile,
                    ],
                });
                this.posts = build(posts);

                this.$dispatch('toast', {
                    type: 'success',
                    message: 'Posts loaded',
                });

                this.$dispatch('fetchPosts', {
                    profileId: props.profileId,
                    userId: props.userId,
                });
            } catch (e) {
                if (axios.isAxiosError(e) && e?.response?.data) {
                    this.errors = apiValidationErrors(e?.response?.data);

                    this.$dispatch('toast', {
                        type: 'error',
                        message: apiErrorMessage(
                            e?.response?.data,
                            // props.messageError ?? 'messages.contact_form_error' //window.polyglot.t('messages.contact_form_error')
                        ),
                    });
                }
            } finally {
                this.saving = false;
            }
        },

        onCreatePost(event: Event) {
            // @ts-ignore
            if (!event.detail.post) {
                console.error('[onCreatePost] post is required');
                return;
            }
            // @ts-ignore
            const post = event.detail.post;

            const profileLink = post.profile ? ROUTE_PROFILE_EDIT(post.profile.nickname) : null;

            const postPreview = {
                ...post,
                likesCount: 0,
                likes: [],
                commentsCount: 0,
                doYouLike: false,
                canEdit: true,
                commentPreviews: [],
                profileLink: profileLink,
            };

            this.posts = [postPreview, ...this.posts];
        },

        onDestroyPost(event: Event) {
            // @ts-ignore
            if (!event.detail.postId) {
                console.error('[onDestroyPost] postId is required');
                return;
            }
            // @ts-ignore
            const postId = event.detail.postId;

            this.posts = this.posts.filter((post: Post) => post.id != postId);
        },

        onPostLiked(event: Event) {
            // @ts-ignore
            if (!event.detail.post) {
                console.error('[onPostLiked] post is required');
                return;
            }
            // @ts-ignore
            const post = event.detail.post;

            const postPreview = this.posts.find((p: Post) => p.id == post.id);

            if (postPreview == null || postPreview.likesCount == null || postPreview.likes == null) {
                console.error("[onPostLiked] post can't refresh");
                return;
            }
            postPreview.doYouLike = true;
            postPreview.likes.push(post.profile);
            postPreview.likesCount += 1;
        },

        onPostLikedRemoved(event: Event) {
            // @ts-ignore
            if (!event.detail.post) {
                console.error('[onPostLikedRemoved] post is required');
                return;
            }
            // @ts-ignore
            const post = event.detail.post;

            const postPreview = this.posts.find((p: Post) => p.id == post.id);

            if (postPreview == null || postPreview.likesCount == null || postPreview.likes == null) {
                console.error("[onPostLikedRemoved] post can't refresh");
                return;
            }
            postPreview.doYouLike = false;
            postPreview.likes.push(post.profile);
            postPreview.likesCount = postPreview.likesCount == 0 ? 0 : postPreview.likesCount - 1;
        },

        onCreateComment(event: Event) {
            // @ts-ignore
            if (!event.detail.comment) {
                console.error('[onCreateComment] comment is required');
                return;
            }

            // @ts-ignore
            if (!event.detail.postId) {
                console.error('[onCreateComment] postId is required');
                return;
            }

            // @ts-ignore
            const comment = event.detail.comment;

            // @ts-ignore
            const postId = event.detail.postId;

            const profileLink = comment.profile ? ROUTE_PROFILE_EDIT(comment.profile.nickname) : null;

            const commentPreview = {
                ...comment,
                doYouLike: false,
                canEdit: true,
                profileLink: profileLink,
                likesCount: 0,
                likes: [],
            };

            const post = this.posts.find((post: Post) => post.id == postId);

            if (post?.commentPreviews == null) {
                console.error('[onCreateComment] commentPreviews not found');
                return;
            }
            post.commentPreviews = [commentPreview, ...post.commentPreviews];

            if (post?.commentsCount == null) {
                console.error('[onCreateComment] commentsCount not found');
                return;
            }
            post.commentsCount += post.commentsCount == 0 ? 0 : 1;
        },

        onDestroyComment(event: Event) {
            // @ts-ignore
            if (!event.detail.commentId) {
                console.error('[onDestroyComment] commentId is required');
                return;
            }

            // @ts-ignore
            if (!event.detail.postId) {
                console.error('[onDestroyComment] postId is required');
                return;
            }

            // @ts-ignore
            const commentId = event.detail.commentId;

            // @ts-ignore
            const postId = event.detail.postId;

            const post = this.posts.find((post: Post) => post.id == postId);

            if (post?.commentPreviews == null) {
                console.error('[onDestroyComment] commentPreviews not found');
                return;
            }

            post.commentPreviews = post.commentPreviews.filter((comment: CommentPreview) => comment.id != commentId);

            if (post?.commentsCount == null) {
                console.error('[onDestroyComment] commentsCount not found');
                return;
            }
            post.commentsCount -= post.commentsCount == 0 ? 0 : 1;
        },

        onCommentLiked(event: Event) {
            // @ts-ignore
            if (!event.detail.comment) {
                console.error('[onCommentLiked] comment is required');
                return;
            }

            // @ts-ignore
            if (!event.detail.postId) {
                console.error('[onCommentLiked] postId is required');
                return;
            }

            // @ts-ignore
            const comment = event.detail.comment;

            // @ts-ignore
            const postId = event.detail.postId;

            const postPreview = this.posts.find((p: PostPreview) => p.id == postId);

            const commentPreview = postPreview?.commentPreviews.find((c: CommentPreview) => c.id == comment.id);

            if (commentPreview == null || commentPreview.likesCount == null || commentPreview.likes == null) {
                console.error("[onCommentLiked] comment can't refresh");
                return;
            }
            commentPreview.doYouLike = true;
            commentPreview.likes.push(comment.profile);
            commentPreview.likesCount += 1;
        },

        onCommentLikedRemoved(event: Event) {
            // @ts-ignore
            if (!event.detail.comment) {
                console.error('[onCommentLikedRemoved] comment is required');
                return;
            }

            // @ts-ignore
            if (!event.detail.postId) {
                console.error('[onCommentLikedRemoved] postId is required');
                return;
            }

            // @ts-ignore
            const comment = event.detail.comment;

            // @ts-ignore
            const postId = event.detail.postId;

            const postPreview = this.posts.find((p: PostPreview) => p.id == postId);

            const commentPreview = postPreview?.commentPreviews.find((c: CommentPreview) => c.id == comment.id);

            if (commentPreview == null || commentPreview.likesCount == null || commentPreview.likes == null) {
                console.error("[onCommentLikedRemoved] comment can't refresh");
                return;
            }
            commentPreview.doYouLike = false;
            commentPreview.likes.push(comment.profile);
            commentPreview.likesCount = commentPreview.likesCount == 0 ? 0 : commentPreview.likesCount - 1;
        },
    };
});
