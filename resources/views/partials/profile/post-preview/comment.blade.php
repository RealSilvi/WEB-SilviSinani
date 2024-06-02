<div
    class="flex flex-col text-sm lg:text-base lg:gap-2 bg-primary/10 rounded-3xl px-4 lg:px-5 py-1 lg:py-3">
    <a :href="comment.profileLink" class="flex flex-row items-center lg:gap-3 font-medium">
        <img
            :src="comment.profile.mainImage"
            alt="profile_image"
            class="h-7 w-7 lg:h-10 lg:w-10 rounded-full object-cover hidden lg:block" />
        <div x-text="`@${comment.profile.nickname}`"></div>
    </a>

    <div class="flex justify-between gap-2 lg:gap-10 lg:pl-14">
        <div x-text="comment.body"
             class="line-clamp-2 text-sm lg:text-base">
        </div>

        <div class="flex flex-row gap-1 lg:gap-3 items-start text-xs lg:text-base lg:px-5">
            <div x-show="comment.canEdit"
                 @click="deleteComment(post.id,comment.id)"
                 class="cursor-pointer text-primary">
                {{svg('delete','h-5 w-5 lg:h-8 lg:w-8')}}
            </div>
            <div
                @click="comment.doYouLike ? deleteCommentLike(post.id,comment.id) : createCommentLike(post.id,comment.id)"
                :class="comment.doYouLike ? 'text-black' : 'text-primary'"
                class="cursor-pointer flex justify-end">
                {{svg('like','h-5 w-5 lg:h-8 lg:w-8')}}
            </div>
            <div class="h-full flex items-end">
                <span x-text="comment.likesCount ?? '0'"></span>
            </div>

        </div>
    </div>
</div>
