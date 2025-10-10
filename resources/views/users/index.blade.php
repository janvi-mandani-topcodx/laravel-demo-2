@extends('layout')
@section('contents')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col col-12 text-center">
                                <h2 class="">Users</h2>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end my-3">
                            <div class="col-xs-8 text-right w-66 p-0">
                                <a href="{{route('user.create')}}" class="btn btn-sm btn-primary" id="create-user-demo">Create New</a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body table-responsive">
                        <table class="table table-hover" id="user-demo-container">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Hobbies</th>
                                <th>Phone Number</th>
                                <th>Gender</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <div id="user-demo-tr">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    @include('users.templates')

    <script>
        $(document).ready(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let table = new DataTable('#user-demo-container', {
                deferRender: true,
                scroller: false,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('user.index') }}",
                },
                columns: [
                    { data: 'id', name: 'id' },
                    {
                        data: function (row){
                            return  `<a href="/user/${row.id}" data-id='${row.id}'>${row.first_name}</a>`;
                        },
                        name: 'first name'
                    },
                    { data: 'last_name', name: 'last name' },
                    { data: 'email', name: 'email' },
                    { data: 'hobbies', name: 'hobbies' },
                    { data: 'phone_number', name: 'phone number' , type: 'string'},
                    { data: 'gender', name: 'gender' },
                    {
                        data: function (row) {
                            let url = `/user/${row.id}/edit`;
                            let data = [{
                                'id': row.id,
                                'url': url,
                                'edit': 'Edit',
                                'delete': 'Delete'
                            }];
                            let template = $.templates("#editDeleteScript");
                            return template.render(data);
                        },
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ]
            });


            $(document).on('click', '#delete-users', function () {
                let userId = $(this).data('id');

                $.ajax({
                    url: `/user/${userId}`,
                    type: "DELETE",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function () {
                        table.ajax.reload(null, false);
                    },
                });
            });
        });
    </script>
@endsection
