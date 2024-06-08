import axios from 'axios';
import { Alpine } from '../livewire';
import { Profile, ProfileLink } from '../models';
import { ROUTE_PROFILE_EDIT } from '../routes';
import { indexProfile } from '../api/profiles';
import { getCurrentLocale } from '../utils';

interface NavbarProps {
    userId: number;
    onSuccessMessage?: string;
    onFailMessage?: string;
}

Alpine.data('sidebar', (props: NavbarProps) => {
    return {
        saving: false,
        canAddProfile: false,
        profiles: [] as Profile[],
        profileLinks: [] as ProfileLink[],
        errors: {},

        async init() {
            await this.fetchProfiles();

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
                this.profiles = (await indexProfile(props.userId)) ?? ([] as Profile[]);

                if (props.onSuccessMessage) {
                    this.$dispatch('toast', {
                        type: 'success',
                        message: props.onSuccessMessage
                    });
                }
                this.buildLinks();
            } catch (e) {
                if (axios.isAxiosError(e) && e?.response?.data) {
                    this.$dispatch('toast', {
                        type: 'error',
                        message: props.onFailMessage ?? 'Error'
                    });
                }
            } finally {
                this.saving = false;
            }
        },

        buildLinks() {
            const locale = getCurrentLocale();
            let currentProfileFound = false;

            this.profileLinks = this.profiles.map((profile: Profile) => {

                const explicitActive = window.location.pathname.includes(`/${profile.nickname}`);
                if (explicitActive) {
                    currentProfileFound = true;
                }

                return {
                    profileId: profile.id,
                    src: `${window.location.origin}/${profile.mainImage}`,
                    alt: `Profile image ${profile.nickname}`,
                    href: locale + ROUTE_PROFILE_EDIT(profile.nickname),
                    nickname: profile.nickname,
                    currentActive: explicitActive
                } as ProfileLink;
            });

            if (!currentProfileFound) {
                const defaultProfileId = this.profiles.find((profile: Profile) => profile.default)?.id ?? '-1';
                const defaultLink = this.profileLinks.find((link: ProfileLink) => link.profileId === defaultProfileId);
                if (defaultLink) {
                    defaultLink.currentActive = true;
                }
            }

            this.canAddProfile = this.profileLinks.length < 4;
        }
    };
});
