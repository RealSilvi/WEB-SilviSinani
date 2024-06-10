import axios, { AxiosInstance } from 'axios';
import { News } from '../models';
import { ApiAction, Decimal } from '../utils';

/**
 * API_USERS_PROFILES__NEWS_INDEX
 */
export async function indexNews(userId: Decimal, profileId: Decimal, input?: IndexNewsInput, instance?: AxiosInstance) {
    const response = await (instance ?? axios).request<{ data: News[] }>({
        ...API_USERS_PROFILES__NEWS_INDEX(userId, profileId, input),
    });
    return response.data.data;
}

const API_USERS_PROFILES__NEWS_INDEX = (userId: Decimal, profileId: Decimal, input?: IndexNewsInput): ApiAction => ({
    url: `/api/users/${userId}/profiles/${profileId}/news`,
    method: 'GET',
    params: input,
});

export enum IndexNewsIncludeKey {
    Profile = 'profile',
    From = 'from',
}

export interface IndexNewsInput {
    include?: IndexNewsIncludeKey[];
    page?: number;
}

/**
 * API_USERS_PROFILES__NEWS_STORE
 */
export async function createNews(
    userId: Decimal,
    profileId: Decimal,
    input: CreateNewsInput,
    instance?: AxiosInstance,
) {
    const response = await (instance ?? axios).request<{ data: News }>({
        ...API_USERS_PROFILES__NEWS_STORE(userId, profileId, input),
    });

    return response.data.data;
}

const API_USERS_PROFILES__NEWS_STORE = (userId: Decimal, profileId: Decimal, input: CreateNewsInput): ApiAction => ({
    url: `/api/users/${userId}/profiles/${profileId}/news`,
    method: 'POST',
    data: input,
});

export interface CreateNewsInput {
    type: NewsType;
    profileId: Decimal;
    title?: string;
    body?: string;
}

export enum NewsType {
    FollowRequest = 'Follow request',
    PostLike = 'Post like',
    CommentLike = 'Comment like',
    Comment = 'Comment',
}

/**
 * API_USERS_PROFILES__NEWS_SEE_ALL
 */
export async function seeAllNews(userId: Decimal, profileId: Decimal, instance?: AxiosInstance) {
    await (instance ?? axios).request<{ data: News }>({ ...API_USERS_PROFILES__NEWS_SEE_ALL(userId, profileId) });

    return true;
}

const API_USERS_PROFILES__NEWS_SEE_ALL = (userId: Decimal, profileId: Decimal): ApiAction => ({
    url: `/api/users/${userId}/profiles/${profileId}/news/seeAll`,
    method: 'POST',
});
