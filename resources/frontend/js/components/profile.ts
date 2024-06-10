import axios from 'axios';
import { Alpine } from '../livewire';
import { Decimal } from '../utils';
import {
    createProfile,
    CreateProfileInput,
    destroyProfile,
    ProfileType,
    updateProfile,
    UpdateProfileInput,
} from '../api/profiles';

interface ProfileProps {
    userId: Decimal;
}

Alpine.data('profile', (props: ProfileProps) => {
    return {
        errors: {},
        saving: false,

        async createProfile(event: SubmitEvent, onSuccessMessage?: string, onFailMessage?: string) {
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

                if (!data.has('nickname') || !data.has('type')) {
                    console.error('[createPost] nickname and type input are required');
                    return;
                }

                let nickname = data.get('nickname');
                let defaultProfile = data.get('default');
                let dateOfBirth = data.get('dateOfBirth');
                let breed = data.get('breed');
                let mainImage = data.get('mainImage');
                let secondaryImage = data.get('secondaryImage');
                let bio = data.get('bio');
                let type = data.get('type');

                const input = {
                    nickname: typeof nickname === 'string' ? nickname : undefined,
                    type: typeof type === 'string' ? type : undefined,
                    default: typeof defaultProfile === 'object' ? defaultProfile : undefined,
                    dateOfBirth: typeof dateOfBirth === 'string' ? dateOfBirth : undefined,
                    breed: typeof breed === 'string' ? breed : undefined,
                    mainImage: mainImage instanceof File ? mainImage : undefined,
                    secondaryImage: secondaryImage instanceof File ? secondaryImage : undefined,
                    bio: typeof bio === 'string' ? bio : undefined,
                } as CreateProfileInput;

                const profile = await createProfile(props.userId, input);

                event.target.reset();

                this.$dispatch('toast', {
                    type: 'success',
                    message: onSuccessMessage ?? 'Success',
                });

                this.$dispatch('create-profile', {
                    profile: profile,
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

        async destroyProfile(profileId: Decimal, onSuccessMessage?: string, onFailMessage?: string) {
            if (this.saving) {
                return;
            }
            this.saving = true;
            this.errors = {};

            try {
                await destroyProfile(props.userId, profileId);

                this.$dispatch('toast', {
                    type: 'success',
                    message: onSuccessMessage ?? 'Success',
                });

                this.$dispatch('destroy-profile', {
                    profileId: profileId,
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

        async updateProfile(event: SubmitEvent, profileId: Decimal, onSuccessMessage?: string, onFailMessage?: string) {
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

                let nickname = data.get('nickname');
                let defaultProfile = data.get('default');
                let dateOfBirth = data.get('dateOfBirth');
                let breed = data.get('breed');
                let mainImage = data.get('mainImage');
                let secondaryImage = data.get('secondaryImage');
                let bio = data.get('bio');

                const input = {
                    nickname: typeof nickname === 'string' ? nickname : undefined,
                    default: typeof defaultProfile === 'string' ? defaultProfile : undefined,
                    dateOfBirth: typeof dateOfBirth === 'string' ? dateOfBirth : undefined,
                    breed: typeof breed === 'string' ? breed : undefined,
                    mainImage: mainImage instanceof File ? mainImage : undefined,
                    secondaryImage: secondaryImage instanceof File ? secondaryImage : undefined,
                    bio: typeof bio === 'string' ? bio : undefined,
                } as UpdateProfileInput;

                const profile = await updateProfile(props.userId, profileId, input);

                event.target.reset();

                this.$dispatch('toast', {
                    type: 'success',
                    message: onSuccessMessage ?? 'Success',
                });

                this.$dispatch('update-profile', {
                    profile: profile,
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
