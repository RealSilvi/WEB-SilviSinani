import axios from 'axios';
import { Alpine } from '../../livewire';
import { Decimal, postsToPostPreviews } from '../../utils';
import { CommentPreview, PostPreview, Profile } from '../../models';
import { showPosts, ShowPostsIncludeKey } from '../../api/posts';
import { ROUTE_DASHBOARD, ROUTE_PROFILE_EDIT } from '../../routes';
import { indexComment, IndexCommentIncludeKey } from '../../api/postComments';

interface postContextProps {
    userId: Decimal;
    profileId: Decimal;
    authProfileId: Decimal;
    postId: Decimal;
    authProfileNickname: string;
    onSuccessMessage?: string;
    onFailMessage?: string;
    onCommentSuccessMessage?: string;
    onCommentFailMessage?: string;
}

Alpine.data('postContext', (props: postContextProps) => {
    return {
        errors: {},
        saving: false,
        post: {} as PostPreview,
        page: 1,
        lastCommentPage: false,

        async init() {
            await this.fetchPost();
            await this.fetchComments();
        },

        async fetchPost() {
            if (this.saving) {
                return;
            }
            this.saving = true;
            this.errors = {};

            try {
                const post = await showPosts(props.userId, props.profileId, props.postId, {
                    include: [
                        ShowPostsIncludeKey.Profile,
                        ShowPostsIncludeKey.Likes,
                        ShowPostsIncludeKey.LikesCount,
                        ShowPostsIncludeKey.CommentsCount,
                    ],
                });

                const postPreview = postsToPostPreviews([post], props.authProfileId, props.authProfileNickname).pop();
                if (postPreview == null) {
                    return;
                }

                this.post = postPreview;

                this.$dispatch('toast', {
                    type: 'success',
                    message: props.onSuccessMessage ?? 'Success',
                });

                this.$dispatch('fetch-post', {
                    profileId: props.profileId,
                    userId: props.userId,
                });
            } catch (e) {
                if (axios.isAxiosError(e) && e?.response?.data) {
                    this.$dispatch('toast', {
                        type: 'error',
                        message: props.onFailMessage ?? 'Error',
                    });
                }
            } finally {
                this.saving = false;
            }
        },

        async fetchComments() {
            if (this.saving) {
                return;
            }
            this.saving = true;
            this.errors = {};

            try {
                const comments = await indexComment(props.userId, props.profileId, props.postId, {
                    include: [
                        IndexCommentIncludeKey.Profile,
                        IndexCommentIncludeKey.LikesCount,
                        IndexCommentIncludeKey.Likes,
                    ],
                    page: this.page,
                });

                if (comments.length < 10) {
                    this.lastCommentPage = true;
                }

                this.post.comments = [...(this.post.comments ?? []), ...comments];

                const postPreview = postsToPostPreviews(
                    [this.post],
                    props.authProfileId,
                    props.authProfileNickname,
                ).pop();
                if (postPreview == null) {
                    return;
                }

                this.post = postPreview;

                if (props.onCommentSuccessMessage) {
                    this.$dispatch('toast', {
                        type: 'success',
                        message: props.onCommentSuccessMessage,
                    });
                }

                this.$dispatch('fetch-post-comments', {
                    profileId: props.profileId,
                    userId: props.userId,
                });
            } catch (e) {
                if (axios.isAxiosError(e) && e?.response?.data) {
                    this.$dispatch('toast', {
                        type: 'error',
                        message: props.onCommentFailMessage ?? 'Error',
                    });
                }
            } finally {
                this.saving = false;
            }
        },

        async loadMoreComments() {
            if (this.lastCommentPage) {
                return;
            }

            this.page += 1;

            await this.fetchComments();
        },

        onDestroyPost() {
            window.location.replace(ROUTE_DASHBOARD(props.authProfileNickname));
        },

        onPostLiked(event: Event) {
            // @ts-ignore
            if (!event.detail.post) {
                console.error('[onPostLiked] post is required');
                return;
            }
            // @ts-ignore
            const post = event.detail.post;

            const like = post.likes.find((p: Profile) => p.id == props.authProfileId);

            if (post.id != this.post.id || this.post.likesCount == null || this.post.likePreviews == null) {
                console.error("[onPostLiked] post can't refresh");
                return;
            }

            this.post.doYouLike = true;
            this.post.likesCount += 1;
            this.post.likePreviews.push({
                ...like,
                profileLink: ROUTE_PROFILE_EDIT(props.authProfileNickname, props.authProfileId.toString()),
            });
        },

        onPostLikedRemoved(event: Event) {
            // @ts-ignore
            if (!event.detail.post) {
                console.error('[onPostLikedRemoved] post is required');
                return;
            }
            // @ts-ignore
            const post = event.detail.post;

            if (this.post.id != post.id || this.post.likesCount == null || this.post.likes == null) {
                console.error("[onPostLikedRemoved] post can't refresh");
                return;
            }
            this.post.doYouLike = false;
            this.post.likesCount = this.post.likesCount == 0 ? 0 : this.post.likesCount - 1;
            this.post.likePreviews = this.post.likePreviews.filter((profile) => profile.id != props.authProfileId);
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

            const profileLink = comment.profile
                ? location + ROUTE_PROFILE_EDIT(comment.profile.nickname, props.authProfileNickname)
                : '#';

            const commentPreview = {
                ...comment,
                doYouLike: false,
                canEdit: true,
                profileLink: profileLink,
                likesCount: 0,
                likes: [],
            };
            if (this.post.id != postId || this.post?.commentPreviews == null) {
                console.error('[onCreateComment] commentPreviews not found');
                return;
            }
            this.post.commentPreviews = [commentPreview, ...this.post.commentPreviews];

            if (this.post?.commentsCount == null) {
                console.error('[onCreateComment] commentsCount not found');
                return;
            }
            this.post.commentsCount += this.post.commentsCount == 0 ? 0 : 1;
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

            if (this.post.id != postId || this.post?.commentPreviews == null) {
                console.error('[onDestroyComment] commentPreviews not found');
                return;
            }

            this.post.commentPreviews = this.post.commentPreviews.filter(
                (comment: CommentPreview) => comment.id != commentId,
            );

            if (this.post?.commentsCount == null) {
                console.error('[onDestroyComment] commentsCount not found');
                return;
            }
            this.post.commentsCount -= this.post.commentsCount == 0 ? 0 : 1;
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

            const commentPreview = this.post?.commentPreviews.find((c: CommentPreview) => c.id == comment.id);

            const like = comment.likes.find((p: Profile) => p.id == props.authProfileId);

            if (
                postId != this.post.id ||
                commentPreview == null ||
                commentPreview.likesCount == null ||
                commentPreview.likes == null
            ) {
                console.error("[onCommentLiked] comment can't refresh");
                return;
            }
            commentPreview.doYouLike = true;
            commentPreview.likesCount += 1;
            commentPreview.likePreviews.push({
                ...like,
                profileLink: ROUTE_PROFILE_EDIT(props.authProfileNickname, props.authProfileId.toString()),
            });
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

            const commentPreview = this.post?.commentPreviews.find((c: CommentPreview) => c.id == comment.id);

            if (
                postId != this.post.id ||
                commentPreview == null ||
                commentPreview.likesCount == null ||
                commentPreview.likes == null
            ) {
                console.error("[onCommentLikedRemoved] comment can't refresh");
                return;
            }
            commentPreview.doYouLike = false;
            commentPreview.likesCount = commentPreview.likesCount == 0 ? 0 : commentPreview.likesCount - 1;
            commentPreview.likePreviews = commentPreview.likePreviews.filter(
                (profile) => profile.id != props.authProfileId,
            );
        },
    };
});
