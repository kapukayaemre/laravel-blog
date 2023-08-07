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

                        <label for="seo_keywords_home" class="form-label m-t-sm">Homepage Seo Keywords</label>
                        <textarea
                            class="form-control form-control-solid-bordered m-b-sm"
                            name="seo_keywords_home"
                            id="seo_keywords_home"
                            cols="30"
                            rows="5"
                            placeholder="Homepage Seo Keywords"
                            style="resize: none">{{ isset($settings) ? $settings->seo_keywords_home : "" }}</textarea>

                        <label for="seo_description_home" class="form-label m-t-sm">Homepage Seo Description</label>
                        <textarea
                            class="form-control form-control-solid-bordered m-b-sm"
                            name="seo_description_home"
                            id="seo_description_home"
                            cols="30"
                            rows="5"
                            placeholder="Homepage Seo Description"
                            style="resize: none">{{ isset($settings) ? $settings->seo_description_home : "" }}</textarea>

                        <label for="seo_keywords_articles" class="form-label m-t-sm">Articles Seo Keywords</label>
                        <textarea
                            class="form-control form-control-solid-bordered m-b-sm"
                            name="seo_keywords_articles"
                            id="seo_keywords_articles"
                            cols="30"
                            rows="5"
                            placeholder="Articles Seo Keywords"
                            style="resize: none">{{ isset($settings) ? $settings->seo_keywords_articles : "" }}</textarea>

                        <label for="seo_description_articles" class="form-label m-t-sm">Articles Seo Description</label>
                        <textarea
                            class="form-control form-control-solid-bordered m-b-sm"
                            name="seo_description_articles"
                            id="seo_description_articles"
                            cols="30"
                            rows="5"
                            placeholder="Articles Seo Description"
                            style="resize: none">{{ isset($settings) ? $settings->seo_description_articles : "" }}</textarea>

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

                        <hr>

                        <label for="category_default_image" class="form-label">Category Default Image</label>
                        <input type="file" name="category_default_image" id="category_default_image" class="form-control" accept="image/png, image/jpeg, image/jpg">
                        <div class="form-text m-b-sm">Category Default Image must be lower than 2MB</div>

                        @if(isset($settings) && $settings->category_default_image)
                            <label for="image-preview" class="form-label">Category Default Image Preview</label><br>
                            <img src="{{ asset($settings->category_default_image) }}" alt="" class="img-fluid m-b-sm" id="image-preview" style="max-height: 200px">
                        @endif

                        <hr>

                        <label for="article_default_image" class="form-label">Article Default Image</label>
                        <input type="file" name="article_default_image" id="article_default_image" class="form-control" accept="image/png, image/jpeg, image/jpg">
                        <div class="form-text m-b-sm">Article Default Image must be lower than 2MB</div>

                        @if(isset($settings) && $settings->article_default_image)
                            <label for="image-preview" class="form-label">Article Default Image Preview</label><br>
                            <img src="{{ asset($settings->article_default_image) }}" alt="" class="img-fluid m-b-sm" id="image-preview" style="max-height: 200px">
                        @endif

                        <hr>

                        <label for="reset_password_image" class="form-label">Reset Password Image</label>
                        <input type="file" name="reset_password_image" id="reset_password_image" class="form-control" accept="image/png, image/jpeg, image/jpg">
                        <div class="form-text m-b-sm">Reset Password Image must be lower than 2MB</div>

                        @if(isset($settings) && $settings->reset_password_image)
                            <label for="image-preview" class="form-label">Reset Password Image Preview</label><br>
                            <img src="{{ asset($settings->reset_password_image) }}" alt="" class="img-fluid m-b-sm" id="image-preview" style="max-height: 200px">
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
        $(document).ready(function ()
        {

            $('#btnSave').click(function () {
                let logoCheckStatus = imageCheck($('#logo'));
                let category_default_imageStatus = imageCheck($('#category_default_image'));
                let article_default_imageStatus = imageCheck($('#article_default_image'));
                let reset_password_imageStatus = imageCheck($('#reset_password_image'));

                if(!logoCheckStatus || !category_default_imageStatus || !article_default_imageStatus || !reset_password_imageStatus)
                {
                    return false;
                }
                else
                {
                    $("#settingsForm").submit();
                }
            });
        });
    </script>
@endsection
