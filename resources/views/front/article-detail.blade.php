@extends("layouts.front")
@section("title")
@endsection

@section("css")
@endsection

@section("content")
    <section class="row">
        <div class="col-12 bg-white rounded-1 shadow-sm">
            <div class="article-wrapper">
                <div class="article-header font-lato d-flex justify-content-between pb-4">
                    <div class="article-header-date">
                        @php
                            $publishDate = \Carbon\Carbon::parse($article->publish_date)->format("d-m-Y");
                        @endphp
                        <time datetime="{{ $publishDate }}">{{ $publishDate }}</time>
                        @foreach($article->getAttribute("tagsToArray") as $tag)
                            @php
                                $class = ["text-danger", "text-warning", "text-primary", "text-success"];
                                $randomClass = $class[random_int(0,3)];
                            @endphp
                            <span class="{{ $randomClass }}">{{ $tag }}</span>
                        @endforeach
                    </div>
                    <div class="article-header-author">
                        Author: <a href="#"><strong>{{ $article->user->name }}</strong></a>
                    </div>

                </div>
                <div class="article-content mt-4">
                    <h1 class="fw-bold mb-4">
                        {{ $article->title }}
                    </h1>
                    <div class="d-flex justify-content-center">
                        <img src="{{ asset("$article->image") }}" class="img-fluid w-75 rounded">
                    </div>
                    <div class="text-secondary mt-4">
                        {!! $article->body !!}
                    </div>
                </div>
            </div>

        </div>

        <section class="col-12 mt-4">
            <div class="article-items d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <a href="javascript:void(0)"
                       class="favorite-article me-1"
                       id="favoriteArticle"
                       data-id="{{ $article->id }}"
                       @if(!is_null($userLike))
                           style="color: red"
                       @endif
                    >
                        <span class="material-icons-outlined">favorite</span>
                    </a>
                    <span class="fw-light" id="favoriteCount">{{ $article->like_count }}</span>
                </div>
                <a href="javascript:void(0)" class="btn-response btnArticleResponse">Answer</a>

            </div>

            <div class="article-authors mt-5">
                <div class="bg-white p-4 d-flex justify-content-between align-items-center shadow-sm">
                    <img src="{{ asset($article->user->image) }}" alt="" width="75" height="75">
                    <div class="px-5 me-auto">
                        <h4 class=""><a href="mt-3">{{ $article->user->name }}</a></h4>
                        {!! $article->user->about !!}
                    </div>
                </div>
            </div>

            @if(isset($suggestArticles) && count($suggestArticles))
                <div class="mt-5">
                    <div class="swiper-most-popular mt-3">
                        <div class="swiper-wrapper">
                            @foreach($suggestArticles as $article)
                                @php
                                $image = $article->image;
                                $articlePublishDate = \Carbon\Carbon::parse($article->publish_date)->format("d-m-Y");
                                if (!file_exists(public_path($image)))
                                {
                                    $image = $settings->article_default_image;
                                }
                                @endphp
                                <div class="swiper-slide">
                                    <a href="{{ route("front.articleDetail", ["user" => $article->user, "article" => $article->slug]) }}">
                                        <img src="{{ asset($image) }}" class="img-fluid">
                                    </a>

                                    <div class="most-popular-body mt-2">
                                        <div class="most-popular-author d-flex justify-content-between">
                                            <div>
                                                Yazar: <a href="#">{{ $article->user->name }}</a>
                                            </div>
                                            <div class="text-end">
                                                Category:
                                                <a href="{{ route("front.category", ["category" => $article->category->slug]) }}">
                                                    {{ $article->category->name }}
                                                </a>
                                            </div>
                                        </div>
                                        <div class="most-popular-title">
                                            <h4 class="text-black">
                                                <a href="#">
                                                    {{ $article->title }}
                                                </a>
                                            </h4>
                                        </div>
                                        <div class="most-popular-date">
                                            <span>{{ $articlePublishDate }}</span> &#x25CF; <span>10 dk</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </section>

        <section class="article-responses mt-4">
            <div class="response-form bg-white shadow-sm rounded-1 p-4" style="display: none">
                <form action="{{ route("article.comment", $article->id) }}" method="POST">
                    <input type="hidden" name="parent_id" id="comment_parent_id" value="{{ null }}">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <h5>Your Answer</h5>
                            <hr>
                        </div>

                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="Your name.." name="name" required>
                        </div>
                        <div class="col-md-6">
                            <input type="email" class="form-control" placeholder="Email Address.." name="email" required>
                        </div>
                        <div class="col-12 mt-3">
                            <textarea name="comment" id="comment" cols="30" rows="5" class="form-control" placeholder="Your Comment.."></textarea>
                        </div>
                        <div class="col-md-4">
                            <button class="btn-response align-items-center d-flex mt-3">
                                <span class="material-icons-outlined me-2">send</span>
                                Gönder
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="response-body p-4">
                <h3>Article Comments</h3>
                <hr class="mb-4">

                @foreach($article->comments as $comment)
                    <div class="article-response-wrapper">
                        <div class="article-response bg-white p-2 mt-3 d-flex justify-content-between align-items-center shadow-sm">
                            <div class="col-md-2 ms-2">
                                @php
                                    if ($comment->user)
                                    {
                                        $image = $comment->user->image;
                                        $name = $comment->user->name;

                                        if (!file_exists(public_path($image)))
                                        {
                                            $image = $settings->default_comment_profile_image;
                                        }
                                    }
                                    else
                                    {
                                        $image = $settings->default_comment_profile_image;
                                        $name = $comment->name;
                                    }
                                @endphp

                                <img src="{{ asset($image) }}" alt="" width="75" height="75">
                            </div>
                            <div class="col-md-10">
                                <div class="px-3">
                                    <div class="comment-title-date d-flex justify-content-between">
                                        <h4 class="mt-3"><a href="">{{ $name }}</a></h4>
                                        <time datetime="{{ \Carbon\Carbon::parse($comment->created_at)->format("d-m-Y") }}">
                                            {{ \Carbon\Carbon::parse($comment->created_at)->format("d-m-Y") }}
                                        </time>
                                    </div>
                                    <p class="text-secondary">{{ $comment->comment }}</p>
                                    <div class="text-end d-flex  align-items-center justify-content-between">
                                        <div>
                                            <a href="javascript:void(0)" class="btn-response btnArticleResponseComment" data-id="{{ $comment->id }}">Answer</a>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            @php
                                                $commentLike = $comment->commentLikes->where("user_id", auth()->id())->where("comment_id", $comment->id)->first();
                                            @endphp
                                            <a href="javascript:void(0)"
                                               class="like-comment"
                                               data-id="{{ $comment->id }}"
                                               @if($commentLike)
                                                   style="color: red"
                                               @endif
                                            >
                                                <span class="material-icons">thumb_up</span>
                                            </a>
                                            <span id="commentLikeCount-{{ $comment->id }}">{{ $comment->like_count }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($comment->children)
                            <div class="articles-response-comment-wrapper">
                                @foreach($comment->children as $child)
                                    <div class="article-comment bg-white p-2 mt-3 d-flex justify-content-between align-items-center shadow-sm">
                                        <div class="col-md-2 ms-2">
                                            @php
                                                if ($child->user)
                                                {
                                                    $childImage = $child->user->image;
                                                    $childName = $child->user->name;

                                                    if (!file_exists(public_path($childImage)))
                                                    {
                                                        $childImage = $settings->default_comment_profile_image;
                                                    }
                                                }
                                                else
                                                {
                                                    $childImage = $settings->default_comment_profile_image;
                                                    $childName = $child->name;
                                                }
                                            @endphp
                                            <img src="{{ asset($childImage) }}" alt="" width="75" height="75">
                                        </div>
                                        <div class="col-md-10">
                                            <div class="px-3">
                                                <div class="comment-title-date d-flex justify-content-between">
                                                    <h4 class="mt-3"><a href="">{{ $childName }}</a></h4>
                                                    <time datetime="{{ \Carbon\Carbon::parse($child->created_at)->format("d-m-Y") }}">
                                                        {{ \Carbon\Carbon::parse($child->created_at)->format("d-m-Y") }}
                                                    </time>
                                                </div>
                                                <p class="text-secondary">{{ $child->comment }}</p>
                                                <div class="text-end d-flex  align-items-center justify-content-between">
                                                    <div class="d-flex align-items-center">
                                                        @php
                                                            $commentLikeChild = $child->commentLikes->where("user_id", auth()->id())->where("comment_id", $child->id)->first();
                                                        @endphp
                                                        <a href="javascript:void(0)"
                                                           class="like-comment"
                                                           data-id="{{ $child->id }}"
                                                           @if($commentLikeChild)
                                                               style="color: red"
                                                            @endif
                                                        >
                                                            <span class="material-icons">thumb_up</span>
                                                        </a>
                                                        <span id="commentLikeCount-{{ $child->id }}">{{ $child->like_count }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>
    </section>
@endsection

@section("js")
    <script>
        $(document).ready(function () {
            $("#favoriteArticle").click(function ()
            {
                @if(Auth::check())
                    let articleID = $(this).data("id");
                    let self = $(this);

                    $.ajax({
                        method: "POST",
                        url: "{{ route("article.favorite") }}",
                        data: {
                            articleID : articleID
                        },
                        async: false,
                        success: function (data){
                            if(data.process)
                            {
                                self.css("color", "red")
                            }
                            else
                            {
                                self.css("color", "inherit")
                            }

                            $("#favoriteCount").text(data.like_count)
                        },
                        error: function (){
                            console.log("error comes");
                        }
                    })
                @else
                    Swal.fire({
                        icon: "info",
                        title: "Info",
                        text: "You need login to for add favorite",
                        confirmButtonText: "Okay"
                    })
                @endif

            })

            $(".like-comment").click(function ()
            {
                @if(Auth::check())
                    let commentID = $(this).data("id");
                    let self = $(this);

                    $.ajax({
                        method: "POST",
                        url: "{{ route("comment.favorite") }}",
                        data: {
                            id : commentID
                        },
                        async: false,
                        success: function (data){
                            if(data.process)
                            {
                                self.css("color", "red")
                            }
                            else
                            {
                                self.css("color", "inherit")
                            }

                            $("#commentLikeCount-" + commentID).text(data.like_count)
                        },
                        error: function (){
                            console.log("error comes");
                        }
                    })
                @else
                    Swal.fire({
                        icon: "info",
                        title: "Info",
                        text: "You need login for like comments",
                        confirmButtonText: "Okay"
                    })
                @endif

            })
        })
    </script>
@endsection
