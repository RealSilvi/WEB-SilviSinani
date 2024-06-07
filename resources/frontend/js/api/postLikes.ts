import axios, { AxiosInstance } from 'axios';
import { Post, Profile } from '../models';
import { ApiAction, Decimal } from '../utils';

/**
 * API_USERS_PROFILES_POSTS__LIKES_INDEX
 */
export async function indexPostLikes(
    userId: Decimal,
    profileId: Decimal,
    postId: Decimal,
    input?: IndexPostLikesInput,
    instance?: AxiosInstance,
) {
    const response = await (instance ?? axios).request<{ data: Profile[] }>({
        ...API_USERS_PROFILES_POSTS__LIKES_INDEX(userId, profileId, postId, input),
    });

    return response.data.data;
}

const API_USERS_PROFILES_POSTS__LIKES_INDEX = (
    userId: Decimal,
    profileId: Decimal,
    postId: Decimal,
    input?: IndexPostLikesInput,
): ApiAction => ({
    url: `/api/users/${userId}/profiles/${profileId}/posts/${postId}/likes`,
    method: 'GET',
    params: input,
});

export enum IndexPostLikesIncludeKey {
    User = 'user',
    News = 'news',
    AllNews = 'allNews',
    ReceivedRequests = 'receivedRequests',
    SentRequests = 'sentRequests',
    Followers = 'followers',
    Following = 'following',
    PendingFollowers = 'pendingFollowers',
    Comments = 'comments',
    PostLikes = 'postLikes',
    CommentLikes = 'commentLikes',
    LastPost = 'lastPost',
    Posts = 'posts',
}

export interface IndexPostLikesInput {
    include?: IndexPostLikesIncludeKey[];
}
/**
 * API_USERS_PROFILES_POSTS__LIKES_STORE
 */
export async function createPostLike(userId: Decimal, profileId: Decimal, postId: Decimal, instance?: AxiosInstance) {
    const response = await (instance ?? axios).request<{ data: Post }>({
        ...API_USERS_PROFILES_POSTS__LIKES_STORE(userId, profileId, postId),
    });

    return response.data.data;
}

const API_USERS_PROFILES_POSTS__LIKES_STORE = (userId: Decimal, profileId: Decimal, postId: Decimal): ApiAction => ({
    url: `/api/users/${userId}/profiles/${profileId}/posts/${postId}/likes`,
    method: 'POST',
});

/**
 * API_USERS_PROFILES_POSTS__LIKES_DESTROY
 */

export async function destroyPostLike(userId: Decimal, profileId: Decimal, postId: Decimal, instance?: AxiosInstance) {
    const response = await (instance ?? axios).request<{ data: Post }>({
        ...API_USERS_PROFILES_POSTS__LIKES_DESTROY(userId, profileId, postId),
    });

    return response.data.data;
}

const API_USERS_PROFILES_POSTS__LIKES_DESTROY = (userId: Decimal, profileId: Decimal, postId: Decimal): ApiAction => ({
    url: `/api/users/${userId}/profiles/${profileId}/posts/${postId}/likes`,
    method: 'DELETE',
});
