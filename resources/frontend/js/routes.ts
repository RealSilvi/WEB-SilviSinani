export const ROUTE_DASHBOARD = (authProfileId?: string) =>
    authProfileId ? `/dashboard/${authProfileId}` : `/dashboard`;
export const ROUTE_PROFILE_EDIT = (profileId: string, authProfile?: string) =>
    authProfile ? `/profiles/${profileId}?authProfile=${authProfile}` : `/profiles/${profileId}`;
export const ROUTE_POST_SHOW = (postId: string | number, authProfile: string) =>
    `/posts/${postId}?authProfile=${authProfile}`;
