import axios from 'axios';
import { Alpine } from '../livewire';
import { Decimal } from '../utils';
import { showPosts, ShowPostsIncludeKey } from '../api/posts';
import { createPostLike, destroyPostLike } from '../api/postLikes';
import { showComment, ShowCommentIncludeKey } from '../api/postComments';
import { createCommentLike, destroyCommentLike } from '../api/postCommentLikes';

interface PostsProps {
    userId: Decimal;
    profileId: Decimal;
    postId: Decimal;
    commentId: Decimal;
    doYouLike?: boolean;
    likesCount?: number;
}

Alpine.data('commentLikes', (props: PostsProps) => {
    return {
        errors: {},
        saving: false,
        doYouLike: props.doYouLike ?? null,
        likesCount: props.likesCount ?? null,

        async init() {
            if (this.doYouLike != null && this.likesCount != null) {
                return;
            }
            this.saving = true;
            this.errors = {};

            try {
                const comment = await showComment(props.userId, props.profileId, props.postId, props.commentId, {
                    include: [ShowCommentIncludeKey.Likes, ShowCommentIncludeKey.LikesCount],
                });

                this.$dispatch('showComment', {
                    comment: comment,
                    postId: props.postId,
                    profileId: props.profileId,
                    userId: props.userId,
                });

                this.likesCount = comment.likesCount ?? 0;
                this.doYouLike = comment.likes?.find((profile) => profile.id == props.profileId) != null;
            } catch (e) {
                if (axios.isAxiosError(e) && e?.response?.data) {
                    this.$dispatch('toast', {
                        type: 'error',
                        message: 'General Error',
                    });

                    // this.errors = apiValidationErrors(e?.response?.data);

                    // this.$dispatch('toast', {
                    //     type: 'error',
                    //     message: apiErrorMessage(
                    //         e?.response?.data,
                    //         props.messageError ?? window.polyglot.t('messages.form_submit_generic_error'),
                    //     ),
                    // });
                }
            } finally {
                this.saving = false;
            }
        },

        async likeComment() {
            if (this.saving) {
                return;
            }
            this.saving = true;
            this.errors = {};

            try {
                const comment = await createCommentLike(props.userId, props.profileId, props.postId, props.commentId);

                this.doYouLike = true;
                this.likesCount = (this.likesCount ?? 0) + 1;

                this.$dispatch('commentLikes', {
                    liked: true,
                    commentId: comment,
                    postId: props.postId,
                    profileId: props.profileId,
                    userId: props.userId,
                });
            } catch (e) {
                if (axios.isAxiosError(e) && e?.response?.data) {
                    this.$dispatch('toast', {
                        type: 'error',
                        message: 'General Error',
                    });

                    // this.errors = apiValidationErrors(e?.response?.data);

                    // this.$dispatch('toast', {
                    //     type: 'error',
                    //     message: apiErrorMessage(
                    //         e?.response?.data,
                    //         props.messageError ?? window.polyglot.t('messages.form_submit_generic_error'),
                    //     ),
                    // });
                }
            } finally {
                this.saving = false;
            }
        },

        async unlikeComment() {
            if (this.saving) {
                return;
            }
            this.saving = true;
            this.errors = {};

            try {
                const comment = await destroyCommentLike(props.userId, props.profileId, props.postId, props.commentId);

                this.doYouLike = false;
                this.likesCount = (this.likesCount ?? 1) - 1;

                this.$dispatch('commentLikes', {
                    liked: false,
                    comment: comment,
                    postId: props.postId,
                    profileId: props.profileId,
                    userId: props.userId,
                });
            } catch (e) {
                if (axios.isAxiosError(e) && e?.response?.data) {
                    this.$dispatch('toast', {
                        type: 'error',
                        message: 'General Error',
                    });

                    // this.errors = apiValidationErrors(e?.response?.data);

                    // this.$dispatch('toast', {
                    //     type: 'error',
                    //     message: apiErrorMessage(
                    //         e?.response?.data,
                    //         props.messageError ?? window.polyglot.t('messages.form_submit_generic_error'),
                    //     ),
                    // });
                }
            } finally {
                this.saving = false;
            }
        },
    };
});
