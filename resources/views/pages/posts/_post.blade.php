@php
    /**
     * @var \App\Models\User $user
     * @var \App\Models\Profile $profile
     * @var \App\Models\Profile $authProfile
     * @var \App\Models\Post $post
     */
    $user = $user ?? auth()->user();
    $authProfile = $authProfile ?? $user->getDefaultProfile();
    $profile = $profile ?? $authProfile;
@endphp

@extends('layouts.default',[
    'title' => __('pages.posts.title'),
])

@section('main')
    <header>
        @include('partials.navbar.navbar', [
            'user' => $user,
            'profile' => $profile,
            'authProfile' => $authProfile,
        ])
    </header>

    <aside>
        @include('partials.sidebar.sidebar', [
            'user' => $user,
            'profile' => $profile,
            'authProfile' => $authProfile,
        ])
    </aside>

    <main class="mx-auto w-full max-w-screen-2xl flex-1 pt-5 pb-10 lg:pt-20 lg:pb-32 px-5 lg:px-20">

        <section
            x-cloak
            x-data="postContext({
                 userId: {{$user->id}},
                 profileId: {{$profile->id}},
                 authProfileId: {{$authProfile->id}},
                 postId: {{$post->id}},
                 authProfileNickname: '{{$authProfile->nickname}}',
                 onCommentSuccessMessage:'{{__('messages.load_comments.on_success')}}',
                 onCommentFailMessage:'{{__('messages.load_comments.on_fail')}}'
            })"
            @destroy-post.window="onDestroyPost($event)"
            @post-liked.window="onPostLiked($event)"
            @post-liked-removed.window="onPostLikedRemoved($event)"
            @create-comment.window="onCreateComment($event)"
            @destroy-comment.window="onDestroyComment($event)"
            @comment-liked.window="onCommentLiked($event)"
            @comment-liked-removed.window="onCommentLikedRemoved($event)">

            <div class="w-full">
                <div class="flex flex-col gap-3 lg:gap-5 p-5 lg:px-20 lg:py-10 bg-primary/10 rounded-xl">

                    @include('partials.posts.post-preview.post-details', [
                        'user' => $user,
                        'authProfile' => $authProfile,
                        'profile' => $profile,
                    ])

                    @include('partials.posts.post-preview.interact', [
                        'user' => $user,
                        'authProfile' => $authProfile,
                        'profile' => $profile,
                    ])

                    <div class="flex flex-col gap-2">
                        <template x-for="comment in post.commentPreviews">
                            @include('partials.posts.post-preview.comment', [
                                'user' => $user,
                                'authProfile' => $authProfile,
                                'profile' => $profile,
                            ])
                        </template>
                    </div>
                </div>
            </div>

            <section x-show="!lastCommentPage" x-intersect="loadMoreComments()" class="text-transparent">
                {{__('pages.profile.loading')}}
            </section>
        </section>


    </main>

@endsection
