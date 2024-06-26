import axios, { AxiosInstance, AxiosRequestConfig } from 'axios';
import { Profile } from '../models';
import { ApiAction, Decimal } from '../utils';

/**
 * API_USERS_PROFILES__FOLLOWING_INDEX
 */

export async function indexWhoFollow(
    userId: Decimal,
    profileId: Decimal,
    input?: IndexFollowingInput,
    instance?: AxiosInstance,
) {
    const response = await (instance ?? axios).request<{ data: Profile[] }>({
        ...API_USERS_PROFILES__FOLLOWING_INDEX(userId, profileId, input),
    });

    return response.data.data;
}
const API_USERS_PROFILES__FOLLOWING_INDEX = (
    userId: Decimal,
    profileId: Decimal,
    input?: IndexFollowingInput,
): ApiAction => ({
    url: `/api/users/${userId}/profiles/${profileId}/following`,
    method: 'GET',
    params: input,
});

export enum IndexFollowingIncludeKey {
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

export interface IndexFollowingInput {
    include?: IndexFollowingIncludeKey[];
}

/**
 * API_USERS_PROFILES__FOLLOWING_STORE
 */

export async function sendFollowRequest(
    userId: Decimal,
    profileId: Decimal,
    input: sendFollowRequestInput,
    instance?: AxiosInstance,
) {
    const response = await (instance ?? axios).request<{ data: Profile }>({
        ...API_USERS_PROFILES__FOLLOWING_STORE(userId, profileId, input),
    });

    return response.data.data;
}

const API_USERS_PROFILES__FOLLOWING_STORE = (
    userId: Decimal,
    profileId: Decimal,
    input: sendFollowRequestInput,
): ApiAction => ({
    url: `/api/users/${userId}/profiles/${profileId}/following`,
    method: 'POST',
    data: input,
});

export interface sendFollowRequestInput {
    followerId: Decimal;
}

/**
 * API_USERS_PROFILES__FOLLOWING_DESTROY
 */
export async function destroyFollowingOrFollowingRequest(
    userId: Decimal,
    profileId: Decimal,
    followerId: Decimal,
    instance?: AxiosInstance,
) {
    const response = await (instance ?? axios).request<{ data: Profile }>({
        ...API_USERS_PROFILES__FOLLOWING_DESTROY(userId, profileId, followerId),
    });

    return response.data.data;
}
const API_USERS_PROFILES__FOLLOWING_DESTROY = (
    userId: Decimal,
    profileId: Decimal,
    followingId: Decimal,
): ApiAction => ({
    url: `/api/users/${userId}/profiles/${profileId}/following/${followingId}`,
    method: 'DELETE',
});
