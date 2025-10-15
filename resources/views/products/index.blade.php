@extends('layout')
@section('contents')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col col-12 text-center">
                                <h2 class="">Products</h2>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end my-3">
                            <div class="col-xs-8 text-right w-66 p-0">
                                <a href="{{route('product.create')}}" class="btn btn-sm btn-primary" id="create">Create New</a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body table-responsive">
                        <table class="table table-hover" id="productContainer">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>status</th>
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
    @include('products.templates')

    <script>
        $(document).ready(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let table = new DataTable('#productContainer', {
                deferRender: true,
                scroller: false,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('product.index') }}",
                },
                columnDefs: [
                    {
                        targets: [1,2,3,4],
                        searchable: true,
                    }
                ],
                columns: [
                    { data: 'id', name: 'id' },
                    {
                        data: function (row){
                            return  '<a href="'+ route('product.show' , row.id)+'" data-id="'+ row.id +'">'+ row.title +'</a>';
                        },
                        name: 'title'
                    },
                    { data: 'description', name: 'description' },
                    { data: 'status', name: 'status' , type: 'string' },
                    {
                        data: function (row) {
                            let url = route('product.edit' , row.id );
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


            $(document).on('click', '#deleteProduct', function () {
                let userId = $(this).data('id');

                $.ajax({
                    url: route('product.destroy' , userId),
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
