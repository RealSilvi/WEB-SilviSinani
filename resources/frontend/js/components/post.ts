import axios from 'axios';
import { Alpine } from '../livewire';
import { apiErrorMessage, apiValidationErrors, Decimal } from '../utils';
import { createPost, destroyPost } from '../api/posts';

interface PostProps {
    userId: Decimal;
    profileId: Decimal;
}

Alpine.data('post', (props: PostProps) => {
    return {
        errors: {},
        saving: false,

        async createPost(event: SubmitEvent) {
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

                if (!data.has('description') || !data.has('image')) {
                    console.error('[createPost] description and image input are required');
                    return;
                }

                let description = data.get('description') ?? undefined;
                let image = data.get('image') ?? undefined;

                if (!(image instanceof File) || image.name == '') {
                    image = undefined;
                }
                if (typeof description != 'string' || description == '') {
                    description = undefined;
                }
                if (description == null && image == null) {
                    return;
                }

                const post = await createPost(props.userId, props.profileId, {
                    description: description,
                    image: image,
                });

                event.target.reset();

                this.$dispatch('toast', {
                    type: 'success',
                    message: 'Post created',
                });

                this.$dispatch('create-post', {
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

        async deletePost(postId: Decimal) {
            if (this.saving) {
                return;
            }
            this.saving = true;
            this.errors = {};

            try {
                await destroyPost(props.userId, props.profileId, postId);

                this.$dispatch('toast', {
                    type: 'success',
                    message: 'Post deleted',
                });

                this.$dispatch('destroy-post', {
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
