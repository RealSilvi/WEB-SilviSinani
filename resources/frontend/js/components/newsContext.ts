import axios from 'axios';
import { Alpine } from '../livewire';
import { Decimal, postsToPostPreviews } from '../utils';
import { Comment, CommentPreview, News, Post, PostPreview, Profile } from '../models';
import { indexPosts, IndexPostsIncludeKey, showPosts, ShowPostsIncludeKey } from '../api/posts';
import { ROUTE_DASHBOARD, ROUTE_PROFILE_EDIT } from '../routes';
import { showDashboard, ShowDashboardIncludeKey } from '../api/dashboard';
import { indexComment, IndexCommentIncludeKey } from '../api/postComments';
import { indexNews, IndexNewsIncludeKey, seeAllNews } from '../api/profileNews';

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
        news: [] as News[],
        page: 0,
        lastCommentPage: false,

        async init() {
            await this.fetchNews();
            await this.seeAll();
        },

        async fetchNews() {
            if (this.saving) {
                return;
            }
            this.saving = true;
            this.errors = {};

            try {
                const news = await indexNews(props.userId, props.profileId, {
                    include: [IndexNewsIncludeKey.Profile, IndexNewsIncludeKey.From],
                    page: this.page,
                });

                if (news.length == 0) {
                    this.lastCommentPage = true;
                    return;
                }

                this.news = [...(this.news ?? []), ...news];

                if (props.onSuccessMessage) {
                    this.$dispatch('toast', {
                        type: 'success',
                        message: props.onSuccessMessage,
                    });
                }

                this.$dispatch('fetch-news', {
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
            if (this.lastCommentPage) {
                return;
            }

            this.page += 1;

            await this.fetchNews();
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
    };
});
