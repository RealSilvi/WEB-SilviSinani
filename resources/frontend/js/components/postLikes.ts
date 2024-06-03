import axios from 'axios';
import { Alpine } from '../livewire';
import { Decimal } from '../utils';
import { showPosts, ShowPostsIncludeKey } from '../api/posts';
import { createPostLike, destroyPostLike } from '../api/postLikes';

interface PostsProps {
    userId: Decimal;
    profileId: Decimal;
    postId: Decimal;
    doYouLike?: boolean;
    likesCount?: number;
}

Alpine.data('postLikes', (props: PostsProps) => {
    return {
        errors: {},
        saving: false,
        doYouLike: props.doYouLike ?? null,
        likesCount: props.likesCount ?? null,

        async init() {
            if (this.doYouLike != null && this.likesCount != null) {
                return;
            }
            this.saving = true;
            this.errors = {};

            try {
                const post = await showPosts(props.userId, props.profileId, props.postId, {
                    include: [ShowPostsIncludeKey.Likes, ShowPostsIncludeKey.LikesCount],
                });

                this.$dispatch('showPost', {
                    post: post,
                    profileId: props.profileId,
                    userId: props.userId,
                });
                console.log(post);

                this.likesCount = post.likesCount ?? 0;
                this.doYouLike = post.likes?.find((profile) => profile.id == props.profileId) != null;
            } catch (e) {
                if (axios.isAxiosError(e) && e?.response?.data) {
                    this.$dispatch('toast', {
                        type: 'error',
                        message: 'General Error',
                    });

                    // this.errors = apiValidationErrors(e?.response?.data);

                    // this.$dispatch('toast', {
                    //     type: 'error',
                    //     message: apiErrorMessage(
                    //         e?.response?.data,
                    //         props.messageError ?? window.polyglot.t('messages.form_submit_generic_error'),
                    //     ),
                    // });
                }
            } finally {
                this.saving = false;
            }
        },

        async likePost() {
            if (this.saving) {
                return;
            }
            this.saving = true;
            this.errors = {};

            try {
                const post = await createPostLike(props.userId, props.profileId, props.postId);

                this.doYouLike = true;
                this.likesCount = (this.likesCount ?? 0) + 1;

                this.$dispatch('postLikes', {
                    liked: true,
                    post: post,
                    profileId: props.profileId,
                    userId: props.userId,
                });
            } catch (e) {
                if (axios.isAxiosError(e) && e?.response?.data) {
                    this.$dispatch('toast', {
                        type: 'error',
                        message: 'General Error',
                    });

                    // this.errors = apiValidationErrors(e?.response?.data);

                    // this.$dispatch('toast', {
                    //     type: 'error',
                    //     message: apiErrorMessage(
                    //         e?.response?.data,
                    //         props.messageError ?? window.polyglot.t('messages.form_submit_generic_error'),
                    //     ),
                    // });
                }
            } finally {
                this.saving = false;
            }
        },

        async unlikePost() {
            if (this.saving) {
                return;
            }
            this.saving = true;
            this.errors = {};

            try {
                const post = await destroyPostLike(props.userId, props.profileId, props.postId);

                this.doYouLike = false;
                this.likesCount = (this.likesCount ?? 1) - 1;

                this.$dispatch('postLikes', {
                    liked: false,
                    post: post,
                    profileId: props.profileId,
                    userId: props.userId,
                });
            } catch (e) {
                if (axios.isAxiosError(e) && e?.response?.data) {
                    this.$dispatch('toast', {
                        type: 'error',
                        message: 'General Error',
                    });

                    // this.errors = apiValidationErrors(e?.response?.data);

                    // this.$dispatch('toast', {
                    //     type: 'error',
                    //     message: apiErrorMessage(
                    //         e?.response?.data,
                    //         props.messageError ?? window.polyglot.t('messages.form_submit_generic_error'),
                    //     ),
                    // });
                }
            } finally {
                this.saving = false;
            }
        },
    };
});
