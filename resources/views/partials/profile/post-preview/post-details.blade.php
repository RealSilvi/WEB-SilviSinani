<div class="flex flex-col gap-3 lg:gap-5">
    <a :href="post.profileLink" class="flex flex-row gap-2 items-center lg:gap-5 lg:text-xl italic">
        <img
            alt="profile_image"
            :src="post.profile.mainImage"
            class="h-7 w-7 lg:h-14 lg:w-14 rounded-full object-cover" />
        <div x-text="`@${post.profile.nickname}`"></div>
    </a>

    <div
        x-text="post.description"
        class="line-clamp-2 lg:line-clamp-3 text-pretty text-sm lg:text-lg lg:px-0 ">
    </div>
    <template x-if="post.image">
        <img
            :src="post.image"
            alt="post_image"
            class="rounded-xl w-full object-cover aspect-[1/1] lg:aspect-[2/1] lg:object-contain bg-primary/10" />
    </template>
</div>
