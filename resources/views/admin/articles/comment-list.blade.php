@extends("layouts.admin")
@section("title")
    @if($page === "comment-list")
        All Comment List
    @else
        Pending Approval Comment List
    @endif
@endsection
@section("css")
    <link rel="stylesheet" href="{{ asset("assets/admin/plugins/flatpickr/flatpickr.min.css") }}">
    <link rel="stylesheet" href="{{ asset("assets/admin/plugins/select2/css/select2.min.css") }}">

    <style>
        .table-hover > tbody > tr:hover {
            --bs-table-hover-bg: transparent;
            background: #abcff5;
        }
    </style>
@endsection

@section("content")
    <x-bootstrap.card>
        <x-slot:header>
            <h2>
                @if($page === "comment-list")
                    All Comment List
                @else
                    Pending Approval Comment List
                @endif
            </h2>
        </x-slot:header>

        <x-slot:body>
            <form action="{{ $page === "comment-list" ? route("article.comment-list") : route("article.pending-approval") }}" method="GET" id="formFilter">
                <div class="row">
                    <div class="col-3 my-1">
                        <select class="form-select" name="user_id">
                            <option value="{{ null }}">Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request()->get("user_id") == $user->id ? "selected" : "" }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if($page === "comment-list")
                        <div class="col-3 my-1">
                            <select class="form-select" name="status" aria-label="Status">
                                <option value="{{ null }}">Status</option>
                                <option value="0" {{ request()->get("status") === "0" ? "selected" : "" }}>Inactive</option>
                                <option value="1" {{ request()->get("status") === "1" ? "selected" : "" }}>Active</option>
                            </select>
                        </div>
                    @endif

                    <div class="col-3 my-1">
                        <input class="form-control flatpickr2 m-b-sm"
                               id="created_at"
                               name="created_at"
                               type="text"
                               value="{{ request()->get("created_at") }}"
                               placeholder="Comment Date">
                    </div>
                    <div class="col-3 my-1">
                        <input type="text" class="form-control" placeholder="Comment, Name, Email" name="search_text" value="{{ request()->get("search_text") }}">
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
                    <th scope="col">Article Link</th>
                    <th scope="col">Username</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">IP</th>
                    <th scope="col">Status</th>
                    <th scope="col">Comment</th>
                    <th scope="col">Create Date</th>
                    <th scope="col">Actions</th>
                </x-slot:columns>

                <x-slot:rows>
                    @foreach($comments as $comment)
                        <tr id="row-{{ $comment->id }}">
                            <td>
                                <a href="{{ route("front.articleDetail",[
                                    'user' => $comment->article->user->username,
                                    'article' => $comment->article->slug
                                 ]) }}" target="_blank">
                                    <span class="material-icons-outlined">visibility</span>
                                </a>
                            </td>
                            <td>{{ $comment->user?->name }}</td>
                            <td>{{ $comment->name }}</td>
                            <td>{{ $comment->email }}</td>
                            <td>{{ $comment->ip }}</td>
                            <td>
                                @if($comment->status)
                                    <a href="javascript:void(0)" class="btn btn-success btn-sm btnChangeStatus" data-id="{{ $comment->id }}">Active</a>
                                @else
                                    <a href="javascript:void(0)" class="btn btn-danger btn-sm btnChangeStatus" data-id="{{ $comment->id }}">Inactive</a>
                                @endif
                            </td>
                            <td>
                                <span data-bs-container="body" data-bs-toggle="tooltip" data-bs-placement="top"
                                      data-bs-title="{{ substr( $comment->comment , 0, 200) }}">
                                    {{ substr( $comment->comment, 0, 10 ) }}
                                </span>
                                <button type="button" class="btn btn-primary lookComment btn-sm p-0 px-2"
                                        data-comment="{{ $comment->comment }}" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal">
                                    <span class="material-icons-outlined" style="line-height: unset; font-size: 20px">visibility</span>
                                </button>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($comment->created_at)->translatedFormat("d F Y H:i:s") }}</td>
                            <td>
                                <div class="d-flex">
                                    <a href="javascript:void(0)"
                                       class="btn btn-danger btn-sm btnDelete"
                                       data-name="{{ $comment->id }}"
                                       data-id="{{ $comment->id }}">
                                       <i class="material-icons ms-0">delete</i>
                                    </a>
                                    @if($comment->deleted_at)
                                        <a href="javascript:void(0)"
                                           class="btn btn-info btn-sm btnRestore"
                                           data-name="{{ $comment->id }}"
                                           data-id="{{ $comment->id }}">
                                           <i class="material-icons ms-0">restore</i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </x-slot:rows>
            </x-bootstrap.table>
            {{ $comments->appends(request()->all())->onEachside(2)->links() }}
        </x-slot:body>
    </x-bootstrap.card>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">
                    ...
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
    <script src="{{ asset("assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js") }}"></script>
    <script src="{{ asset("assets/admin/plugins/bootstrap/js/popper.min.js") }}"></script>
    <script>
        $(document).ready(function ()
        {

            $('.btnChangeStatus').click(function () {
                let id = $(this).data('id');
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
                            url: "{{ route("article.pending-approval.change-status") }}",
                            data: {
                                id : id
                            },
                            async: false,
                            success: function (data){
                                if(data.comment_status)
                                {
                                    $("#row-" + id).remove();
                                }

                                Swal.fire({
                                    icon: "success",
                                    title: "Success",
                                    text: "Comment Accepted",
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
                let commentID = $(this).data('id');
                let comment = $(this).data('name');

                Swal.fire({
                    title: "Do you want to delete this " + comment + "?",
                    showDenyButton: true,
                    confirmButtonText: 'Yes',
                    denyButtonText: `No`
                }).then((result) => {
                    if (result.isConfirmed)
                    {
                        $.ajax({
                            method: "POST",
                            url: "{{ route("comment.delete") }}",
                            data: {
                                "_method": "DELETE",
                                commentID : commentID
                            },
                            async: false,
                            success: function (data){
                                $('#row-' + commentID).remove();

                                Swal.fire({
                                    icon: "success",
                                    title: "Success",
                                    text: "Comment Deleted Successfully",
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

            $('.btnRestore').click(function () {
                let commentID = $(this).data('id');
                let comment = $(this).data('name');
                let self = $(this);

                Swal.fire({
                    title: "Do you want to restore this " + comment + "?",
                    showDenyButton: true,
                    confirmButtonText: 'Yes',
                    denyButtonText: `No`
                }).then((result) => {
                    if (result.isConfirmed)
                    {
                        $.ajax({
                            method: "POST",
                            url: "{{ route("comment.restore") }}",
                            data: {
                                commentID : commentID
                            },
                            async: false,
                            success: function (data){
                                self.remove();
                                Swal.fire({
                                    icon: "success",
                                    title: "Success",
                                    text: "Comment Restored Successfully",
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

            $("#created_at").flatpickr({
                dateFormat: "Y-m-d"
            })

            $(".lookComment").click(function () {
                let comment = $(this).data("comment");
                $("#modalBody").text(comment);
            })

        });
    </script>
@endsection
