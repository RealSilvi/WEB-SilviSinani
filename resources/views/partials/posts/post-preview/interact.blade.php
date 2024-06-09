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
    x-data="{showInput:false}"
    class="flex flex-col">
    <div class="flex flex-row gap-2 lg:gap-3 items-center text-xs lg:text-base lg:px-5">
        <div x-data="postLike({
            userId: {{$user->id}},
            profileId: {{$authProfile->id}},
        })"
             class="flex flex-row gap-2 lg:gap-3 items-center text-xs lg:text-base">
            <div @click="post.doYouLike ?
            unlikePost( post.id, '{{__('messages.unlike_post.on_success')}}', '{{__('messages.unlike_post.on_fail')}}') :
            likePost( post.id, '{{__('messages.like_post.on_success')}}', '{{__('messages.like_post.on_fail')}}' );"
                 :class="post.doYouLike ? 'text-black' : 'text-primary'"
                 class="cursor-pointer rounded-full">
                {{svg('like','h-5 w-5 lg:h-8 lg:w-8')}}
            </div>

            <div x-data="{showLikeList:false}"
                 x-init="$watch('showLikeList', () =>  document.body.style.overflow = showLikeList ? 'hidden' : '')">
                <button @click="showLikeList = true" x-text="post.likesCount"></button>

                <div x-show="showLikeList"
                     class="fixed h-screen w-screen bg-black/50 z-50 inset-0 px-5 pb-16 pt-20 lg:pb-5 lg:pr-20">
                    <div
                        class="relative shrink-0 h-full w-full bg-black/50 rounded-xl inset-0 content-center py-10 px-5 lg:p-12">
                        <button class="absolute right-4 top-3 lg:right-10 lg:top-3" @click="showLikeList = false">
                            {{ svg('close','h-6 w-6 lg:h-7 lg:w-7 text-white') }}
                        </button>

                        <div class="w-full h-full overflow-auto pr-5 lg:pr-12">
                            <div class="flex flex-col lg:flex-row w-full justify-center lg:flex-wrap">
                                <template x-for="profile in post.likePreviews">
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

        <div></div>

        <div @click="showInput=!showInput"
             class="cursor-pointer">
            {{svg('comments','h-5 w-5 lg:h-8 lg:w-8 text-primary')}}
        </div>
        <a :href="post.postLink" class="px-1">
            <div x-text="post.commentsCount ?? '0'"></div>
        </a>
    </div>

    <div
        x-show="showInput"
        x-collapse
        x-data="comment({
                userId: {{$user->id}},
                profileId: {{$authProfile->id}},
        })"
    >
        <form
            @submit.prevent="createComment($event,post.id,
                 '{{__('messages.create_comment.on_success')}}',
                 '{{__('messages.create_comment.on_fail')}}')"
            @create-comment.window="showInput=false"
            class="relative overflow-hidden mt-5"
        >
            <x-form.group name="body" class="w-full">
                <x-form.label sr-only>
                    {{__('pages.profile.comment_placeholder')}}
                </x-form.label>
                <x-form.textarea required rows="1"
                                 placeholder="{{__('pages.profile.comment_placeholder')}}"
                                 class="placeholder-primary placeholder:font-light text-sm xl:text-lg !border py-2 pr-12 pl-2 rounded-xl flex-shrink" />
            </x-form.group>
            <x-form.submit class="absolute right-2 top-0 bg-transparent rounded-full !py-2">
                {{svg('send','h-5 w-5 lg:h-6 lg:w-6 text-primary')}}
            </x-form.submit>
        </form>
    </div>
</div>

