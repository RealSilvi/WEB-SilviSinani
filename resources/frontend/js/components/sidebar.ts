import axios from 'axios';
import { Alpine } from '../livewire';
import { apiValidationErrors } from '../utils';
import { Profile, ProfileLink } from '../models';
import { ROUTE_PROFILE_EDIT, ROUTE_PROFILE_NEW } from '../routes';
import { indexProfile } from '../api/profile';

interface NavbarProps {
    userId?: number;
}

Alpine.data('sidebar', (props: NavbarProps) => {
    return {
        saving: false,
        canAddProfile: false,
        profiles: [] as Profile[],
        profileLinks: [] as ProfileLink[],
        errors: {},

        init() {
            this.fetchProfiles();
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

                this.buildLinks();
            } catch (e) {
                if (axios.isAxiosError(e) && e?.response?.data) {
                    this.errors = apiValidationErrors(e?.response?.data);
                }
                //TODO Gestisci gli errori
            } finally {
                this.saving = false;
            }
        },

        buildLinks() {
            this.profileLinks = this.profiles.map((profile: Profile) => {
                return {
                    profileId: profile.id,
                    src: `${window.location.origin}/${profile.mainImage}`,
                    alt: `Profile image ${profile.nickname}`,
                    href: ROUTE_PROFILE_EDIT(profile.nickname),
                    nickname: profile.nickname,
                } as ProfileLink;
            });

            this.canAddProfile = this.profileLinks.length < 4;
        },
    };
});
