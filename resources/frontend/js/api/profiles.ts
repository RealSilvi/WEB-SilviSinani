import axios, { AxiosInstance, AxiosRequestConfig } from 'axios';
import { Profile } from '../models';
import { ApiAction, Decimal } from '../utils';

/**
 * API_USERS__PROFILES_INDEX
 */

export async function indexProfile(userId: Decimal, input?: IndexProfileInput, instance?: AxiosInstance) {
    const response = await (instance ?? axios).request<{ data: Profile[] }>({
        ...API_USERS__PROFILES_INDEX(userId, input),
    });

    return response.data.data;
}
const API_USERS__PROFILES_INDEX = (userId: Decimal, input?: IndexProfileInput): ApiAction => ({
    url: `/api/users/${userId}/profiles`,
    method: 'GET',
    data: null,
    params: input,
});

export interface IndexProfileInput {
    include?: IndexProfileIncludeKey[];
}

export enum IndexProfileIncludeKey {
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

/**
 * API_USERS__PROFILES_SHOW
 */

export async function showProfile(
    userId: Decimal,
    profileId: Decimal,
    input?: ShowProfileInput,
    instance?: AxiosInstance,
) {
    const response = await (instance ?? axios).request<{ data: Profile }>({
        ...API_USERS__PROFILES_SHOW(userId, profileId, input),
    });

    return response.data.data;
}
const API_USERS__PROFILES_SHOW = (userId: Decimal, profileId: Decimal, input?: ShowProfileInput): ApiAction => ({
    url: `/api/users/${userId}/profiles/${profileId}`,
    method: 'GET',
    params: input,
});

export interface ShowProfileInput {
    include?: ShowProfileIncludeKey[];
}

export enum ShowProfileIncludeKey {
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

/**
 * API_USERS__PROFILES_STORE
 */

export async function createProfile(userId: Decimal, input: CreateProfileInput, instance?: AxiosInstance) {
    const response = await (instance ?? axios).request<{ data: Profile }>({
        ...API_USERS__PROFILES_STORE(userId, input),
    });

    return response.data.data;
}
const API_USERS__PROFILES_STORE = (userId: Decimal, input: CreateProfileInput): ApiAction => ({
    url: `/api/users/${userId}/profiles`,
    method: 'POST',
    data: input,
});

export interface CreateProfileInput {
    nickname: string;
    type: ProfileType;
    default?: boolean;
    dateOfBirth?: string;
    breed?: string;
    mainImage?: string;
    secondaryImage?: string;
    bio?: string;
}

export enum ProfileType {
    Dog = 'Dog',
    Cat = 'Cat',
    Bird = 'Bird',
    Horse = 'Horse',
    Reptile = 'Reptile',
}

/**
 * API_USERS__PROFILES_UPDATE
 */

export async function updateProfile(
    userId: Decimal,
    profileId: Decimal,
    input: UpdateProfileInput,
    instance?: AxiosInstance,
) {
    const response = await (instance ?? axios).request<{ data: Profile }>({
        ...API_USERS__PROFILES_UPDATE(userId, profileId, input),
    });

    return response.data.data;
}
const API_USERS__PROFILES_UPDATE = (userId: Decimal, profileId: Decimal, input: UpdateProfileInput): ApiAction => ({
    url: `/api/users/${userId}/profiles/${profileId}`,
    method: 'PATCH',
    data: input,
});

export interface UpdateProfileInput {
    nickname?: string;
    default?: boolean;
    dateOfBirth?: string;
    breed?: string;
    mainImage?: string;
    secondaryImage?: string;
    bio?: string;
}

/**
 * API_USERS__PROFILES_DESTROY
 */
export async function destroyProfile(userId: Decimal, profileId: Decimal, instance?: AxiosInstance) {
    await (instance ?? axios).request(API_USERS__PROFILES_DESTROY(userId, profileId));

    return true;
}

const API_USERS__PROFILES_DESTROY = (userId: Decimal, profileId: Decimal): ApiAction => ({
    url: `/api/users/${userId}/profiles/${profileId}`,
    method: 'DELETE',
});
