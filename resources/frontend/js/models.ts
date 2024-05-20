export interface Profile {
    id: number;
    nickname: string;
    mainImage?: string;
    secondaryImage?: string;
    dateOfBirth?: string;
    default: boolean;
    userId: number;
    type: string;
    breed?: string;
    createdAt: string;
    updatedAt: string;
    user?: User;
    bio: string;
    receivedRequestsCount?: number;
    receivedRequests?: Profile[];
    sentRequestsCount: number;
    sentRequests?: Profile[];
    allNewsCount: number;
    allNews?: News[];
    newsCount: number;
    news?: News[];
    followersCount: number;
    followers?: Profile[];
    followingCount: number;
    following?: Profile[];
}

export interface News {
    id: number;
    title: string | null;
    body: string | null;
    type: string;
    seen: boolean;
    seenAt: string;
    createdAt: string;
    updatedAt: string;
    profileId: number;
    from: number;
    profile?: Profile;
}

export interface User {
    id: number;
    firstName: string;
    lastName: string;
    dateOfBirth: string;
    email: string;
    profiles?: Profile[];
    createdAt: string;
    updatedAt: string;
}

export interface ProfileLink {
    profileId?: number;
    src: string;
    alt: string;
    href: string;
    nickname: string;
}
