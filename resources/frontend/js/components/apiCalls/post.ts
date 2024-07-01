import axios from 'axios';
import { Alpine } from '../../livewire';
import { Decimal } from '../../utils';
import { createPost, destroyPost } from '../../api/posts';

interface PostProps {
    userId: Decimal;
    profileId: Decimal;
}

Alpine.data('post', (props: PostProps) => {
    return {
        errors: {},
        saving: false,

        async createPost(event: SubmitEvent, onSuccessMessage?: string, onFailMessage?: string) {
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
                    message: onSuccessMessage ?? 'Success',
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
                        message: onFailMessage ?? 'Error',
                    });
                }
            } finally {
                this.saving = false;
            }
        },

        async deletePost(postId: Decimal, onSuccessMessage?: string, onFailMessage?: string) {
            if (this.saving) {
                return;
            }
            this.saving = true;
            this.errors = {};

            try {
                await destroyPost(props.userId, props.profileId, postId);

                this.$dispatch('toast', {
                    type: 'success',
                    message: onSuccessMessage ?? 'Success',
                });

                this.$dispatch('destroy-post', {
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
