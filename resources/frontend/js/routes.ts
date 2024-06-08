export const ROUTE_DASHBOARD = () => `/`;
export const ROUTE_PROFILE_EDIT = (profile: string, authProfile?:string) =>
    authProfile ? `/profiles/${profile}?auth=${authProfile}`:`/profiles/${profile}`;
export const ROUTE_PROFILE_NEW = () => `/profiles/new`;
