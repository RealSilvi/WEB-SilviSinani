import axios from 'axios';
import { Alpine } from '../livewire';
import { indexProfile } from '../api/profiles';
import { profileToProfilePreviews } from '../utils';
import { ProfilePreview } from '../models';

interface SidebarProps {
    userId: number;
    authProfileNickname: string;
    onSuccessMessage?: string;
    onFailMessage?: string;
}

Alpine.data('sidebar', (props: SidebarProps) => {
    return {
        saving: false,
        canAddProfile: false,
        profiles: [] as ProfilePreview[],
        errors: {},

        async init() {
            await this.fetchProfiles();
            this.setCurrentProfile();
        },

        async fetchProfiles() {
            if (!props.userId) {
                console.error('[sidebar] userId is required');
                return;
            }

            if (this.saving) {
                return;
            }

            this.saving = true;
            this.errors = {};

            try {
                const profiles = await indexProfile(props.userId);

                this.profiles = [...this.profiles, ...profileToProfilePreviews(profiles)];

                this.canAddProfile = this.profiles.length < 4;

                if (props.onSuccessMessage) {
                    this.$dispatch('toast', {
                        type: 'success',
                        message: props.onSuccessMessage,
                    });
                }
            } catch (e) {
                if (axios.isAxiosError(e) && e?.response?.data) {
                    this.$dispatch('toast', {
                        type: 'error',
                        message: props.onFailMessage ?? 'Error',
                    });
                }
            } finally {
                this.saving = false;
            }
        },

        setCurrentProfile() {
            let currentProfileFound = false;

            this.profiles = this.profiles.map((profile: ProfilePreview) => {
                const explicitActive = window.location.pathname.includes(`/${profile.nickname}`);
                if (explicitActive) {
                    currentProfileFound = true;
                }

                return {
                    ...profile,
                    currentActive: explicitActive,
                } as ProfilePreview;
            });

            if (!currentProfileFound) {
                const defaultProfile = this.profiles.find((profile: ProfilePreview) => profile.default);
                if (defaultProfile) {
                    defaultProfile.currentActive = true;
                }
            }
        },
        onImageUpdated() {
            this.profiles = this.profiles.map((profile: ProfilePreview) => {
                return {
                    ...profile,
                    mainImage: `${profile.mainImage}?${new Date().getTime()}`,
                } as ProfilePreview;
            });
        },
    };
});
