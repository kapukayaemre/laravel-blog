@extends("layouts.front")
@section("title")
@endsection

@section("css")
@endsection

@section("container-top")
    @if(isset($settings) && $settings->feature_categories_is_active)
        <section class="feature-categories mt-4">
        <div class="row">
            {{--<div class="col-md-3 p-2"
                 data-aos="fade-down-right"
                 data-aos-duration="1000"
                 data-aos-easing="ease-in-out">
                <div
                    style="
                             background: url('https://via.placeholder.com/600x400') no-repeat center center;
                             background-size: cover;
                             height: 300px"
                    class="p-4 position-relative">
                    <h2 class="text-center text-secondary">Lorem ipsum.</h2>
                    <p class="" style="text-align: justify">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab, necessitatibus?
                    </p>

                    <p class="position-absolute" style="bottom: 10px; left: 10px; right: 10px; ">
                        Lorem ipsum dolor sit amet.
                    </p>
                </div>
            </div>

            <div class="col-md-3 p-2"
                 data-aos="fade-down-right"
                 data-aos-duration="1000"
                 data-aos-easing="ease-in-out">
                <div
                    style="
                             background: url('https://via.placeholder.com/600x400') no-repeat center center;
                             background-size: cover;
                             height: 300px"
                    class="p-4 position-relative">
                    <h2 class="text-center text-secondary">Lorem ipsum.</h2>
                    <p class="" style="text-align: justify">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab, necessitatibus?
                    </p>

                    <p class="position-absolute" style="bottom: 10px; left: 10px; right: 10px; ">
                        Lorem ipsum dolor sit amet.
                    </p>
                </div>
            </div>

            <div class="col-md-3 p-2"
                 data-aos="fade-down-left"
                 data-aos-duration="1000"
                 data-aos-easing="ease-in-out">
                <div
                    style="
                             background: url('https://via.placeholder.com/600x400') no-repeat center center;
                             background-size: cover;
                             height: 300px"
                    class="p-4 position-relative">
                    <h2 class="text-center text-secondary">Lorem ipsum.</h2>
                    <p class="" style="text-align: justify">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab, necessitatibus?
                    </p>

                    <p class="position-absolute" style="bottom: 10px; left: 10px; right: 10px; ">
                        Lorem ipsum dolor sit amet.
                    </p>
                </div>
            </div>

            <div class="col-md-3 p-2"
                 data-aos="fade-down-left"
                 data-aos-duration="1000"
                 data-aos-easing="ease-in-out">
                <div
                    style="
                             background: url('https://via.placeholder.com/600x400') no-repeat center center;
                             background-size: cover;
                             height: 300px"
                    class="p-4 position-relative">
                    <h2 class="text-center text-secondary">Lorem ipsum.</h2>
                    <p class="" style="text-align: justify">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab, necessitatibus?
                    </p>

                    <p class="position-absolute" style="bottom: 10px; left: 10px; right: 10px; ">
                        Lorem ipsum dolor sit amet.
                    </p>
                </div>
            </div>--}}
        </div>
    </section>
    @endif
@endsection

@section("content")
    <section class="most-popular row"
             data-aos="zoom-in-up"
             data-aos-duration="2000"
             data-aos-easing="ease-in-out">
        <div class="popular-title col-md-8">
            <h2 class="font-montserrat fw-semibold">Most Popular Articles</h2>
        </div>
        <div class="col-4">
            <div class="most-popular-swiper-navigation text-end">
                <span class="btn btn-secondary material-icons most-popular-swiper-button-prev">arrow_back</span>
                <span class="btn btn-secondary material-icons most-popular-swiper-button-next">arrow_forward</span>
            </div>
        </div>
        <div class="col-12">
            <div class="swiper-most-popular mt-3">
                <!-- Additional required wrapper -->
                <div class="swiper-wrapper">
                    <!-- Slides -->
                    @foreach($mostPopularArticles as $article)
                        <div class="swiper-slide">
                            <a href="{{ route("front.articleDetail", ["user" => $article->user->username, "article" => $article->slug]) }}">
                                <img src="{{ imageExist($article->image, $settings->article_default_image) }}" class="img-fluid">
                            </a>

                            <div class="most-popular-body mt-2">
                                <div class="most-popular-author d-flex justify-content-between">
                                    <div>
                                        Author:
                                        <a href="{{ route("front.authorArticles", ["user" => $article->user->username]) }}">
                                            {{ $article->user->name }}
                                        </a>
                                    </div>

                                    <div class="text-end">
                                        Category:
                                        <a href="{{ route("front.categoryArticles", ["category" => $article->category->slug]) }}">
                                            {{ $article->category->name }}
                                        </a>
                                    </div>
                                </div>
                                <div class="most-popular-title">
                                    <h4 class="text-black">
                                        <a href="{{ route("front.articleDetail", ["user" => $article->user->username, "article" => $article->slug]) }}">
                                            {{ $article->title }}
                                        </a>
                                    </h4>
                                </div>
                                <div class="most-popular-date">
                                    <span>{{ $article->getFormatPublishDateAttribute() }}</span> &#x25CF; <span>10 dk</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

    </section>

    <section class="telegram d-flex align-items-center mt-5 p-4 rounded-2 text-white">
        <div class="me-4">
            <span class="material-icons text-black">send</span>
        </div>
        <div class="telegram-body">
            <h4 class="">Join the telegram group!</h4>
            <p class="">You can find more content in this group.</p>
            <a href="{{ isset($settings) ? $settings->telegram_link : "javascript:void(0)" }}" target="_blank" class="btn btn-warning p-3 text-black">Join</a>
        </div>

    </section>

    <section class="articles row mt-5"
             data-aos="flip-left"
             data-aos-duration="2000"
             data-aos-easing="ease-out-cubic">

        <div class="popular-title col-md-12">
            <h2 class="font-montserrat fw-semibold">Latest Articles</h2>
        </div>

        @foreach($lastPublishedArticles as $article)
            <div class="col-md-4 mt-4">
                <a href="{{ route("front.articleDetail", ["user" => $article->user->username, "article" => $article->slug]) }}">
                    <img src="{{ imageExist($article->image, $settings->article_default_image) }}" class="img-fluid">
                </a>
                <div class="most-popular-body mt-2">
                    <div class="most-popular-author d-flex justify-content-between">
                        <div>
                            Author:
                            <a href="{{ route("front.authorArticles", ["user" => $article->user->username]) }}">
                                {{ $article->user->name }}
                            </a>
                        </div>

                        <div class="text-end">
                            Category:
                            <a href="{{ route("front.categoryArticles", ["category" => $article->category->slug]) }}">
                                {{ $article->category->name }}
                            </a>
                        </div>
                    </div>

                    <div class="most-popular-title">
                        <h4 class="text-black">
                            <a href="{{ route("front.articleDetail", ["user" => $article->user->username, "article" => $article->slug]) }}">
                                {{ $article->title }}
                            </a>
                        </h4>
                    </div>
                    <div class="most-popular-date">
                        <span>{{ $article->getFormatPublishDateAttribute() }}</span> &#x25CF; <span>10 dk</span>
                    </div>
                </div>
            </div>
        @endforeach
    </section>
@endsection

@section("js")
@endsection
