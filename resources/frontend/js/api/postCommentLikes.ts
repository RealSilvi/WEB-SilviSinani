import axios, { AxiosInstance } from 'axios';
import { Comment, Post, Profile } from '../models';
import { ApiAction, Decimal } from '../utils';

/**
 * API_USERS_PROFILES_POSTS_COMMENTS__LIKES_INDEX
 */
export async function indexCommentLikes(
    userId: Decimal,
    profileId: Decimal,
    postId: Decimal,
    commentId: Decimal,
    input?: IndexCommentLikesInput,
    instance?: AxiosInstance,
) {
    const response = await (instance ?? axios).request<{ data: Profile[] }>({
        ...API_USERS_PROFILES_POSTS_COMMENTS__LIKES_INDEX(userId, profileId, postId, commentId, input),
    });

    return response.data.data;
}

const API_USERS_PROFILES_POSTS_COMMENTS__LIKES_INDEX = (
    userId: Decimal,
    profileId: Decimal,
    postId: Decimal,
    commentId: Decimal,
    input?: IndexCommentLikesInput,
): ApiAction => ({
    url: `api/users/${userId}/profiles/${profileId}/posts/${postId}/comments/${commentId}/likes `,
    method: 'GET',
    data: null,
});

export enum IndexCommentLikesIncludeKey {
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

export interface IndexCommentLikesInput {
    include?: IndexCommentLikesIncludeKey[];
}

/**
 * API_USERS_PROFILES_POSTS_COMMENTS__LIKES_STORE
 */
export async function createCommentLike(
    userId: Decimal,
    profileId: Decimal,
    postId: Decimal,
    commentId: Decimal,
    instance?: AxiosInstance,
) {
    const response = await (instance ?? axios).request<{ data: Comment }>({
        ...API_USERS_PROFILES_POSTS_COMMENTS__LIKES_STORE(userId, profileId, postId, commentId),
    });

    return response.data.data;
}

const API_USERS_PROFILES_POSTS_COMMENTS__LIKES_STORE = (
    userId: Decimal,
    profileId: Decimal,
    postId: Decimal,
    commentId: Decimal,
): ApiAction => ({
    url: `api/users/${userId}/profiles/${profileId}/posts/${postId}/comments/${commentId}/likes `,
    method: 'POST',
    data: null,
});

/**
 * API_USERS_PROFILES_POSTS_COMMENTS__LIKES_DESTROY
 */
export async function destroyCommentLike(
    userId: Decimal,
    profileId: Decimal,
    postId: Decimal,
    commentId: Decimal,
    instance?: AxiosInstance,
) {
    const response = await (instance ?? axios).request<{ data: Comment }>({
        ...API_USERS_PROFILES_POSTS_COMMENTS__LIKES_DESTROY(userId, profileId, postId, commentId),
    });

    return response.data.data;
}

const API_USERS_PROFILES_POSTS_COMMENTS__LIKES_DESTROY = (
    userId: Decimal,
    profileId: Decimal,
    postId: Decimal,
    commentId: Decimal,
): ApiAction => ({
    url: `api/users/${userId}/profiles/${profileId}/posts/${postId}/comments/${commentId}/likes`,
    method: 'DELETE',
    data: null,
});
