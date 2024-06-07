import axios from 'axios';
import { Alpine } from '../livewire';
import { Decimal } from '../utils';
import { createCommentLike, destroyCommentLike } from '../api/postCommentLikes';

interface CommentLikeProps {
    userId: Decimal;
    profileId: Decimal;
}

Alpine.data('commentLike', (props: CommentLikeProps) => {
    return {
        errors: {},
        saving: false,

        async likeComment(postId: Decimal, commentId: Decimal, onSuccessMessage?: string, onFailMessage?: string) {
            if (this.saving) {
                return;
            }
            this.saving = true;
            this.errors = {};

            try {
                const comment = await createCommentLike(props.userId, props.profileId, postId, commentId);

                this.$dispatch('toast', {
                    type: 'success',
                    message: onSuccessMessage ?? 'Success',
                });

                this.$dispatch('comment-liked', {
                    comment: comment,
                    postId: postId,
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

        async unlikeComment(postId: Decimal, commentId: Decimal, onSuccessMessage?: string, onFailMessage?: string) {
            if (this.saving) {
                return;
            }
            this.saving = true;
            this.errors = {};

            try {
                const comment = await destroyCommentLike(props.userId, props.profileId, postId, commentId);

                this.$dispatch('toast', {
                    type: 'success',
                    message: onSuccessMessage ?? 'Success',
                });

                this.$dispatch('comment-liked-removed', {
                    comment: comment,
                    postId: postId,
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
