import axios from 'axios';
import { Alpine } from '../../livewire';
import { Decimal } from '../../utils';
import { createComment, destroyComment } from '../../api/postComments';

interface CommentProps {
    userId: Decimal;
    profileId: Decimal;
}

Alpine.data('comment', (props: CommentProps) => {
    return {
        errors: {},
        saving: false,

        async createComment(event: SubmitEvent, postId: Decimal, onSuccessMessage?: string, onFailMessage?: string) {
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

                if (!data.has('body')) {
                    console.error('[createComment] body is required');
                    return;
                }

                let body = data.get('body') ?? undefined;

                if (typeof body != 'string') {
                    body = undefined;
                }
                if (body == null) {
                    return;
                }

                const comment = await createComment(props.userId, props.profileId, postId, {
                    body: body,
                });

                event.target.reset();

                this.$dispatch('toast', {
                    type: 'success',
                    message: onSuccessMessage ?? 'Success',
                });

                this.$dispatch('create-comment', {
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

        async deleteComment(postId: Decimal, commentId: Decimal, onSuccessMessage?: string, onFailMessage?: string) {
            if (this.saving) {
                return;
            }

            this.saving = true;
            this.errors = {};

            try {
                await destroyComment(props.userId, props.profileId, postId, commentId);

                this.$dispatch('toast', {
                    type: 'success',
                    message: onSuccessMessage ?? 'Success',
                });

                this.$dispatch('destroy-comment', {
                    commentId: commentId,
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
