export const ROUTE_DASHBOARD = (authProfileId?: string) =>
    authProfileId ? `/dashboard/${authProfileId}` : `/dashboard`;
export const ROUTE_PROFILE_EDIT = (profileId: string, authProfileId?: string) =>
    authProfileId ? `/profiles/${profileId}?authProfile=${authProfileId}` : `/profiles/${profileId}`;
export const ROUTE_POST_SHOW = (postId: string | number, authProfile: string) =>
    `/posts/${postId}?authProfile=${authProfile}`;
