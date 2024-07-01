import axios, { AxiosInstance, AxiosRequestConfig } from 'axios';
import { Profile } from '../models';
import { ApiAction, Decimal } from '../utils';

/**
 * API_USERS_PROFILES__FOLLOWERS_INDEX
 */

export async function indexMyFollowers(
    userId: Decimal,
    profileId: Decimal,
    input?: IndexFollowersInput,
    instance?: AxiosInstance,
) {
    const response = await (instance ?? axios).request<{ data: Profile[] }>({
        ...API_USERS_PROFILES__FOLLOWERS_INDEX(userId, profileId, input),
    });

    return response.data.data;
}

const API_USERS_PROFILES__FOLLOWERS_INDEX = (
    userId: Decimal,
    profileId: Decimal,
    input?: IndexFollowersInput,
): ApiAction => ({
    url: `/api/users/${userId}/profiles/${profileId}/followers`,
    method: 'GET',
    params: input,
});

export enum IndexFollowersIncludeKey {
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

export interface IndexFollowersInput {
    include?: IndexFollowersIncludeKey[];
    page?: number;
}

/**
 * API_USERS_PROFILES__FOLLOWERS_STORE
 */

export async function acceptFollowRequest(
    userId: Decimal,
    profileId: Decimal,
    input: AcceptFollowRequestInput,
    instance?: AxiosInstance,
) {
    const response = await (instance ?? axios).request<{ data: Profile }>({
        ...API_USERS_PROFILES__FOLLOWERS_STORE(userId, profileId, input),
    });

    return response.data.data;
}

const API_USERS_PROFILES__FOLLOWERS_STORE = (
    userId: Decimal,
    profileId: Decimal,
    input: AcceptFollowRequestInput,
): ApiAction => ({
    url: `/api/users/${userId}/profiles/${profileId}/followers`,
    method: 'POST',
    data: input,
});

export interface AcceptFollowRequestInput {
    followerId: Decimal;
}

/**
 * API_USERS_PROFILES__FOLLOWERS_DESTROY
 */
export async function destroyFollowerOrFollowRequest(
    userId: Decimal,
    profileId: Decimal,
    followerId: Decimal,
    instance?: AxiosInstance,
) {
    const response = await (instance ?? axios).request<{ data: Profile }>({
        ...API_USERS_PROFILES__FOLLOWERS_DESTROY(userId, profileId, followerId),
    });

    return response.data.data;
}

const API_USERS_PROFILES__FOLLOWERS_DESTROY = (
    userId: Decimal,
    profileId: Decimal,
    followerId: Decimal,
): ApiAction => ({
    url: `/api/users/${userId}/profiles/${profileId}/followers/${followerId}`,
    method: 'DELETE',
});
