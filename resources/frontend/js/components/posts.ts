import axios from 'axios';
import { Alpine } from '../livewire';
import { apiValidationErrors, Decimal } from '../utils';
import { Comment, CommentPreview, Post, PostPreview, Profile } from '../models';
import { destroyPost, indexPosts, IndexPostsIncludeKey } from '../api/posts';
import { createPostLike, destroyPostLike } from '../api/postLikes';
import { createCommentLike, destroyCommentLike } from '../api/postCommentLikes';
import { showProfile } from '../api/profiles';

interface PostsProps {
    userId: Decimal;
    profileId: Decimal;
    authProfileId: Decimal;
}

Alpine.data('posts', (props: PostsProps) => {
    function buildPosts(posts: Post[]): PostPreview[] {
        return posts.map((post: Post) => {
            const doYouLike = post.likes?.find((profile) => profile.id == props.profileId) != null;
            const canEdit = post.profileId == props.authProfileId;

            const sortedComments = post.comments?.sort(
                (commentA, commentB) => (commentA.likesCount ?? 0) - (commentB.likesCount ?? 0),
            );
            if (sortedComments == null) {
                return {
                    ...post,
                    doYouLike: doYouLike,
                    canEdit: canEdit,
                    commentPreviews: [],
                } as PostPreview;
            }

            const commentPreviews = sortedComments.map((comment: Comment) => {
                const doYouLike = comment.likes?.find((profile) => profile.id == props.profileId) != null;
                const canEdit = comment.profileId == props.authProfileId;
                return {
                    ...comment,
                    doYouLike: doYouLike,
                    canEdit: canEdit,
                } as CommentPreview;
            });

            return {
                ...post,
                doYouLike: doYouLike,
                canEdit: canEdit,
                commentPreviews: commentPreviews,
            } as PostPreview;
        });
    }

    return {
        errors: {},
        saving: false,
        postPreviews: [] as PostPreview[],
        authProfile: {} as Profile,

        init() {
            this.fetchAuthProfile();
            this.fetchPosts();
        },

        async fetchPosts() {
            if (this.saving) {
                return;
            }
            this.saving = true;
            this.errors = {};

            try {
                const posts = await indexPosts(props.userId, props.profileId, {
                    include: [IndexPostsIncludeKey.Likes, IndexPostsIncludeKey.Comments],
                });
                this.postPreviews = buildPosts(posts);
            } catch (e) {
                if (axios.isAxiosError(e) && e?.response?.data) {
                    this.errors = apiValidationErrors(e?.response?.data);
                }
                //TODO Gestisci gli errori
            } finally {
                this.saving = false;
            }
        },
        async fetchAuthProfile() {
            if (this.saving) {
                return;
            }
            this.saving = true;
            this.errors = {};

            try {
                this.authProfile = await showProfile(props.userId, props.authProfileId);
            } catch (e) {
                if (axios.isAxiosError(e) && e?.response?.data) {
                    this.errors = apiValidationErrors(e?.response?.data);
                }
                //TODO Gestisci gli errori
            } finally {
                this.saving = false;
            }
        },

        async deletePost(postId: Decimal) {
            if (this.saving) {
                return;
            }
            this.saving = true;
            this.errors = {};

            try {
                const post = this.postPreviews.find((post) => post.id == postId);
                if (post == null) {
                    console.error('Post not found');
                    return;
                }
                if (post.profileId == props.authProfileId) {
                    console.error("Cannot delete other's posts");
                    return;
                }

                await destroyPost(props.userId, props.profileId, postId);

                this.postPreviews = this.postPreviews.filter((post) => post.id != postId);
            } catch (e) {
                if (axios.isAxiosError(e) && e?.response?.data) {
                    this.errors = apiValidationErrors(e?.response?.data);
                }
                //TODO Gestisci gli errori
            } finally {
                this.saving = false;
            }
        },

        async createPostLike(postId: Decimal) {
            if (this.saving) {
                return;
            }
            this.saving = true;
            this.errors = {};

            try {
                const post = this.postPreviews.find((post) => post.id == postId);

                if (post == null) {
                    console.error('Post not found');
                    return;
                }

                await createPostLike(props.userId, props.profileId, postId);

                if (post.likesCount && post.likes) {
                    post.doYouLike = true;
                    post.likes.push(this.authProfile);
                    post.likesCount += 1;
                } else {
                    location.reload();
                }
            } catch (e) {
                if (axios.isAxiosError(e) && e?.response?.data) {
                    this.errors = apiValidationErrors(e?.response?.data);
                }
                //TODO Gestisci gli errori
            } finally {
                this.saving = false;
            }
        },

        async destroyPostLike(postId: Decimal) {
            if (this.saving) {
                return;
            }
            this.saving = true;
            this.errors = {};

            try {
                const post = this.postPreviews.find((post) => post.id == postId);

                if (post == null) {
                    console.error('Post not found');
                    return;
                }

                await destroyPostLike(props.userId, props.profileId, postId);

                if (post.likesCount && post.likes) {
                    post.likesCount -= 1;
                    post.doYouLike = false;
                    post.likes = post.likes?.filter((profile) => profile.id != props.authProfileId);
                } else {
                    location.reload();
                }
            } catch (e) {
                if (axios.isAxiosError(e) && e?.response?.data) {
                    this.errors = apiValidationErrors(e?.response?.data);
                }
                //TODO Gestisci gli errori
            } finally {
                this.saving = false;
            }
        },

        async createCommentLike(postId: Decimal, commentId: Decimal) {
            if (this.saving) {
                return;
            }
            this.saving = true;
            this.errors = {};

            try {
                const post = this.postPreviews.find((post) => post.id == postId);
                if (post == null) {
                    console.error('Post not found');
                    return;
                }

                const comment = post.commentPreviews?.find((comment) => comment.id == commentId);
                if (comment == null) {
                    console.error('Comment not found');
                    return;
                }

                await createCommentLike(props.userId, props.profileId, postId, commentId);

                if (comment.likes && comment.likesCount) {
                    comment.likesCount += 1;
                    comment.likes.push(this.authProfile);
                    post.doYouLike = true;
                } else {
                    location.reload();
                }
            } catch (e) {
                if (axios.isAxiosError(e) && e?.response?.data) {
                    this.errors = apiValidationErrors(e?.response?.data);
                }
                //TODO Gestisci gli errori
            } finally {
                this.saving = false;
            }
        },

        async deleteCommentLike(postId: Decimal, commentId: Decimal) {
            if (this.saving) {
                return;
            }
            this.saving = true;
            this.errors = {};

            try {
                const post = this.postPreviews.find((post) => post.id == postId);
                if (post == null) {
                    console.error('Post not found');
                    return;
                }

                const comment = post.commentPreviews?.find((comment) => comment.id == commentId);
                if (comment == null) {
                    console.error('Comment not found');
                    return;
                }

                await destroyCommentLike(props.userId, props.profileId, postId, commentId);

                if (comment.likes && comment.likesCount) {
                    comment.likesCount -= 1;
                    comment.doYouLike = false;
                    comment.likes = comment.likes.filter((profile) => profile.id != props.authProfileId);
                } else {
                    location.reload();
                }
            } catch (e) {
                if (axios.isAxiosError(e) && e?.response?.data) {
                    this.errors = apiValidationErrors(e?.response?.data);
                }
                //TODO Gestisci gli errori
            } finally {
                this.saving = false;
            }
        },
    };
});
