@php
    /**
     * @var \App\Models\User $user
     * @var \App\Models\Profile $profile
     * @var \App\Models\Profile $authProfile
     */
    $user =  $user ?? auth()->user();
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
            <div x-data="commentLikes({
                            userId: {{$user->id}},
                            profileId: {{$authProfile->id}},
                            postId: post.id,
                            commentId: comment.id,
{{--                            doYouLike: post.doYouLike,--}}
{{--                            likesCount: post.likesCount--}}
                        })"
                 class="flex flex-row gap-1 lg:gap-3 items-center text-xs lg:text-base">
                <div
                    @click="doYouLike ? unlikeComment() : likeComment()"
                    :class="doYouLike ? 'text-black' : 'text-primary'"
                    class="cursor-pointer flex justify-end">
                    {{svg('like','h-5 w-5 lg:h-8 lg:w-8')}}
                </div>
                <div>
                    <span x-text="likesCount"></span>
                </div>
            </div>
        </div>
        <div x-show="comment.canEdit"
             @click="deleteComment(post.id,comment.id)"
             class="cursor-pointer text-primary">
            {{svg('delete','h-5 w-5 lg:h-8 lg:w-8')}}
        </div>
    </div>
    <div x-text="comment.body"
         class="line-clamp-2 text-sm lg:text-base lg:pl-14">
    </div>
</div>
