import axios from 'axios';
import { Alpine } from '../livewire';
import { Decimal, postsToPostPreviews } from '../utils';
import { Comment, CommentPreview, Post, PostPreview } from '../models';
import { indexPosts, IndexPostsIncludeKey } from '../api/posts';
import { ROUTE_PROFILE_EDIT } from '../routes';
import { showDashboard, ShowDashboardIncludeKey } from '../api/dashboard';

interface postListContextProps {
    userId: Decimal;
    profileId: Decimal;
    authProfileId: Decimal;
    authProfileNickname: string;
    context: 'PROFILE' | 'DASHBOARD';
    onSuccessMessage?: string;
    onFailMessage?: string;
}

Alpine.data('postListContext', (props: postListContextProps) => {
    return {
        errors: {},
        saving: false,
        posts: [] as PostPreview[],
        page: 1,
        lastPage: false,

        async init() {
            if (props.context === 'PROFILE') {
                await this.fetchProfilePosts();
            }

            if (props.context === 'DASHBOARD') {
                await this.fetchDashboardPosts();
            }
        },

        async fetchProfilePosts() {
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
                    page: this.page,
                });

                if (posts.length == 0) {
                    this.lastPage = true;
                    return;
                }

                this.posts = [...this.posts, ...postsToPostPreviews(posts, props.authProfileId, props.authProfileNickname)];

                this.$dispatch('toast', {
                    type: 'success',
                    message: props.onSuccessMessage ?? 'Success',
                });

                this.$dispatch('fetchProfilePosts', {
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

        async fetchDashboardPosts() {
            if (this.saving) {
                return;
            }
            this.saving = true;
            this.errors = {};

            try {
                const posts = await showDashboard(props.userId, props.profileId, {
                    include: [
                        ShowDashboardIncludeKey.Profile,
                        ShowDashboardIncludeKey.Likes,
                        ShowDashboardIncludeKey.Comments,
                        ShowDashboardIncludeKey.CommentsLikes,
                        ShowDashboardIncludeKey.CommentsProfile,
                        ShowDashboardIncludeKey.LikesCount,
                        ShowDashboardIncludeKey.CommentsCount,
                    ],
                    page: this.page,
                });

                if (posts.length == 0) {
                    this.lastPage = true;
                    return;
                }

                this.posts = [...this.posts, ...postsToPostPreviews(posts, props.authProfileId, props.authProfileNickname)];

                this.$dispatch('toast', {
                    type: 'success',
                    message: props.onSuccessMessage ?? 'Success',
                });

                this.$dispatch('fetchDashboardPosts', {
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

        async loadMore() {
            if (this.lastPage) {
                return;
            }

            this.page += 1;

            if (props.context === 'PROFILE') {
                await this.fetchProfilePosts();
            }

            if (props.context === 'DASHBOARD') {
                await this.fetchDashboardPosts();
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
