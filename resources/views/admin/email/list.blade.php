@extends("layouts.admin")
@section("title")
    Email Theme List
@endsection
@section("css")
    <link rel="stylesheet" href="{{ asset("assets/admin/plugins/select2/css/select2.min.css") }}">
    <link rel="stylesheet" href="{{ asset("assets/admin/plugins/flatpickr/flatpickr.min.css") }}">

    <style>
        .table-hover > tbody > tr:hover {
            --bs-table-hover-bg: transparent;
            background: #363638;
            color: #fff;
        }
    </style>

@endsection

@section("content")
    <x-bootstrap.card>
        <x-slot:header>
            <h2>Email Theme List</h2>
        </x-slot:header>

        <x-slot:body>
            <form action="" method="GET" id="formFilter">
                <div class="row">

                    <div class="col-3 my-2">
                        <select class="js-states form-control" tabindex="-1" id="theme_type" name="theme_type" style="display: none; width: 100%">
                            <option value="{{ null }}">Choose Theme Type</option>
                            <option value="1" {{ request()->get("theme_type") == "1" ? "selected" : "" }}>I Want To Create Myself</option>
                            <option value="2" {{ request()->get("theme_type") == "2" ? "selected" : "" }}>Password Reset Mail</option>
                        </select>
                    </div>
                    <div class="col-3 my-2">
                        <select class="js-states form-control" tabindex="-1" id="process" name="process" style="display: none; width: 100%">
                            <option value="{{ null }}">Choose Process Type</option>
                            <option value="1" {{ request()->get("process") == "1" ? "selected" : "" }}>Email Verify Mail Content</option>
                            <option value="2" {{ request()->get("process") == "2" ? "selected" : "" }}>Password Reset Mail Content</option>
                            <option value="3" {{ request()->get("process") == "3" ? "selected" : "" }}>After Password Reset Mail Content</option>
                        </select>
                    </div>
                    <div class="col-3 my-2">
                        <input type="text" class="form-control" placeholder="Mail İçeriği" name="search_text" value="{{ request()->get("search_text") }}">
                    </div>
                    <hr>
                    <div class="col-6 mb-2 d-flex">
                        <button class="btn btn-primary w-50 me-4" type="submit">Filter</button>
                        <button class="btn btn-warning w-50" type="button" id="btnClearFilter">Clean Filter</button>
                    </div>
                    <hr>
                </div>

            </form>
            <x-bootstrap.table
                :class="'table-striped table-hover table-responsive'"
                :is-responsive="1"
            >
                <x-slot:columns>
                    <th scope="col">Theme Name</th>
                    <th scope="col">Theme Type</th>
                    <th scope="col">Process</th>
                    <th scope="col">Content</th>
                    <th scope="col">Status</th>
                    <th scope="col">Who Created</th>
                    <th scope="col">Created Date</th>
                    <th scope="col">Actions</th>
                </x-slot:columns>

                <x-slot:rows>
                    @foreach($list as $email)
                        <tr id="row-{{ $email->id }}">

                            <td>{{ $email->name }}</td>
                            <td>{{ $email->theme_type }}</td>
                            <td>{{ $email->process }}</td>
                            <td>
                                <a href="javascript:void(0)"
                                   class="btn btn-info btn-sm btnModelThemeDetail"
                                   data-bs-toggle="modal" data-bs-target="#contentViewModal"
                                   data-id="{{ $email->id }}"
                                   data-content="{{ $email->body }}"
                                   data-theme-type="{{ $email->getRawOriginal("theme_type") }}"
                                >
                                    <i class="material-icons ms-0">visibility</i>
                                </a>
                            </td>
                            <td>
                                @if($email->status)
                                    <a href="javascript:void(0)" class="btn btn-success btn-sm btnChangeStatus" data-id="{{ $email->id }}">Aktif</a>
                                @else
                                    <a href="javascript:void(0)" class="btn btn-danger btn-sm btnChangeStatus" data-id="{{ $email->id }}">Pasif</a>
                                @endif
                            </td>
                            <td>{{ $email->user->name }}</td>
                            <td>{{ $email->created_at }}</td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route("admin.email-themes.edit", ['id' => $email->id]) }}"
                                       class="btn btn-warning btn-sm">
                                        <i class="material-icons ms-0">edit</i>
                                    </a>
                                    <a href="javascript:void(0)"
                                       class="btn btn-danger btn-sm btnDelete"
                                       data-name="{{ $email->name }}"
                                       data-id="{{ $email->id }}">
                                        <i class="material-icons ms-0">delete</i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </x-slot:rows>
            </x-bootstrap.table>
            <div class="d-flex justify-content-center">
                {{ $list->appends(request()->all())->onEachside(2)->links() }}
            </div>
        </x-slot:body>
    </x-bootstrap.card>

    <div class="modal fade" id="contentViewModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Theme Content</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">
                    <pre><code class="language-json" id="jsonData"></code></pre>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section("js")
    <script src="{{ asset("assets/admin/plugins/select2/js/select2.full.min.js") }}"></script>
    <script src="{{ asset("assets/admin/js/pages/select2.js") }}"></script>
    <script src="{{ asset("assets/admin/plugins/flatpickr/flatpickr.js") }}"></script>
    <script src="{{ asset("assets/admin/js/pages/datepickers.js") }}"></script>
    <script src="{{ asset("assets/admin/plugins/bootstrap/js/popper.min.js") }}"></script>
    <script>
        $(document).ready(function ()
        {
            $('.btnChangeStatus').click(function () {
                let id = $(this).data('id');
                let self = $(this);
                Swal.fire({
                    title: 'Do you want change the status?',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    denyButtonText: `No`,
                    cancelButtonText: "Cancel"
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed)
                    {
                        $.ajax({
                            method: "POST",
                            url: "{{ route('admin.email-themes.changeStatus') }}",
                            data: {
                                id : id
                            },
                            async:false,
                            success: function (data) {
                                if(data.themeStatus)
                                {
                                    self.removeClass("btn-danger");
                                    self.addClass("btn-success");
                                    self.text("Aktif");
                                }
                                else
                                {
                                    self.removeClass("btn-success");
                                    self.addClass("btn-danger");
                                    self.text("Pasif");
                                }

                                Swal.fire({
                                    title: "Success",
                                    text: "Status Updated Successfully",
                                    confirmButtonText: 'Okay',
                                    icon: "success"
                                });
                            },
                            error: function (){
                                console.log("errors cames");
                            }
                        })
                    }
                    else if (result.isDenied)
                    {
                        Swal.fire({
                            title: "Info",
                            text: "Nothing Changed",
                            confirmButtonText: 'Okay',
                            icon: "info"
                        });
                    }
                })

            });

            $('.btnDelete').click(function () {
                let id = $(this).data('id');
                let themeName = $(this).data('name');

                Swal.fire({
                    title: 'Do you want delete the ' + themeName + '?',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    denyButtonText: `No`,
                    cancelButtonText: "Cancel"
                }).then((result) => {
                    if (result.isConfirmed)
                    {
                        $.ajax({
                            method: "POST",
                            url: "{{ route('admin.email-themes.delete') }}",
                            data: {
                                "_method": "DELETE",
                                id : id
                            },
                            async:false,
                            success: function (data) {

                                $('#row-' + id).remove();
                                Swal.fire({
                                    title: "Success",
                                    text: "Theme Deleted Successfully",
                                    confirmButtonText: 'Okay',
                                    icon: "success"
                                });
                            },
                            error: function (){
                                console.log("errors cames");
                            }
                        })

                    }
                    else if (result.isDenied)
                    {
                        Swal.fire({
                            title: "Info",
                            text: "Nothing to Change",
                            confirmButtonText: 'Okay',
                            icon: "info"
                        });
                    }
                })

            });

            $('.btnModelThemeDetail').click(function () {
                let content = $(this).data("content");
                let themeType = $(this).data("theme-type");
                if (themeType== 1)
                {
                    $('#jsonData').html(content.replace('"', '').replace('"', ''));
                    console.log(content);
                }
                else
                {
                    $('#jsonData').html(JSON.stringify(content, null, 2));
                    document.querySelectorAll('#jsonData').forEach((block) => {
                        hljs.highlightElement(block)
                    })
                }
            });

            $('#theme_type').select2();
            $('#process').select2();
        });
    </script>
@endsection
@push("javascript")
    <script src="{{ asset("assets/front/js/highlight.min.js") }}"></script>
    <script>
        hljs.highlightAll();
    </script>
@endpush
@push("style")
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/highlight/styles/androidstudio.css') }}">
@endpush
