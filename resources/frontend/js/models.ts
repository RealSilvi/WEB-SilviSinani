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
}
