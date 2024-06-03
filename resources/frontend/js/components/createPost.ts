import axios from 'axios';
import { Alpine } from '../livewire';
import { Decimal } from '../utils';
import { createPost } from '../api/posts';

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
                    post: post,
                });

                this.$dispatch('createPost', {
                    target: event.target,
                    post: post,
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
