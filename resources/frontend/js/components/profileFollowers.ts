import axios from 'axios';
import { Alpine } from '../livewire';
import { Decimal } from '../utils';
import { acceptFollowRequest, destroyFollowerOrFollowRequest } from '../api/profileFollowers';

interface ProfileFollowers {
    userId: Decimal;
    profileId: Decimal;
    followerId: Decimal;
}

Alpine.data('profileFollowers', (props: ProfileFollowers) => {
    return {
        errors: {},
        saving: false,
        async acceptFollowRequest(onSuccessMessage?: string, onFailMessage?: string) {
            if (this.saving) {
                return;
            }
            this.saving = true;
            this.errors = {};

            try {
                await acceptFollowRequest(props.userId, props.profileId, {
                    followerId: props.followerId,
                });

                this.$dispatch('toast', {
                    type: 'success',
                    message: onSuccessMessage ?? 'Success',
                });

                this.$dispatch('follower-accepted', {
                    followerId: props.followerId,
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

        async deleteFollowRequest(onSuccessMessage?: string, onFailMessage?: string) {
            if (this.saving) {
                return;
            }
            this.saving = true;
            this.errors = {};

            try {
                await destroyFollowerOrFollowRequest(props.userId, props.profileId, props.followerId);

                this.$dispatch('toast', {
                    type: 'success',
                    message: onSuccessMessage ?? 'Success',
                });

                this.$dispatch('delete-follower', {
                    followerId: props.followerId,
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
