@extends('layouts.admin')
@section('title')
    {{ isset($category) ? "Update Category" : "New Category" }}
@endsection

@section('css')
@endsection

@section('content')
    <x-bootstrap.card>
        <x-slot:header>
            <h4 class="card-title">{{ isset($category) ? "Update Category" : "New Category" }}</h4>
        </x-slot:header>

        <x-slot:body>
            <div class="example-container">
                <div class="example-content">
                    @if($errors->any())
                        @foreach($errors->all() as $error)
                            <div class="alert alert-style-light alert-danger">{{ $error }}</div>
                        @endforeach
                    @endif
                    <form action="{{ isset($category) ? route('category.edit', $category->id) : route('category.create') }}" method="POST">
                        @csrf
                        <label for="name" class="form-label">Category Name</label>
                        <input
                            type="text"
                            class="form-control form-control-solid-bordered m-b-sm @if($errors->has('name')) border-danger @endif"
                            aria-describedby="solidBoderedInputExample"
                            placeholder="Category Name"
                            name="name"
                            id="name"
                            value="{{ isset($category) ? $category->name : "" }}"
                            required
                        >
                        {{--?
                        ? @if($errors->has("name"))
                        ?    {{ $errors->first("name") }}
                        ? @endif
                        ? --}}

                        <label for="slug" class="form-label">Category Slug</label>
                        <input
                            type="text"
                            class="form-control form-control-solid-bordered m-b-sm @if($errors->has('slug')) border-danger @endif"
                            aria-describedby="solidBoderedInputExample"
                            placeholder="Category Slug"
                            name="slug"
                            id="slug"
                            value="{{ isset($category) ? $category->slug : "" }}"
                        >

                        <label for="description" class="form-label">Category Description</label>
                        <textarea
                            class="form-control form-control-solid-bordered m-b-sm @if($errors->has('description')) border-danger @endif"
                            aria-describedby="solidBoderedInputExample"
                            placeholder="Category Description"
                            name="description"
                            id="description"
                            rows="5"
                            style="resize: none"
                        >{{ isset($category) ? $category->description : "" }}</textarea>

                        <label for="order" class="form-label">Category Order</label>
                        <input
                            type="number"
                            class="form-control form-control-solid-bordered m-b-sm @if($errors->has('order')) border-danger @endif"
                            aria-describedby="solidBoderedInputExample"
                            placeholder="Category Order"
                            name="order"
                            id="order"
                            value="{{ isset($category) ? $category->order : "" }}"
                        >

                        <label for="parent_id" class="form-label">Parent Category</label>
                        <select class="form-control form-control-solid-bordered m-b-sm @if($errors->has('parent_id')) border-danger @endif" aria-label="Parent Category" name="parent_id" id="parent_id">
                            <option selected disabled> Choose Parent Category </option>
                            @foreach($categories as $item)
                                <option value="{{ $item->id }}" {{ isset($category) && $category->id === $item->id ? "selected" : "" }}>
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>

                        <label for="seo_keywords" class="form-label">Category Seo Keywords</label>
                        <textarea
                            class="form-control form-control-solid-bordered m-b-sm @if($errors->has('seo_keywords')) border-danger @endif"
                            aria-describedby="solidBoderedInputExample"
                            placeholder="Category Seo Keywords"
                            name="seo_keywords"
                            id="seo_keywords"
                            rows="5"
                            style="resize: none"
                        >{{ isset($category) ? $category->seo_keywords : "" }}</textarea>

                        <label for="seo_description" class="form-label">Category Seo Description</label>
                        <textarea
                            class="form-control form-control-solid-bordered m-b-sm @if($errors->has('seo_description')) border-danger @endif"
                            aria-describedby="solidBoderedInputExample"
                            placeholder="Category Seo Description"
                            name="seo_description"
                            id="seo_description"
                            rows="5"
                            style="resize: none"
                        >{{ isset($category) ? $category->seo_description : "" }}</textarea>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="status" value="1" id="status" {{ isset($category) && $category->status ? "checked" : "" }}>
                            <label class="form-check-label" for="status">
                                Do you want to activate category?
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="feature_status" value="1" id="feature_status" {{ isset($category) && $category->feature_status ? "checked" : "" }}>
                            <label class="form-check-label" for="feature_status">
                                Do you want feature category on homepage?
                            </label>
                        </div>

                        <hr>

                        <div class="col-6 mx-auto mt-4 text-center">
                            <button type="submit" class="btn btn-success btn-style-light w-50">{{ isset($category) ? "Update" : "Save" }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </x-slot:body>
    </x-bootstrap.card>
@endsection

@section('js')
@endsection
