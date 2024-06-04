import axios from 'axios';
import { Alpine } from '../livewire';
import { Decimal } from '../utils';
import { showPosts, ShowPostsIncludeKey } from '../api/posts';
import { createPostLike, destroyPostLike } from '../api/postLikes';
import { showComment, ShowCommentIncludeKey } from '../api/postComments';
import { createCommentLike, destroyCommentLike } from '../api/postCommentLikes';

interface CommentLikeProps {
    userId: Decimal;
    profileId: Decimal;
}

Alpine.data('commentLike', (props: CommentLikeProps) => {
    return {
        errors: {},
        saving: false,

        async likeComment(postId: Decimal, commentId: Decimal) {
            if (this.saving) {
                return;
            }
            this.saving = true;
            this.errors = {};

            try {
                const comment = await createCommentLike(props.userId, props.profileId, postId, commentId);

                this.$dispatch('toast', {
                    type: 'success',
                    message: 'Comment liked',
                });

                this.$dispatch('comment-liked', {
                    comment: comment,
                    postId: postId,
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

        async unlikeComment(postId: Decimal, commentId: Decimal) {
            if (this.saving) {
                return;
            }
            this.saving = true;
            this.errors = {};

            try {
                const comment = await destroyCommentLike(props.userId, props.profileId, postId, commentId);

                this.$dispatch('toast', {
                    type: 'success',
                    message: 'Comment liked removed',
                });

                this.$dispatch('comment-liked-removed', {
                    comment: comment,
                    postId: postId,
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
