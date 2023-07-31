@extends('layouts.admin')
@section('title')
    {{ isset($user) ? "Update User" : "New User" }}
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset("assets/admin/plugins/flatpickr/flatpickr.min.css") }}">
    <link rel="stylesheet" href="{{ asset("assets/admin/plugins/summernote/summernote-lite.min.css") }}">
@endsection

@section('content')
    <x-bootstrap.card>
        <x-slot:header>
            <h4 class="card-title">{{ isset($user) ? "Update User" : "New User" }}</h4>
        </x-slot:header>

        <x-slot:body>
            <div class="example-container">
                <div class="example-content">
                    @if($errors->any())
                        @foreach($errors->all() as $error)
                            <div class="alert alert-style-light alert-danger">{{ $error }}</div>
                        @endforeach
                    @endif
                    <form action="{{ isset($user) ? route('user.edit', $user->username) : route('user.create') }}" method="POST" enctype="multipart/form-data" id="userForm">
                        @csrf
                        <label for="username" class="form-label">Username</label>
                        <input
                            type="text"
                            class="form-control form-control-solid-bordered m-b-sm
                            @if($errors->has('username'))
                                border-danger
                            @endif"
                            placeholder="Password"
                            name="username"
                            id="username"
                            value="{{ isset($user) ? $user->username : "" }}"
                            required
                        >

                        <label for="password" class="form-label">Password</label>
                        <input
                            type="password"
                            class="form-control form-control-solid-bordered m-b-sm
                            @if($errors->has('password'))
                                border-danger
                            @endif"
                            placeholder="Password"
                            name="password"
                            id="password"
                            value=""
                            required
                        >

                        {{--?
                        ? @if($errors->has("name"))
                        ?    {{ $errors->first("name") }}
                        ? @endif
                        ? --}}

                        <label for="name" class="form-label">User Name and Surname</label>
                        <input
                            type="text"
                            class="form-control form-control-solid-bordered m-b-sm @if($errors->has('name')) border-danger @endif"
                            placeholder="User Name and Surname"
                            name="name"
                            id="name"
                            value="{{ isset($user) ? $user->name : "" }}"
                        >

                        <label for="email" class="form-label">Email</label>
                        <input
                            type="email"
                            class="form-control form-control-solid-bordered m-b-sm @if($errors->has('email')) border-danger @endif"
                            placeholder="Email"
                            name="email"
                            id="email"
                            value="{{ isset($user) ? $user->email : "" }}"
                        >

                        <label for="about" class="form-label">About</label>
                        <textarea name="about" id="about" class="m-b-sm">{!! isset($user) ? $user->about : "" !!}</textarea>

                        <div class="row mt-4 m-b-sm">
                            <div class="col-8">
                                <label for="image" class="form-label">User Image</label>
                                <select name="image" id="image" class="form-control form-control-solid-bordered">
                                    <option value="{{ null }}">Choose Image</option>
                                    <option value="/assets/admin/images/user-images/profile1.png">Profile 1</option>
                                    <option value="/assets/admin/images/user-images/profile2.png">Profile 2</option>
                                </select>
                            </div>

                            <div class="col-2 offset-1">
                                <img src="{{ isset($user) ? asset($user->image) : "" }}" id="image-preview" class="img-fluid m-b-sm" style="max-height: 200px">
                            </div>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="status" value="1" id="status" {{ isset($user) && $user->status ? "checked" : "" }}>
                            <label class="form-check-label" for="status">
                                Do you want to user activate?
                            </label>
                        </div>

                        <hr>

                        <div class="col-6 mx-auto mt-4 text-center">
                            <button type="button" class="btn btn-success btn-style-light w-50" id="btnSave">{{ isset($user) ? "Update" : "Save" }}</button>
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

    <script>
        let username = $("#username");
        let email = $("#email");
        let name = $("#name");

        $(document).ready(function ()
        {
            $("#btnSave").click(function () {
                if(username.val().trim() === "" || username.val().trim() == null)
                {
                    Swal.fire({
                        icon: "info",
                        title: "Info",
                        text: "Username field cannot be empty",
                        confirmButtonText: "Okay"
                    })
                }
                else if(name.val().trim() === "" || name.val().trim() == null)
                {
                    Swal.fire({
                        icon: "info",
                        title: "Info",
                        text: "Name and Surname field cannot be empty",
                        confirmButtonText: "Okay"
                    })
                }
                else if(email.val().trim() === "" || email.val().trim() == null)
                {
                    Swal.fire({
                        icon: "info",
                        title: "Info",
                        text: "Email field cannot be empty",
                        confirmButtonText: "Okay"
                    })
                }
                else
                {
                    $("#userForm").submit();
                }
            })

            $("#image").change(function () {
                $("#image-preview").attr("src", $(this).val());
            })
        });
    </script>
@endsection
