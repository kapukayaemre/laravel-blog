@extends("layouts.admin")
@section("title")
    User List
@endsection
@section("css")
    <link rel="stylesheet" href="{{ asset("assets/admin/plugins/flatpickr/flatpickr.min.css") }}">
    <link rel="stylesheet" href="{{ asset("assets/admin/plugins/select2/css/select2.min.css") }}">

    <style>
        .table-hover > tbody > tr:hover {
            --bs-table-hover-bg: transparent;
            background: #abcff5;
        }

        table td {
            vertical-align: middle; !important;
            height: 60px;
        }

    </style>
@endsection

@section("content")
    <x-bootstrap.card>
        <x-slot:header>
            <h2>User List</h2>
        </x-slot:header>

        <x-slot:body>
            <form action="{{ route("user.index") }}" method="GET">
                <div class="row">
                    <div class="col-6 my-1">
                        <select class="form-select" name="status" aria-label="Status">
                            <option value="{{ null }}">Status</option>
                            <option value="0" {{ request()->get("status") === "0" ? "selected" : "" }}>Inactive</option>
                            <option value="1" {{ request()->get("status") === "1" ? "selected" : "" }}>Active</option>
                        </select>
                    </div>

                    <div class="col-6 my-1">
                        <input type="text" class="form-control" placeholder="Name, Username, Email" name="search_text" value="{{ request()->get("search_text") }}">
                    </div>

                    <hr class="mt-2">

                    <div class="row d-flex justify-content-center mb-2">
                        <div class="col-6 d-flex justify-content-center">
                            <button class="btn btn-primary w-50 me-4" type="submit">Filter</button>
                        </div>
                        <div class="col-6 d-flex justify-content-center">
                            <button class="btn btn-warning w-50" type="button">Clean Filter</button>
                        </div>
                    </div>
                </div>

            </form>
            <x-bootstrap.table
                :class="'table-striped table-hover table-responsive'"
                :is-responsive="1"
            >
                <x-slot:columns>
                    <th scope="col">Image</th>
                    <th scope="col">Name</th>
                    <th scope="col">Username</th>
                    <th scope="col">Email</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                </x-slot:columns>

                <x-slot:rows>
                    @foreach($users as $user)
                        <tr id="row-{{ $user->id }}">
                            <td>
                                @if(!empty($user->image))
                                    <img src="{{ asset($user->image) }}" height="60">
                                @endif
                            </td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->status)
                                    <a href="javascript:void(0)" class="btn btn-success btn-sm btnChangeStatus" data-id="{{ $user->id }}">Active</a>
                                @else
                                    <a href="javascript:void(0)" class="btn btn-danger btn-sm btnChangeStatus" data-id="{{ $user->id }}">Inactive</a>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route("user.edit",["id" => $user->id]) }}"
                                       class="btn btn-warning btn-sm"><i class="material-icons ms-0">edit</i></a>
                                    <a href="javascript:void(0)"
                                       class="btn btn-danger btn-sm btnDelete"
                                       data-name="{{ $user->title }}"
                                       data-id="{{ $user->id }}">
                                        <i class="material-icons ms-0">delete</i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </x-slot:rows>
            </x-bootstrap.table>
            {{ $users->appends(request()->all())->onEachside(2)->links() }}
        </x-slot:body>
    </x-bootstrap.card>
@endsection

@section("js")
    <script src="{{ asset("assets/admin/plugins/select2/js/select2.full.min.js") }}"></script>
    <script src="{{ asset("assets/admin/js/pages/select2.js") }}"></script>
    <script src="{{ asset("assets/admin/plugins/flatpickr/flatpickr.js") }}"></script>
    <script src="{{ asset("assets/admin/js/pages/datepickers.js") }}"></script>
    <script src="{{ asset("assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js") }}"></script>
    <script src="{{ asset("assets/admin/plugins/bootstrap/js/popper.min.js") }}"></script>
    <script>
        $(document).ready(function ()
        {

            $('.btnChangeStatus').click(function () {
                let articleID = $(this).data('id');
                let self = $(this);

                Swal.fire({
                    icon: "question",
                    title: 'Do you want to change the status?',
                    showDenyButton: true,
                    confirmButtonText: 'Change',
                    denyButtonText: `Don't Change`,
                    cancelButtonText: "İptal"
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed)
                    {
                        $.ajax({
                            method: "POST",
                            url: "{{ route("user.changeStatus") }}",
                            data: {
                                articleID : articleID
                            },
                            async: false,
                            success: function (data){
                                if(data.article_status)
                                {
                                    self.removeClass("btn-danger");
                                    self.addClass("btn-success");
                                    self.text("Active");
                                }
                                else
                                {
                                    self.removeClass("btn-success");
                                    self.addClass("btn-danger");
                                    self.text("Inactive");
                                }

                                Swal.fire({
                                    icon: "success",
                                    title: "Success",
                                    text: "Status Updated Successfully",
                                    confirmButtonText: 'Okay',
                                });

                            },
                            error: function (){
                                console.log("hata geldi");
                            }
                        })
                    }
                    else if (result.isDenied)
                    {
                        Swal.fire({
                            icon: "info",
                            title: "Info",
                            text: "Nothing Changed",
                            confirmButtonText: 'Okay',
                        });
                    }
                })
            });

            $('.btnDelete').click(function () {
                let articleID = $(this).data('id');
                let articleName = $(this).data('name');

                Swal.fire({
                    title: "Do you want to delete this " + articleName + "?",
                    showDenyButton: true,
                    confirmButtonText: 'Yes',
                    denyButtonText: `No`
                }).then((result) => {
                    if (result.isConfirmed)
                    {
                        $.ajax({
                            method: "POST",
                            url: "{{ route("user.delete") }}",
                            data: {
                                "_method": "DELETE",
                                articleID : articleID
                            },
                            async: false,
                            success: function (data){
                                $('#row-' + articleID).remove();

                                Swal.fire({
                                    icon: "success",
                                    title: "Success",
                                    text: "Article Deleted Successfully",
                                    confirmButtonText: 'Okay'
                                });
                            },
                            error: function (){
                                console.log("error comes");
                            }
                        })
                    }
                    else if (result.isDenied)
                    {
                        Swal.fire({
                            icon: "info",
                            title: "Info",
                            text: "Nothing Changed",
                            confirmButtonText: 'Okay'
                        });
                    }
                })

            });

            $('#selectParentCategory').select2();


        });
    </script>
    <script>
        $("#publish_date").flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });
    </script>
@endsection
