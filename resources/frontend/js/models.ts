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
    sentRequestsCount?: number;
    sentRequests?: Profile[];
    allNewsCount?: number;
    allNews?: News[];
    newsCount?: number;
    news?: News[];
    followersCount?: number;
    followers?: Profile[];
    followingCount?: number;
    following?: Profile[];
}

export interface ProfilePreview extends Profile {
    profileLink: string;
    currentActive?: boolean;
}

export interface News {
    id: number;
    fromNickname: string;
    fromId: string;
    fromType: string;
    type: NewsType;
    seen: boolean;
    seenAt: string;
    createdAt: string;
    updatedAt: string;
    profileId: number;
    from?: Profile | Comment | Post;
    profile?: Profile;
}
export interface NewsPreview extends News {
    profileLink: string;
    postLink?: string;
    message?: string;
}
export enum NewsType {
    FollowRequest = 'Follow request',
    PostLike = 'Post like',
    CommentLike = 'Comment like',
    Comment = 'Comment',
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

export interface Post {
    id: number;
    image?: string;
    description?: string;
    profileId: number;
    profile?: Profile;
    createdAt: string;
    updatedAt: string;
    likesCount?: number;
    likes?: Profile[];
    commentsCount?: number;
    comments?: Comment[];
}

export interface PostPreview extends Post {
    doYouLike: boolean;
    canEdit: boolean;
    profileLink?: string;
    postLink?: string;
    commentPreviews: CommentPreview[];
    likePreviews: LikePreview[];
}

export interface Comment {
    id: number;
    body: string;
    postId: number;
    post?: Post;
    profileId: number;
    profile?: Profile;
    createdAt: string;
    updatedAt: string;
    likesCount?: number;
    likes?: Profile[];
}

export interface CommentPreview extends Comment {
    doYouLike: boolean;
    canEdit: boolean;
    profileLink?: string;
    likePreviews: LikePreview[];
}

export type Like = Profile;

export interface LikePreview extends Like {
    profileLink?: string;
}
