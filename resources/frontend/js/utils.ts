import { AxiosRequestConfig } from 'axios';
import { Comment, CommentPreview, LikePreview, Post, PostPreview, Profile } from './models';
import { ROUTE_POST_SHOW, ROUTE_PROFILE_EDIT } from './routes';

export type ApiAction = Pick<AxiosRequestConfig, 'url' | 'method' | 'params' | 'data' | 'headers'>;
export type Decimal = string | number;

export function postsToPostPreviews(posts: Post[], authProfileId: Decimal, authProfileNickname: string): PostPreview[] {
    const location = getCurrentLocale();
    return posts.map((post: Post) => {
        const doYouLike = post.likes?.find((profile) => profile.id == authProfileId) != null;
        const canEdit = post.profileId == authProfileId;
        const profileLink = post.profile
            ? location + ROUTE_PROFILE_EDIT(post.profile.nickname, authProfileNickname)
            : '#';
        const postLink = location + ROUTE_POST_SHOW(post.id, authProfileNickname);

        const commentPreviews =
            post.comments == null
                ? ([] as CommentPreview[])
                : post.comments.map((comment: Comment) => {
                      const doYouLike = comment.likes?.find((profile) => profile.id == authProfileId) != null;
                      const canEdit = comment.profileId == authProfileId;
                      const profileLink = comment.profile
                          ? location + ROUTE_PROFILE_EDIT(comment.profile.nickname, authProfileNickname)
                          : '#';
                      const likesCount = comment.likes?.length;
                      const likePreviews =
                          comment.likes == null
                              ? ([] as LikePreview[])
                              : comment.likes.map((profile: Profile) => {
                                    const profileLink =
                                        location + ROUTE_PROFILE_EDIT(profile.nickname, authProfileNickname);
                                    return {
                                        ...profile,
                                        profileLink: profileLink,
                                    } as LikePreview;
                                });

                      return {
                          ...comment,
                          likesCount: likesCount,
                          doYouLike: doYouLike,
                          canEdit: canEdit,
                          profileLink: profileLink,
                          likePreviews: likePreviews,
                      } as CommentPreview;
                  });

        const likePreviews =
            post.likes == null
                ? ([] as LikePreview[])
                : post.likes.map((profile: Profile) => {
                      const profileLink = location + ROUTE_PROFILE_EDIT(profile.nickname, authProfileNickname);
                      return {
                          ...profile,
                          profileLink: profileLink,
                      } as LikePreview;
                  });

        return {
            ...post,
            doYouLike: doYouLike,
            canEdit: canEdit,
            commentPreviews: commentPreviews,
            likePreviews: likePreviews,
            profileLink: profileLink,
            postLink: postLink,
        } as PostPreview;
    });
}

export function getCurrentLocale(): string {
    const defaultLanguage = 'en';
    const languages = ['it', 'en'];
    const path = window.location.pathname;
    const lang = path.split('/', 2).pop();

    return '/' + (languages.find((l) => l === lang) ?? defaultLanguage);
}
