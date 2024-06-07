import axios from 'axios';
import { Alpine } from '../livewire';
import { Decimal } from '../utils';
import { createPostLike, destroyPostLike } from '../api/postLikes';

interface PostLikeProps {
    userId: Decimal;
    profileId: Decimal;
}

Alpine.data('postLike', (props: PostLikeProps) => {
    return {
        errors: {},
        saving: false,

        async likePost(postId: Decimal, onSuccessMessage?: string, onFailMessage?: string) {
            if (this.saving) {
                return;
            }
            this.saving = true;
            this.errors = {};

            try {
                const post = await createPostLike(props.userId, props.profileId, postId);

                this.$dispatch('toast', {
                    type: 'success',
                    message: onSuccessMessage ?? 'Success',
                });

                this.$dispatch('post-liked', {
                    post: post,
                    profileId: props.profileId,
                    userId: props.userId,
                });
            } catch (e) {
                if (axios.isAxiosError(e) && e?.response?.data) {
                    this.$dispatch('toast', {
                        type: 'error',
                        message: onFailMessage ?? 'Error',
                    });
                }
            } finally {
                this.saving = false;
            }
        },

        async unlikePost(postId: Decimal, onSuccessMessage?: string, onFailMessage?: string) {
            if (this.saving) {
                return;
            }
            this.saving = true;
            this.errors = {};

            try {
                const post = await destroyPostLike(props.userId, props.profileId, postId);

                this.$dispatch('toast', {
                    type: 'success',
                    message: onSuccessMessage ?? 'Success',
                });

                this.$dispatch('post-liked-removed', {
                    post: post,
                    profileId: props.profileId,
                    userId: props.userId,
                });
            } catch (e) {
                if (axios.isAxiosError(e) && e?.response?.data) {
                    this.$dispatch('toast', {
                        type: 'error',
                        message: onFailMessage ?? 'Error',
                    });
                }
            } finally {
                this.saving = false;
            }
        },
    };
});
