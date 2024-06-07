import axios, { AxiosInstance } from 'axios';
import { Comment, Post } from '../models';
import { ApiAction, Decimal } from '../utils';

/**
 * API_USERS_PROFILES_POSTS__COMMENTS_INDEX
 */
export async function indexComment(
    userId: Decimal,
    profileId: Decimal,
    postId: Decimal,
    input?: IndexCommentInput,
    instance?: AxiosInstance,
) {
    const response = await (instance ?? axios).request<{ data: Comment[] }>({
        ...API_USERS_PROFILES_POSTS__COMMENTS_INDEX(userId, profileId, postId, input),
    });

    return response.data.data;
}

const API_USERS_PROFILES_POSTS__COMMENTS_INDEX = (
    userId: Decimal,
    profileId: Decimal,
    postId: Decimal,
    input?: IndexCommentInput,
): ApiAction => ({
    url: `/api/users/${userId}/profiles/${profileId}/posts/${postId}/comments`,
    method: 'GET',
    params: input,
});

export enum IndexCommentIncludeKey {
    Profile = 'profile',
    Post = 'post',
    Likes = 'likes',
    LikesCount = 'likesCount',
}

export interface IndexCommentInput {
    include?: IndexCommentIncludeKey[];
}

/**
 * API_USERS_PROFILES_POSTS__COMMENTS_SHOW
 */
export async function showComment(
    userId: Decimal,
    profileId: Decimal,
    postId: Decimal,
    commentId: Decimal,
    input?: ShowCommentInput,
    instance?: AxiosInstance,
) {
    const response = await (instance ?? axios).request<{ data: Comment }>({
        ...API_USERS_PROFILES_POSTS__COMMENTS_SHOW(userId, profileId, postId, commentId, input),
    });

    return response.data.data;
}
const API_USERS_PROFILES_POSTS__COMMENTS_SHOW = (
    userId: Decimal,
    profileId: Decimal,
    postId: Decimal,
    commentId: Decimal,
    input?: ShowCommentInput,
): ApiAction => ({
    url: `/api/users/${userId}/profiles/${profileId}/posts/${postId}/comments/${commentId}`,
    method: 'GET',
    params: input,
});

export enum ShowCommentIncludeKey {
    Profile = 'profile',
    Post = 'post',
    Likes = 'likes',
    LikesCount = 'likesCount',
}

export interface ShowCommentInput {
    include?: ShowCommentIncludeKey[];
}

/**
 * API_USERS_PROFILES_POSTS__COMMENTS_STORE
 */
export async function createComment(
    userId: Decimal,
    profileId: Decimal,
    postId: Decimal,
    input: CreateCommentInput,
    instance?: AxiosInstance,
) {
    const response = await (instance ?? axios).request<{ data: Comment }>({
        ...API_USERS_PROFILES_POSTS__COMMENTS_STORE(userId, profileId, postId, input),
    });

    return response.data.data;
}
const API_USERS_PROFILES_POSTS__COMMENTS_STORE = (
    userId: Decimal,
    profileId: Decimal,
    postId: Decimal,
    input: CreateCommentInput,
): ApiAction => ({
    url: `/api/users/${userId}/profiles/${profileId}/posts/${postId}/comments`,
    method: 'POST',
    data: input,
});

export interface CreateCommentInput {
    body?: string;
}

/**
 * API_USERS_PROFILES_POSTS__COMMENTS_DESTROY
 */
export async function destroyComment(
    userId: Decimal,
    profileId: Decimal,
    postId: Decimal,
    commentId: Decimal,
    instance?: AxiosInstance,
) {
    await (instance ?? axios).request(API_USERS_PROFILES_POSTS__COMMENTS_DESTROY(userId, profileId, postId, commentId));

    return true;
}

const API_USERS_PROFILES_POSTS__COMMENTS_DESTROY = (
    userId: Decimal,
    profileId: Decimal,
    postId: Decimal,
    commentId: Decimal,
): ApiAction => ({
    url: `/api/users/${userId}/profiles/${profileId}/posts/${postId}/comments/${commentId}`,
    method: 'DELETE',
});
