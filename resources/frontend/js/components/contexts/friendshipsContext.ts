import axios from 'axios';
import { Alpine } from '../../livewire';
import { Decimal, profileToProfilePreviews } from '../../utils';
import { Profile, ProfilePreview } from '../../models';
import { destroyFollowerOrFollowRequest, indexMyFollowers } from '../../api/profileFollowers';
import { destroyFollowingOrFollowingRequest, indexWhoFollow } from '../../api/profileFollowing';

interface friendshipsContextProps {
    userId: Decimal;
    profileId: Decimal;
    authProfileId: Decimal;
    authProfileNickname: string;
    context: 'FOLLOWERS' | 'FOLLOWING';
    onSuccessMessage?: string;
    onFailMessage?: string;
}

Alpine.data('friendshipsContext', (props: friendshipsContextProps) => {
    return {
        errors: {},
        saving: false,
        friends: [] as ProfilePreview[],
        friendPage: 0,
        lastFriendsPage: false,

        async init() {
            props.context === 'FOLLOWERS' ? await this.fetchFollowers() : await this.fetchFollowing();
        },

        async fetchFollowers() {
            if (this.saving) {
                return;
            }
            this.saving = true;
            this.errors = {};

            try {
                const followers = await indexMyFollowers(props.userId, props.profileId, {
                    page: this.friendPage,
                });

                if (followers.length < 9) {
                    this.lastFriendsPage = true;
                }

                this.friends = [
                    ...(this.friends ?? []),
                    ...profileToProfilePreviews(followers, props.authProfileNickname),
                ];

                this.$dispatch('fetch-followers', {
                    profileId: props.profileId,
                    userId: props.userId,
                });
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

        async fetchFollowing() {
            if (this.saving) {
                return;
            }
            this.saving = true;
            this.errors = {};

            try {
                const following = await indexWhoFollow(props.userId, props.profileId, {
                    page: this.friendPage,
                });

                if (following.length < 10) {
                    this.lastFriendsPage = true;
                }

                this.friends = [
                    ...(this.friends ?? []),
                    ...profileToProfilePreviews(following, props.authProfileNickname),
                ];

                this.$dispatch('fetch-following', {
                    profileId: props.profileId,
                    userId: props.userId,
                });
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

        async loadMore() {
            if (this.lastFriendsPage) {
                return;
            }

            this.friendPage += 1;

            props.context === 'FOLLOWERS' ? await this.fetchFollowers() : await this.fetchFollowing();
        },

        async deleteFriend(friendId: Decimal) {
            if (this.saving) {
                return;
            }
            this.saving = true;
            this.errors = {};

            try {
                props.context === 'FOLLOWERS'
                    ? await destroyFollowerOrFollowRequest(props.userId, props.profileId, friendId)
                    : await destroyFollowingOrFollowingRequest(props.userId, props.profileId, friendId);

                this.$dispatch('delete-friend', {
                    friendId: friendId,
                    profileId: props.profileId,
                    userId: props.userId,
                });
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

        onFriendInteracted(event?: Event) {
            // @ts-ignore
            if (!event.detail.friendId) {
                console.error('[onFriendInteracted] friendId is required');
                return;
            }

            // @ts-ignore
            const friendId = event.detail.friendId;

            this.friends = this.friends.filter((p: Profile) => p.id !== friendId);
        },
    };
});
