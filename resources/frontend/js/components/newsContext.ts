import axios from 'axios';
import { Alpine } from '../livewire';
import { Decimal } from '../utils';
import { News, NewsType } from '../models';
import { indexNews, IndexNewsFilterKey, IndexNewsIncludeKey, seeAllNews } from '../api/profileNews';

interface newsContextProps {
    userId: Decimal;
    profileId: Decimal;
    authProfileId: Decimal;
    onSuccessMessage?: string;
    onFailMessage?: string;
}

Alpine.data('newsContext', (props: newsContextProps) => {
    return {
        errors: {},
        saving: false,
        followRequests: [] as News[],
        followRequestsPage: 1,
        lastFollowRequestsPage: false,
        generalNews: [] as News[],
        generalNewsPage: 1,
        lastGeneralNewsPage: false,

        async init() {
            await this.fetchFollowRequests();
            await this.fetchGeneralNews();
            await this.seeAll();
        },

        async fetchFollowRequests() {
            if (this.saving) {
                return;
            }
            this.saving = true;
            this.errors = {};

            try {
                const followRequests = await indexNews(props.userId, props.profileId, {
                    include: [IndexNewsIncludeKey.From],
                    filter: { [IndexNewsFilterKey.Type]: NewsType.FollowRequest },
                    page: this.followRequestsPage,
                    perPage: 3,
                });

                if (followRequests.length < 3) {
                    this.lastFollowRequestsPage = true;
                }

                this.followRequests = [...(this.followRequests ?? []), ...followRequests];

                this.$dispatch('fetch-follow-request-news', {
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

        async loadMoreFollowRequests() {
            if (this.lastFollowRequestsPage) {
                return;
            }

            this.followRequestsPage += 1;

            await this.fetchFollowRequests();
        },

        async fetchGeneralNews() {
            if (this.saving) {
                return;
            }
            this.saving = true;
            this.errors = {};

            try {
                const generalNews = await indexNews(props.userId, props.profileId, {
                    include: [IndexNewsIncludeKey.From],
                    filter: { [IndexNewsFilterKey.NotType]: NewsType.FollowRequest },
                    page: this.generalNewsPage,
                });

                if (generalNews.length < 10) {
                    this.lastGeneralNewsPage = true;
                }

                console.log(generalNews);

                this.generalNews = [...(this.generalNews ?? []), ...generalNews];

                this.$dispatch('fetch-general-news', {
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

        async loadMoreGeneralNews() {
            if (this.lastGeneralNewsPage) {
                return;
            }

            this.generalNewsPage += 1;

            await this.fetchGeneralNews();
        },

        async seeAll() {
            if (this.saving) {
                return;
            }
            this.saving = true;
            this.errors = {};

            try {
                await seeAllNews(props.userId, props.profileId);

                this.$dispatch('see-all-news', {
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

        onFollowerRequestInteracted(event: Event) {
            // @ts-ignore
            if (!event.detail.followerId) {
                console.error('[onFollowerRequestInteracted] followerId is required');
                return;
            }

            // @ts-ignore
            const followerId = event.detail.followerId;

            this.followRequests = this.followRequests.filter((f: News) => f.fromId !== followerId);
        },
    };
});
