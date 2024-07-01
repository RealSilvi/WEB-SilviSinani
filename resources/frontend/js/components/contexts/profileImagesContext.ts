import axios from 'axios';
import { Alpine } from '../../livewire';
import { Decimal } from '../../utils';
import {
    createProfile,
    CreateProfileInput,
    destroyProfile,
    ProfileType,
    showProfile,
    updateProfile,
    UpdateProfileInput,
} from '../../api/profiles';
import { Profile } from '../../models';

interface profileImagesContextProps {
    userId: Decimal;
    profileId: Decimal;
}

Alpine.data('profileImagesContext', (props: profileImagesContextProps) => {
    return {
        errors: {},
        saving: false,
        profile: {} as Profile,

        async init() {
            await this.showProfile();
        },
        async showProfile(onSuccessMessage?: string, onFailMessage?: string) {
            if (this.saving) {
                return;
            }

            this.saving = true;
            this.errors = {};

            try {
                const profile = await showProfile(props.userId, props.profileId);

                profile.mainImage = `${profile.mainImage}?${new Date().getTime()}`;
                profile.secondaryImage = `${profile.secondaryImage}?${new Date().getTime()}`;

                this.profile = profile;

                this.$dispatch('profile-loaded', {
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
        async editImage(
            event: SubmitEvent,
            context: 'PROFILE' | 'BACKGROUND',
            onSuccessMessage?: string,
            onFailMessage?: string,
        ) {
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

                if (!data.has('image')) {
                    console.error('[editImage] image input is required');
                    return;
                }

                let image = data.get('image') ?? undefined;

                if (!(image instanceof File) || image.name == '') {
                    return;
                }

                const input =
                    context === 'PROFILE'
                        ? ({ mainImage: image } as UpdateProfileInput)
                        : ({ secondaryImage: image } as UpdateProfileInput);

                const profile = await updateProfile(props.userId, props.profileId, input);

                this.profile.mainImage = `${profile.mainImage}?${new Date().getTime()}`;
                this.profile.secondaryImage = `${profile.secondaryImage}?${new Date().getTime()}`;

                this.$dispatch('image-updated', {
                    context: context,
                    image: context === 'PROFILE' ? this.profile.mainImage : this.profile.secondaryImage,
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
