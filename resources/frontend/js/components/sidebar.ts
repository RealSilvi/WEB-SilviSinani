import axios from 'axios';
import { Alpine } from '../livewire';
import { apiValidationErrors, STORAGE_PATH, STORAGE_PATH__PROFILE_PLACEHOLDER_IMAGE } from '../utils';
import { Profile, ProfileLink } from '../models';
import { API_USERS__PROFILES_INDEX } from '../api';
import { ROUTE_PROFILE_EDIT, ROUTE_PROFILE_NEW } from '../routes';

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
            this.fetch();
        },

        async fetch() {
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
                const { data } = await axios.request<{ data: Profile[] }>(API_USERS__PROFILES_INDEX(props.userId));

                this.profiles = data?.data ?? ([] as Profile[]);

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
                    src: profile.mainImage,
                    alt: `Profile image ${profile.nickname}`,
                    href: ROUTE_PROFILE_EDIT(profile.nickname),
                } as ProfileLink;
            });

            this.canAddProfile = this.profileLinks.length < 4;
        },
    };
});
