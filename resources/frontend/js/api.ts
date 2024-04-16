import type { AxiosRequestConfig } from 'axios';

type ApiAction = Required<Pick<AxiosRequestConfig, 'url' | 'method'>>;

export const API_USERS__PROFILES_INDEX = (userId: number): ApiAction => ({
    url: `/api/users/${userId}/profiles`,
    method: 'GET',
});

export const API_USERS__PROFILES_SHOW = (userId: number, profileId: Number): ApiAction => ({
    url: `/api/users/${userId}/profiles/${profileId}`,
    method: 'GET',
});

export const API_USERS__PROFILES_STORE = (userId: number): ApiAction => ({
    url: `/api/users/${userId}/profiles`,
    method: 'POST',
});

export const API_USERS__PROFILES_UPDATE = (userId: number, profileId: Number): ApiAction => ({
    url: `/api/users/${userId}/profiles/${profileId}`,
    method: 'PATCH',
});

export const API_USERS__PROFILES_DESTROY = (userId: number, profileId: Number): ApiAction => ({
    url: `/api/users/${userId}/profiles/${profileId}`,
    method: 'DELETE',
});
