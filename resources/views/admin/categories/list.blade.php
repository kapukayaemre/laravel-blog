@extends('layouts.admin')
@section('title')
    Category List
@endsection

@section('css')
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
                            <td>{{ substr($category->description, 0, 20) }}</td>
                            <td>{{ $category->order }}</td>
                            <td>{{ $category->parentCategory?->name }}</td>
                            <td>{{ $category->user->name }}</td>
                            <td>
                                <div class="d-flex mx-auto">
                                    <a href="javascript:void(0)" class="btn btn-warning btn-sm"><i class="material-icons ms-0">edit</i> Edit</a>
                                    <a href="javascript:void(0)" class="btn btn-danger btn-sm"><i class="material-icons ms-0">delete_outline</i> Delete</a>
                                </div>
                            </td>

                        </tr>
                    @endforeach
                </x-slot:rows>
            </x-bootstrap.table>
        </x-slot:body>
    </x-bootstrap.card>
    <form action="" method="POST" id="statusChangeForm">
        @csrf
        <input type="hidden" name="id" id="inputStatus" value="">
    </form>
@endsection

@section('js')
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
                            text: "Status is not changed",
                            confirmButtonText: "Okay"
                        });
                    }
                })

            });
        });
    </script>
@endsection
