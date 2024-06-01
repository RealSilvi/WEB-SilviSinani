import axios, { AxiosInstance } from 'axios';
import { Post } from '../models';
import { ApiAction, Decimal } from '../utils';

/**
 * API_USERS_PROFILES__POSTS_INDEX
 */
export async function indexPosts(
    userId: Decimal,
    profileId: Decimal,
    input?: IndexPostsInput,
    instance?: AxiosInstance,
) {
    const response = await (instance ?? axios).request<{ data: Post[] }>({
        ...API_USERS_PROFILES__POSTS_INDEX(userId, profileId, input),
    });
    return response.data.data;
}

const API_USERS_PROFILES__POSTS_INDEX = (userId: Decimal, profileId: Decimal, input?: IndexPostsInput): ApiAction => ({
    url: `/api/users/${userId}/profiles/${profileId}/posts`,
    method: 'GET',
    data: null,
    params: input,
});

export enum IndexPostsIncludeKey {
    Profile = 'profile',
    Comments = 'comments',
    CommentsProfile = 'comments.profile',
    CommentsCount = 'commentsCount',
    Likes = 'likes',
    LikesCount = 'likesCount',
}

export interface IndexPostsInput {
    include?: IndexPostsIncludeKey[];
}

/**
 * API_USERS_PROFILES__POSTS_SHOW
 */
export async function showPosts(
    userId: Decimal,
    profileId: Decimal,
    postId: Decimal,
    input?: ShowPostsInput,
    instance?: AxiosInstance,
) {
    const response = await (instance ?? axios).request<{ data: Post }>({
        ...API_USERS_PROFILES__POSTS_SHOW(userId, profileId, postId, input),
    });

    return response.data.data;
}

const API_USERS_PROFILES__POSTS_SHOW = (
    userId: Decimal,
    profileId: Decimal,
    postId: Decimal,
    input?: ShowPostsInput,
): ApiAction => ({
    url: `/api/users/${userId}/profiles/${profileId}/posts/${postId}`,
    method: 'GET',
    params: input,
    data: null,
});

export enum ShowPostsIncludeKey {
    Profile = 'profile',
    Comments = 'comments',
    Likes = 'likes',
}

export interface ShowPostsInput {
    include?: ShowPostsIncludeKey[];
}

/**
 * API_USERS_PROFILES__POSTS_STORE
 */
export async function createPost(
    userId: Decimal,
    profileId: Decimal,
    input: CreatePostInput,
    instance?: AxiosInstance,
) {
    const response = await (instance ?? axios).request<{ data: Post }>({
        ...API_USERS_PROFILES__POSTS_STORE(userId, profileId, input),
    });

    return response.data.data;
}

const API_USERS_PROFILES__POSTS_STORE = (userId: Decimal, profileId: Decimal, input: CreatePostInput): ApiAction => ({
    url: `/api/users/${userId}/profiles/${profileId}/posts`,
    method: 'POST',
    params: null,
    data: input,
});

export interface CreatePostInput {
    image?: string;
    description?: string;
}

/**
 * API_USERS_PROFILES__POSTS_DESTROY
 */

export async function destroyPost(userId: Decimal, profileId: Decimal, postId: Decimal, instance?: AxiosInstance) {
    await (instance ?? axios).request(API_USERS_PROFILES__POSTS_DESTROY(userId, profileId, postId));

    return true;
}

const API_USERS_PROFILES__POSTS_DESTROY = (userId: Decimal, profileId: Decimal, postId: Decimal): ApiAction => ({
    url: `/api/users/${userId}/profiles/${profileId}/posts/${postId}`,
    method: 'DELETE',
    data: null,
    params: null,
});
