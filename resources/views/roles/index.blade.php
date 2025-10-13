@extends('layout')
@section('contents')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col col-12 text-center">
                                <h2 class="">Roles</h2>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end my-3">
                            <div class="col-xs-8 text-right w-66 p-0">
                                <a href="{{route('role.create')}}" class="btn btn-sm btn-primary" id="createRole">Create New</a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body table-responsive">
                        <table class="table table-hover" id="RolesContainer">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    @include('roles.templates')

    <script>
        $(document).ready(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let table = new DataTable('#RolesContainer', {
                deferRender: true,
                scroller: false,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('role.index') }}",
                },
                columns: [
                    { data: 'id', name: 'id' },
                    {
                        data: function (row){
                            return  '<a href="'+ route('role.show' , row.id)+'" data-id="'+ row.id +'">'+ row.name +'</a>';
                        },
                        name: 'first name'
                    },
                    {
                        data: function (row) {
                            let url = route('role.edit' , row.id );
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


            $(document).on('click', '#deleteRole', function () {
                let roleId = $(this).data('id');

                $.ajax({
                    url: route('role.destroy' , roleId),
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
