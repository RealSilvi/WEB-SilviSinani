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

<div
    class="flex flex-col text-sm lg:text-base gap-1 lg:gap-2 bg-primary/10 rounded-3xl px-5 lg:px-5 py-3 lg:py-4">
    <div class="flex justify-between gap-2 lg:gap-10">

        <div class="flex flex-row gap-1 lg:gap-3 items-center text-xs lg:text-base">
            <a :href="comment.profileLink" class="flex flex-row items-center lg:gap-3 font-medium">
                <img
                    :src="comment.profile.mainImage"
                    alt="profile_image"
                    class="h-10 w-10 rounded-full object-cover hidden lg:block" />
                <div class="pr-2" x-text="`@${comment.profile.nickname}`"></div>
            </a>
            <div x-data="commentLike({
                            userId: {{$user->id}},
                            profileId: {{$authProfile->id}},
                        })"
                 class="flex flex-row gap-1 lg:gap-3 items-center text-xs lg:text-base">
                <div
                    @click="comment.doYouLike ?
                    unlikeComment(post.id, comment.id, '{{__('messages.unlike_comment.on_success')}}', '{{__('messages.unlike_comment.on_fail')}}') :
                    likeComment(post.id, comment.id, '{{__('messages.like_comment.on_success')}}', '{{__('messages.like_comment.on_fail')}}' );"
                    :class="comment.doYouLike ? 'text-black' : 'text-primary'"
                    class="cursor-pointer flex justify-end">
                    {{svg('like','h-5 w-5 lg:h-8 lg:w-8')}}
                </div>

                <div x-data="{showLikeList:false}"
                     x-init="$watch('showLikeList', () =>  document.body.style.overflow = showLikeList ? 'hidden' : '')">
                    <button @click="showLikeList = true" x-text="comment.likesCount"></button>

                    <div x-show="showLikeList"
                         class="fixed h-screen w-screen bg-black/50 z-50 inset-0 px-5 pb-16 pt-20 lg:pb-5 lg:pr-20">
                        <div
                            class="relative shrink-0 h-full w-full bg-black/50 rounded-xl inset-0 content-center py-10 px-5 lg:p-12">
                            <button class="absolute right-4 top-3 lg:right-10 lg:top-3" @click="showLikeList = false">
                                {{ svg('close','h-6 w-6 lg:h-7 lg:w-7 text-white') }}
                            </button>

                            <div class="w-full h-full overflow-auto pr-5 lg:pr-12">
                                <div class="flex flex-col lg:flex-row w-full justify-center lg:flex-wrap">
                                    <template x-for="profile in comment.likePreviews">
                                        <div class="lg:w-1/3 lg:p-5">
                                            @include('partials.profile.profile-preview')
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div x-show="comment.canEdit"
             x-data="comment({
                 userId: {{$user->id}},
                 profileId: {{$authProfile->id}},
             })"
             @click="deleteComment(post.id,comment.id,
                 '{{__('messages.delete_comment.on_success')}}',
                 '{{__('messages.delete_comment.on_fail')}}')"
             class="cursor-pointer text-primary">
            {{svg('delete','h-5 w-5 lg:h-8 lg:w-8')}}
        </div>
    </div>
    <div x-text="comment.body"
         class="line-clamp-2 text-sm lg:text-base lg:pl-14">
    </div>
</div>
