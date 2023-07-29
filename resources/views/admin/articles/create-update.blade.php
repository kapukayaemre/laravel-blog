@extends('layouts.admin')
@section('title')
    {{ isset($article) ? "Update Article" : "New Article" }}
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset("assets/admin/plugins/flatpickr/flatpickr.min.css") }}">
    <link rel="stylesheet" href="{{ asset("assets/admin/plugins/summernote/summernote-lite.min.css") }}">
@endsection

@section('content')
    <x-bootstrap.card>
        <x-slot:header>
            <h4 class="card-title">{{ isset($article) ? "Update Article" : "New Article" }}</h4>
        </x-slot:header>

        <x-slot:body>
            <div class="example-container">
                <div class="example-content">
                    @if($errors->any())
                        @foreach($errors->all() as $error)
                            <div class="alert alert-style-light alert-danger">{{ $error }}</div>
                        @endforeach
                    @endif
                    <form action="{{ isset($article) ? route('article.edit', $article->id) : route('article.create') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label for="title" class="form-label">Article Title</label>
                        <input
                            type="text"
                            class="form-control form-control-solid-bordered m-b-sm
                            @if($errors->has('title'))
                                border-danger
                            @endif"
                            placeholder="Article Title"
                            name="title"
                            id="title"
                            value="{{ isset($article) ? $article->title : "" }}"
                            required
                        >
                        {{--?
                        ? @if($errors->has("name"))
                        ?    {{ $errors->first("name") }}
                        ? @endif
                        ? --}}

                        <label for="slug" class="form-label">Article Slug</label>
                        <input
                            type="text"
                            class="form-control form-control-solid-bordered m-b-sm @if($errors->has('slug')) border-danger @endif"
                            placeholder="Article Slug"
                            name="slug"
                            id="slug"
                            value="{{ isset($article) ? $article->slug : "" }}"
                        >

                        <label for="summernote" class="form-label">Article Body</label>
                        <textarea name="body" id="summernote" class="m-b-sm">Hello Summernote</textarea>

                        <label for="tags" class="form-label m-t-sm">Article Tags</label>
                        <input
                            type="text"
                            class="form-control form-control-solid-bordered @if($errors->has('tags')) border-danger @endif"
                            placeholder="Article Tags"
                            name="tags"
                            id="tags"
                            value="{{ isset($article) ? $article->tags : "" }}"
                        >
                        <div class="form-text m-b-sm">Separate each tag with a comma</div>

                        <label for="category_id" class="form-label">Article Category</label>
                        <select
                            class="form-control form-control-solid-bordered m-b-sm
                                @if($errors->has('category_id'))
                                    border-danger
                                @endif"
                            aria-label="Parent Article"
                            name="category_id"
                            id="category_id"
                        >
                            <option selected disabled> Choose Parent Article </option>
                            @foreach($categories as $item)
                                <option value="{{ $item->id }}" {{ isset($article) && $article->category_id === $item->id ? "selected" : "" }}>
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>

                        <label for="seo_keywords" class="form-label">Article Seo Keywords</label>
                        <textarea
                            class="form-control form-control-solid-bordered m-b-sm @if($errors->has('seo_keywords')) border-danger @endif"
                            aria-describedby="solidBoderedInputExample"
                            placeholder="Article Seo Keywords"
                            name="seo_keywords"
                            id="seo_keywords"
                            rows="5"
                            style="resize: none"
                        >{{ isset($article) ? $article->seo_keywords : "" }}</textarea>

                        <label for="seo_description" class="form-label">Article Seo Description</label>
                        <textarea
                            class="form-control form-control-solid-bordered m-b-sm @if($errors->has('seo_description')) border-danger @endif"
                            aria-describedby="solidBoderedInputExample"
                            placeholder="Article Seo Description"
                            name="seo_description"
                            id="seo_description"
                            rows="5"
                            style="resize: none"
                        >{{ isset($article) ? $article->seo_description : "" }}</textarea>

                        <label for="publish_date" class="form-label">Article Publish Date</label>
                        <input
                            type="text"
                            class="form-control flatpickr2 m-b-sm"
                            id="publish_date"
                            name="publish_date"
                            placeholder="Select Publish Date"
                        >

                        <label for="image" class="form-label">Article Image</label>
                        <input type="file" name="image" id="image" class="form-control" accept="image/png, image/jpeg, image/jpg">
                        <div class="form-text m-b-sm">Article image must be max 2MB</div>

                        @if(isset($article) && $article->image)
                            <label for="image-preview" class="form-label">Article Image Preview</label>
                            <img src="{{ asset($article->image) }}" alt="{{ $article->title }}" class="img-fluid" id="image-preview" style="max-height: 200px">
                        @endif

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="status" value="1" id="status" {{ isset($article) && $article->status ? "checked" : "" }}>
                            <label class="form-check-label" for="status">
                                Do you want this article on homepage?
                            </label>
                        </div>

                        <hr>

                        <div class="col-6 mx-auto mt-4 text-center">
                            <button type="submit" class="btn btn-success btn-style-light w-50">{{ isset($article) ? "Update" : "Save" }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </x-slot:body>
    </x-bootstrap.card>
@endsection

@section('js')
    <script src="{{ asset('assets/admin/plugins/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/admin/js/pages/datepickers.js') }}"></script>
    <script src="{{ asset('assets/admin/plugins/summernote/summernote-lite.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/pages/text-editor.js') }}"></script>
    <script>
        $("#publish_date").flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });
    </script>
@endsection
