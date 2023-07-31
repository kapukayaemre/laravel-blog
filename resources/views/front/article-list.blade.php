@extends("layouts.front")
@section("title")
@endsection

@section("css")
@endsection

@section("content")
    <section class="articles row">

        <div class="popular-title col-md-12">
            <h2 class="font-montserrat fw-semibold">Latest Articles</h2>
        </div>

        {{--@foreach($category->articlesActive as $item)
            <div class="col-md-4 mt-4">
                <a href="#">
                    <img src="{{ asset($item->image) }}" class="img-fluid">
                </a>
                <div class="most-popular-body mt-2">
                    <div class="most-popular-author d-flex justify-content-between">
                        <div>
                            Author: <a href="#">{{ $item->user->name }}</a>
                        </div>
                        <div class="text-end">
                            Category: <a href="#">{{ $item->category->name }}</a>
                        </div>
                    </div>
                    <div class="most-popular-title">
                        <h4 class="text-black">
                            <a href="#">
                                {{ substr($item->title, 0, 20) }}
                            </a>
                        </h4>
                    </div>
                    <div class="most-popular-date">
                        <span>{{ \Carbon\Carbon::parse($item->publish_date)->format("d-m-Y") }}</span> &#x25CF; <span>10 dk</span>
                    </div>
                </div>
            </div>
        @endforeach--}}

        @foreach($articles as $item)
            <div class="col-md-4 mt-4">
                <a href="#">
                    <img src="{{ asset($item->image) }}" class="img-fluid">
                </a>
                <div class="most-popular-body mt-2">
                    <div class="most-popular-author d-flex justify-content-between">
                        <div>
                            Author: <a href="#">{{ $item->user->name }}</a>
                        </div>
                        <div class="text-end">
                            Category: <a href="#">{{ $item->category->name }}</a>
                        </div>
                    </div>
                    <div class="most-popular-title">
                        <h4 class="text-black">
                            <a href="#">
                                {{ substr($item->title, 0, 20) }}
                            </a>
                        </h4>
                    </div>
                    <div class="most-popular-date">
                        <span>{{ \Carbon\Carbon::parse($item->publish_date)->format("d-m-Y") }}</span> &#x25CF; <span>10 dk</span>
                    </div>
                </div>
            </div>
        @endforeach

        <hr style="border:1px solid #a9abad;" class="mt-5">
        <div class="col-8 mx-auto mt-2">
            {{--<nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link">Previous</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>--}}
            {{ $articles->links() }}
        </div>

    </section>
@endsection

@section("js")
@endsection
