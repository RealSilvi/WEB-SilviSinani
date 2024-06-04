import axios from 'axios';
import { Alpine } from '../livewire';
import { apiErrorMessage, apiValidationErrors, Decimal } from '../utils';
import { createComment, destroyComment } from '../api/postComments';

interface CommentProps {
    userId: Decimal;
    profileId: Decimal;
}

Alpine.data('comment', (props: CommentProps) => {
    return {
        errors: {},
        saving: false,

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
                    message: 'Comment created',
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

        async deleteComment(postId: Decimal, commentId: Decimal) {
            if (this.saving) {
                return;
            }

            this.saving = true;
            this.errors = {};

            try {
                await destroyComment(props.userId, props.profileId, postId, commentId);

                this.$dispatch('toast', {
                    type: 'success',
                    message: 'Comment deleted',
                });

                this.$dispatch('destroy-comment', {
                    commentId: commentId,
                    postId: postId,
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
    };
});
