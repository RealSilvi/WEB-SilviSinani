import { AxiosRequestConfig } from 'axios';
import { Comment, CommentPreview, Post, PostPreview } from './models';
import { ROUTE_PROFILE_EDIT } from './routes';

export type ApiAction = Pick<AxiosRequestConfig, 'url' | 'method' | 'params' | 'data' | 'headers'>;
export type Decimal = string | number;

export function postsToPostPreviews(posts: Post[], authProfileId: Decimal): PostPreview[] {
    return posts.map((post: Post) => {
        const doYouLike = post.likes?.find((profile) => profile.id == authProfileId) != null;
        const canEdit = post.profileId == authProfileId;
        const profileLink = post.profile ? ROUTE_PROFILE_EDIT(post.profile.nickname) : '#';

        if (post.comments == null) {
            return {
                ...post,
                doYouLike: doYouLike,
                canEdit: canEdit,
                commentPreviews: [],
                profileLink: profileLink,
            } as PostPreview;
        }

        const commentPreviews = post.comments
            .map((comment: Comment) => {
                const doYouLike = comment.likes?.find((profile) => profile.id == authProfileId) != null;
                const canEdit = comment.profileId == authProfileId;
                const profileLink = comment.profile ? ROUTE_PROFILE_EDIT(comment.profile.nickname) : '#';
                const likesCount = comment.likes?.length;
                return {
                    ...comment,
                    likesCount: likesCount,
                    doYouLike: doYouLike,
                    canEdit: canEdit,
                    profileLink: profileLink,
                } as CommentPreview;
            })
            .slice(0, 2);

        return {
            ...post,
            doYouLike: doYouLike,
            canEdit: canEdit,
            commentPreviews: commentPreviews,
            profileLink: profileLink,
        } as PostPreview;
    });
}
