import axios from 'axios';
import { Alpine } from '../livewire';
import { apiErrorMessage, apiValidationErrors, Decimal } from '../utils';
import { createPost, CreatePostInput } from '../api/posts';
import { i } from 'vite/dist/node/types.d-aGj9QkWt';

interface PostsProps {
    userId: Decimal;
    profileId: Decimal;
}

Alpine.data('createPost', (props: PostsProps) => {
    return {
        errors: {},
        saving: false,

        async execute(event: SubmitEvent) {
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

                this.$dispatch('toast', {
                    type: 'success',
                    message: 'Post created',
                    post: post,
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
