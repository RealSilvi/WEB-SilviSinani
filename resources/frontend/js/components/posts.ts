import axios from 'axios';
import { Alpine } from '../livewire';
import { apiErrorMessage, apiValidationErrors, Decimal } from '../utils';
import { Comment, CommentPreview, Post, PostPreview, Profile } from '../models';
import { destroyPost, indexPosts, IndexPostsIncludeKey } from '../api/posts';
import { createPostLike, destroyPostLike } from '../api/postLikes';
import { createCommentLike, destroyCommentLike } from '../api/postCommentLikes';
import { showProfile } from '../api/profiles';
import { ROUTE_PROFILE_EDIT, ROUTE_PROFILE_NEW } from '../routes';
import { createComment } from '../api/postComments';

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
            const profileLink = post.profile ? ROUTE_PROFILE_EDIT(post.profile.nickname) : null;

            if (post.topComments == null) {
                return {
                    ...post,
                    doYouLike: doYouLike,
                    canEdit: canEdit,
                    commentPreviews: [],
                    profileLink: profileLink,
                } as PostPreview;
            }

            const commentPreviews = post.topComments
                .map((comment: Comment) => {
                    const doYouLike = comment.likes?.find((profile) => profile.id == props.profileId) != null;
                    const canEdit = comment.profileId == props.authProfileId;
                    const profileLink = comment.profile ? ROUTE_PROFILE_EDIT(comment.profile.nickname) : null;
                    return {
                        ...comment,
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
        postPreviews: [] as PostPreview[],
        authProfile: {} as Profile,

        async init() {
            await this.fetchAuthProfile();
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
                        IndexPostsIncludeKey.LikesCount,
                        IndexPostsIncludeKey.Likes,
                        IndexPostsIncludeKey.CommentsCount,
                        IndexPostsIncludeKey.TopComments,
                        IndexPostsIncludeKey.TopCommentsProfile,
                        IndexPostsIncludeKey.Profile,
                    ],
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

                if (post.likesCount != null && post.likes != null) {
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

                if (post.likesCount != null && post.likes != null) {
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
                if (comment.likes != null && comment.likesCount != null) {
                    comment.likesCount += 1;
                    comment.likes.push(this.authProfile);
                    comment.doYouLike = true;
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

                if (comment.likes != null && comment.likesCount != null) {
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

        async createComment(event: SubmitEvent, postId: Decimal) {
            if (!(event.target instanceof HTMLFormElement)) {
                return;
            }

            if (this.saving) {
                return;
            }

            this.saving = true;
            this.errors = {};

            try {
                const data = new FormData(event.target);

                const comment = await createComment(props.userId, props.authProfileId, postId, {
                    body: data.get('body')?.toString(),
                });
                const post = this.postPreviews.find((post) => post.id == postId);

                post?.commentPreviews?.push({
                    ...comment,
                    profile: this.authProfile,
                    likes: [],
                    likesCount: 0,
                    doYouLike: false,
                    canEdit: true,
                    profileLink: ROUTE_PROFILE_EDIT(this.authProfile.id),
                });

                if (post?.commentsCount != null) {
                    post.commentsCount = (post?.commentsCount ?? 0) + 1;
                }

                this.$dispatch('toast', {
                    type: 'success',
                    message: 'Comment published',
                });

                event.target.reset();

                this.$dispatch('submitted', {
                    // formId: props.formId,
                    target: event.target,
                    data,
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
    };
});
