import axios from 'axios';
import { Alpine } from '../../livewire';
import { Decimal } from '../../utils';
import { acceptFollowRequest, destroyFollowerOrFollowRequest } from '../../api/profileFollowers';
import { showProfile, ShowProfileIncludeKey } from '../../api/profiles';
import { destroyFollowingOrFollowingRequest, sendFollowRequest } from '../../api/profileFollowing';

interface ProfileFollowing {
    userId: Decimal;
    profileId: Decimal;
    followerId: Decimal;
}

Alpine.data('profileFollowing', (props: ProfileFollowing) => {
    return {
        errors: {},
        saving: false,
        friendshipStatus: '',
        async loadFriendshipStatus() {
            if (this.saving) {
                return;
            }
            this.saving = true;
            this.errors = {};

            try {
                const profile = await showProfile(props.userId, props.profileId, {
                    include: [ShowProfileIncludeKey.Following, ShowProfileIncludeKey.SentRequests],
                });

                this.friendshipStatus = profile.sentRequests?.find((p) => p.id === props.followerId)
                    ? profile.following?.find((p) => p.id === props.followerId)
                        ? 'Following'
                        : 'Waiting'
                    : 'Follow';

                this.$dispatch('load-friendship-status', {
                    followerId: props.followerId,
                    profileId: props.profileId,
                    userId: props.userId,
                });
            } catch (e) {
                if (axios.isAxiosError(e) && e?.response?.data) {
                    this.$dispatch('toast', {
                        type: 'error',
                        message: 'Error',
                    });
                }
            } finally {
                this.saving = false;
            }
        },
        async sendFollowRequest(onSuccessMessage?: string, onFailMessage?: string) {
            if (this.saving) {
                return;
            }
            this.saving = true;
            this.errors = {};

            try {
                await sendFollowRequest(props.userId, props.profileId, {
                    followerId: props.followerId,
                });

                this.friendshipStatus = 'Waiting';

                this.$dispatch('toast', {
                    type: 'success',
                    message: onSuccessMessage ?? 'Success',
                });

                this.$dispatch('sent-follow-request', {
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
                await destroyFollowingOrFollowingRequest(props.userId, props.profileId, props.followerId);

                this.friendshipStatus = 'Follow';

                this.$dispatch('toast', {
                    type: 'success',
                    message: onSuccessMessage ?? 'Success',
                });

                this.$dispatch('delete-following', {
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

        async triggerFollowingRequest() {
            this.friendshipStatus == 'Follow' ? this.sendFollowRequest() : this.deleteFollowRequest();
        },
    };
});
