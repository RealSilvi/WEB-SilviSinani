@php
    /**
     * @var \App\Models\User $user
     * @var \App\Models\Profile $profile
     * @var \App\Models\Profile $authProfile
     */

    $user = $user ?? auth()->user();
    $authProfile = $authProfile ?? $user->getDefaultProfile();
    $profile = $profile ?? $authProfile;
@endphp

<div class="flex flex-col gap-10"
     x-data="profilePostsContext({
         userId: {{$user->id}},
         profileId: {{$profile->id}},
         authProfileId: {{$authProfile->id}}
     })"
     @create-post.window="onCreatePost($event)"
     @destroy-post.window="onDestroyPost($event)"
     @post-liked.window="onPostLiked($event)"
     @post-liked-removed.window="onPostLikedRemoved($event)"
     @create-comment.window="onCreateComment($event)"
     @destroy-comment.window="onDestroyComment($event)"
     @comment-liked.window="onCommentLiked($event)"
     @comment-liked-removed.window="onCommentLikedRemoved($event)">

    <template x-for="post in posts">
        <div class="w-full">
            <div class="flex flex-col gap-3 lg:gap-5 p-5 lg:px-20 lg:py-10 bg-primary/10 rounded-xl">
                {{--Divided in partials for clenner code.--}}

                @include('partials.profile.post-preview.post-details', [
                    'user' => $user,
                    'authProfile' => $authProfile,
                    'profile' => $profile,
                ])

                @include('partials.profile.post-preview.interact', [
                    'user' => $user,
                    'authProfile' => $authProfile,
                    'profile' => $profile,
                ])

                <div class="flex flex-col gap-2">
                    <template x-for="comment in post.commentPreviews">
                        @include('partials.profile.post-preview.comment', [
                            'user' => $user,
                            'authProfile' => $authProfile,
                            'profile' => $profile,
                        ])
                    </template>
                </div>
            </div>
        </div>
    </template>

    <template x-if="posts.length == 0">
        <div class="mt-10 lg:mt-20 w-full h-full flex items-center justify-center text-center text-2xl font-medium ">
            <div class="flex flex-col lg:flex-row items-center justify-center gap-5">
                <span>
                    Non sono stati trovati post
                </span>
                <a href="{{route('dashboard',['profile'=>$authProfile->nickname])}}">
                    {{svg('other-logo','h-8 w-8 lg:h-10 lg:w-10')}}
                </a>
            </div>
        </div>
    </template>
</div>
