import axios from 'axios';
import { Alpine } from '../livewire';
import { apiValidationErrors } from '../utils';
import { Profile, ProfileLink } from '../models';
import { API_USERS__PROFILES_INDEX } from '../api';

interface NavbarProps {
    userId?: number;
}

Alpine.data('sidebar', (props: NavbarProps) => {

    return {
        saving: false,
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

                const { data } = await axios.request<{ data: Profile[] }>(
                    API_USERS__PROFILES_INDEX(props.userId)
                );

                this.profiles = data?.data ?? [] as Profile [];

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
                    href: '#'
                } as ProfileLink;
            });

            if (this.profileLinks.length < 4) {
                this.profileLinks = [...this.profileLinks, {
                    profileId: null,
                    src: '/storage/utilities/image-placeholder.png',
                    alt: 'Add new profile Image',
                    href: '#'
                } as ProfileLink];
            }
        }
    };
});
