@extends('layouts.admin')
@section('title')
    Category List
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">

    <style>
        .table-hover > tbody > tr:hover {
            --bs-table-hover-bg: transparent;
            background: #abcff5;
        }
    </style>
@endsection

@section('content')
    <x-bootstrap.card>
        {{--? <x-slot name="header" ile <x-slot:header> aynı işlemi yapıyor --}}
        <x-slot:header>
            <h4> Category List </h4>
        </x-slot:header>

        <x-slot:body>
            <form action="" method="GET">
                <div class="row">
                    <div class="col-3 my-1">
                        <input type="text" class="form-control" placeholder="Search Name" name="name" value="{{ request()->get("name") }}">
                    </div>

                    <div class="col-3 my-1">
                        <input type="text" class="form-control" placeholder="Search Slug" name="slug" value="{{ request()->get("slug") }}">
                    </div>

                    <div class="col-3 my-1">
                        <input type="text" class="form-control" placeholder="Search Description" name="description" value="{{ request()->get("description") }}">
                    </div>

                    <div class="col-3 my-1">
                        <input type="text" class="form-control" placeholder="Search Order" name="order" value="{{ request()->get("order") }}">
                    </div>

                    <div class="col-3 my-1">
                        <select class="form-select" name="parent_id">
                            <option selected value="{{ null }}">Parent Category</option>
                            @foreach($parentCategories as $parentCategory)
                                <option value="{{ $parentCategory->id }}" {{ request()->get('parent_id') == $parentCategory->id ? "selected" : "" }}>{{ $parentCategory->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-3 my-1">
                        <select class="form-select" name="user_id">
                            <option selected value="{{ null }}">User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request()->get('user_id') == $user->id ? "selected" : "" }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-3 my-1">
                        <select class="form-select" name="status" aria-label="Status">
                            <option value="{{ null }}">Status</option>
                            <option value="0" {{ request()->get("status") === "0" ? "selected" : "" }}>Inactive</option>
                            <option value="1" {{ request()->get("status") === "1" ? "selected" : "" }}>Active</option>
                        </select>
                    </div>

                    <div class="col-3 my-1">
                        <select class="form-select" name="feature_status" aria-label="Feature Status">
                            <option value="{{ null }}">Feature Status</option>
                            <option value="0" {{ request()->get("feature_status") === "0" ? "selected" : "" }}>Inactive</option>
                            <option value="1" {{ request()->get("feature_status") === "1" ? "selected" : "" }}>Active</option>
                        </select>
                    </div>
                    <hr class="mt-2">
                    <div class="col-6 d-flex mx-auto mb-2 text-center">
                        <button type="submit" class="btn btn-primary w-50 mx-2">Filter</button>
                        <button type="submit" class="btn btn-warning w-50">Clear Filter</button>
                    </div>
                    <hr>
                </div>
            </form>
            <x-bootstrap.table
                :class="'table-striped table-hover table-responsive'"
                :is-responsive="1"
            >
                <x-slot:columns>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Slug</th>
                    <th scope="col">Status</th>
                    <th scope="col">Feature Status</th>
                    <th scope="col">Description</th>
                    <th scope="col">Order</th>
                    <th scope="col">Parent Category</th>
                    <th scope="col">User</th>
                    <th scope="col">Actions</th>
                </x-slot:columns>

                <x-slot:rows>
                    @foreach($categories as $category)
                        <tr>
                            <th scope="row">{{ $loop->index + 1 }}</th>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->slug }}</td>
                            <td>
                                @if($category->status)
                                    <a href="javascript:void(0)" data-id="{{ $category->id }}" class="btn btn-success btn-sm btnChangeStatus">Active</a>
                                @else
                                    <a href="javascript:void(0)" data-id="{{ $category->id }}" class="btn btn-danger btn-sm btnChangeStatus">Inactive</a>
                                @endif
                            </td>
                            <td>
                                @if($category->feature_status)
                                    <a href="javascript:void(0)" data-id="{{ $category->id }}" class="btn btn-success btn-sm btnChangeFeatureStatus">Active</a>
                                @else
                                    <a href="javascript:void(0)" data-id="{{ $category->id }}" class="btn btn-danger btn-sm btnChangeFeatureStatus">Inactive</a>
                                @endif
                            </td>
                            <td title="{{ $category->description }}">{{ substr($category->description, 0, 20) }}</td>
                            <td>{{ $category->order }}</td>
                            <td>{{ $category->parentCategory?->name }}</td>
                            <td>{{ $category->user->name }}</td>
                            <td>
                                <div class="d-flex mx-auto">
                                    <a href="{{ route('category.edit', $category->id) }}" class="btn btn-warning btn-sm"><i class="material-icons ms-0">edit</i> Edit</a>
                                    <a
                                        href="javascript:void(0)"
                                        class="btn btn-danger btn-sm btnDelete"
                                        data-id="{{ $category->id }}"
                                        data-name="{{ $category->name }}"
                                    >
                                        <i class="material-icons ms-0">delete_outline</i> Delete
                                    </a>
                                </div>
                            </td>

                        </tr>
                    @endforeach
                </x-slot:rows>
            </x-bootstrap.table>
            {{ $categories->appends(request()->all())->links() }}
        </x-slot:body>
    </x-bootstrap.card>
    <form action="" method="POST" id="statusChangeForm">
        @csrf
        <input type="hidden" name="id" id="inputStatus" value="">
    </form>
@endsection

@section('js')
    <script src="{{ asset('assets/admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/pages/select2.js') }}"></script>
    <script>
        $(document).ready(function ()
        {
            $(".btnChangeStatus").click(function () {
                let categoryID = $(this).data("id");
                $("#inputStatus").val(categoryID);

                Swal.fire({
                    icon: "question",
                    title: 'Do you want to change the status?',
                    showDenyButton: true,
                    confirmButtonText: 'Change',
                    denyButtonText: `Don't Change`,
                }).then((result) => {
                    if (result.isConfirmed)
                    {
                        $("#statusChangeForm").attr("action", "{{ route('category.changeStatus') }}");
                        $("#statusChangeForm").submit();
                    }
                    else if (result.isDenied)
                    {
                        Swal.fire({
                            icon: "info",
                            title: "Info",
                            text: "Status is not changed",
                            confirmButtonText: "Okay"
                        });
                    }
                })

            });

            $(".btnChangeFeatureStatus").click(function () {
                let categoryID = $(this).data("id");
                $("#inputStatus").val(categoryID);

                Swal.fire({
                    icon: "question",
                    title: 'Do you want to change the feature status?',
                    showDenyButton: true,
                    confirmButtonText: 'Change',
                    denyButtonText: `Don't Change`,
                }).then((result) => {
                    if (result.isConfirmed)
                    {
                        $("#statusChangeForm").attr("action", "{{ route('category.changeFeatureStatus') }}");
                        $("#statusChangeForm").submit();
                    }
                    else if (result.isDenied)
                    {
                        Swal.fire({
                            icon: "info",
                            title: "Info",
                            text: "Feature Status is not changed",
                            confirmButtonText: "Okay"
                        });
                    }
                })

            });

            $(".btnDelete").click(function () {
                let categoryID = $(this).data("id");
                let categoryName = $(this).data("name");
                $("#inputStatus").val(categoryID);

                Swal.fire({
                    icon: "question",
                    title: 'Do you want to delete the ' + categoryName +'?',
                    showDenyButton: true,
                    confirmButtonText: 'Delete',
                    denyButtonText: `Don't Delete`,
                }).then((result) => {
                    if (result.isConfirmed)
                    {
                        $("#statusChangeForm").attr("action", "{{ route('category.delete') }}");
                        $("#statusChangeForm").submit();
                    }
                    else if (result.isDenied)
                    {
                        Swal.fire({
                            icon: "info",
                            title: "Info",
                            text: "Delete is canceled",
                            confirmButtonText: "Okay"
                        });
                    }
                })

            });


        });
    </script>
@endsection
