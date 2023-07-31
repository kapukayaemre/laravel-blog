@extends('layouts.admin')
@section('title')
    Settings
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset("assets/admin/plugins/summernote/summernote-lite.min.css") }}">
@endsection

@section('content')
    <x-bootstrap.card>
        <x-slot:header>
            <h4 class="card-title">Settings</h4>
        </x-slot:header>

        <x-slot:body>
            <div class="example-container">
                <div class="example-content">
                    @if($errors->any())
                        @foreach($errors->all() as $error)
                            <div class="alert alert-style-light alert-danger">{{ $error }}</div>
                        @endforeach
                    @endif
                    <form action="{{ route("settings") }}" method="POST" enctype="multipart/form-data" id="settingsForm">
                        @csrf
                        <label for="header_text" class="form-label">Header Text</label>
                        <textarea
                            class="form-control form-control-solid-bordered m-b-sm @if($errors->has('header_text')) border-danger @endif"
                            placeholder="Header Text"
                            name="header_text"
                            id="header_text"
                            rows="5"
                        >{!! isset($settings) ? $settings->header_text : "" !!}</textarea>

                        <label for="footer_text" class="form-label mt-3">Footer Text</label>
                        <textarea
                            class="form-control form-control-solid-bordered m-b-sm @if($errors->has('footer_text')) border-danger @endif"
                            placeholder="Footer Text"
                            name="footer_text"
                            id="footer_text"
                            rows="5"
                        >{!! isset($settings) ? $settings->footer_text : "" !!}</textarea>

                        <label for="telegram_link" class="form-label mt-3">Telegram Link</label>
                        <input
                            type="text"
                            class="form-control form-control-solid-bordered m-b-sm @if($errors->has('telegram_link')) border-danger @endif"
                            placeholder="Telegram Link"
                            name="telegram_link"
                            id="telegram_link"
                            value="{{ isset($settings) ? $settings->telegram_link : "" }}"
                        >


                        <label for="logo" class="form-label">Logo</label>
                        <input type="file" name="logo" id="logo" class="form-control" accept="image/png, image/jpeg, image/jpg">
                        <div class="form-text m-b-sm">Logo must be lower than 2MB</div>

                        @if(isset($settings) && $settings->logo)
                            <label for="image-preview" class="form-label">Logo Image Preview</label><br>
                            <img src="{{ asset($settings->logo) }}" alt="" class="img-fluid m-b-sm" id="image-preview" style="max-height: 200px">
                        @endif

                        <label for="category_default_image" class="form-label">Category Default Image</label>
                        <input type="file" name="category_default_image" id="category_default_image" class="form-control" accept="image/png, image/jpeg, image/jpg">
                        <div class="form-text m-b-sm">Category Default Image must be lower than 2MB</div>

                        @if(isset($settings) && $settings->category_default_image)
                            <label for="image-preview" class="form-label">Category Default Image Preview</label><br>
                            <img src="{{ asset($settings->category_default_image) }}" alt="" class="img-fluid m-b-sm" id="image-preview" style="max-height: 200px">
                        @endif

                        <label for="article_default_image" class="form-label">Article Default Image</label>
                        <input type="file" name="article_default_image" id="article_default_image" class="form-control" accept="image/png, image/jpeg, image/jpg">
                        <div class="form-text m-b-sm">Article Default Image must be lower than 2MB</div>

                        @if(isset($settings) && $settings->article_default_image)
                            <label for="image-preview" class="form-label">Article Default Image Preview</label><br>
                            <img src="{{ asset($settings->article_default_image) }}" alt="" class="img-fluid m-b-sm" id="image-preview" style="max-height: 200px">
                        @endif

                        <hr>

                        <div class="form-check m-b-sm mt-5">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="feature_categories_is_active"
                                value="1"
                                id="feature_categories_is_active"
                                {{ isset($settings) && $settings->feature_categories_is_active ? "checked" : "" }}
                            >
                            <label class="form-check-label" for="feature_categories_is_active">
                                Do you want to set feature categories to active?
                            </label>
                        </div>

                        <div class="form-check m-b-sm">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="video_is_active"
                                value="1"
                                id="video_is_active"
                                {{ isset($settings) && $settings->video_is_active ? "checked" : "" }}
                            >
                            <label class="form-check-label" for="video_is_active">
                                Do you want to set video section to active?
                            </label>
                        </div>

                        <div class="form-check m-b-sm">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="author_is_active"
                                value="1"
                                id="author_is_active"
                                {{ isset($settings) && $settings->author_is_active ? "checked" : "" }}
                            >
                            <label class="form-check-label" for="author_is_active">
                                Do you want to set author sections to active?
                            </label>
                        </div>

                        <hr>

                        <div class="col-6 mx-auto mt-4 text-center">
                            <button type="submit" class="btn btn-success btn-style-light w-50" id="btnSave">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </x-slot:body>
    </x-bootstrap.card>
@endsection

@section('js')
    <script src="{{ asset('assets/admin/plugins/summernote/summernote-lite.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/pages/text-editor.js') }}"></script>
    <script>
        let name = $("#name");

        $(document).ready(function () {
            $("#btnSave").click(function () {
                if(name.val().trim() === "" || name.val().trim() == null)
                {
                    Swal.fire({
                        icon: "info",
                        title: "Info",
                        text: "Name field cannot be empty",
                        confirmButtonText: "Okay"
                    })
                }
                else
                {
                    $("#settingsForm").submit();
                }
            })
        });
    </script>
@endsection
