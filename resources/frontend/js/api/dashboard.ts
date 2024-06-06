import axios, { AxiosInstance } from 'axios';
import { Post } from '../models';
import { ApiAction, Decimal } from '../utils';

/**
 * API_USERS_PROFILES__DASHBOARD_SHOW
 */
export async function showDashboard(
    userId: Decimal,
    profileId: Decimal,
    input?: ShowDashboardInput,
    instance?: AxiosInstance,
) {
    const response = await (instance ?? axios).request<{ data: Post[] }>({
        ...API_USERS_PROFILES__DASHBOARD_SHOW(userId, profileId, input),
    });

    return response.data.data;
}

const API_USERS_PROFILES__DASHBOARD_SHOW = (
    userId: Decimal,
    profileId: Decimal,
    input?: ShowDashboardInput,
): ApiAction => ({
    url: `/api/users/${userId}/profiles/${profileId}/dashboard/`,
    method: 'GET',
    params: input,
    data: null,
});

export enum ShowDashboardIncludeKey {
    Profile = 'profile',
    Comments = 'comments',
    CommentsProfile = 'comments.profile',
    CommentsLikes = 'comments.likes',
    CommentsCount = 'commentsCount',
    Likes = 'likes',
    LikesCount = 'likesCount',
}

export interface ShowDashboardInput {
    include?: ShowDashboardIncludeKey[];
    page?: number;
}
