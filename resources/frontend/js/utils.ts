export function apiValidationErrors(response: Record<string, any>): Record<string, string> {
    if (!response || !response.errors) {
        return {};
    }

    const parsed: Record<string, string> = {};

    Object.keys(response.errors).forEach((key) => {
        parsed[key] = response.errors[key][0];
    });

    return parsed;
}

export function apiErrorMessage(response: Record<string, any>, fallback = ''): string {
    if (!response.data || !response.data.message) {
        return fallback;
    }

    return response.data.message;
}

export const STORAGE_PATH = (imagePath?: string) => `/storage/${imagePath}`;
export const STORAGE_PATH___BACKGROUND_IMAGE = () => '/storage/profiles/image-placeholder.png';
export const STORAGE_PATH___PLACEHOLDER_IMAGE = () => '/storage/utilities/image-placeholder.png';
export const STORAGE_PATH__PROFILE_PLACEHOLDER_IMAGE = () => '/storage/utilities/pet-placeholder.png';
