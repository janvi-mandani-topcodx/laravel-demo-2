@extends('layout')
@section('contents')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col col-12 text-center">
                                <h2 class="">Permissions</h2>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end my-3">
                            <div class="col-xs-8 text-right w-66 p-0">
                                @if(auth()->user()->hasPermissionTo('create_permission'))
                                    <a href="{{route('permission.create')}}" class="btn btn-sm btn-primary" id="createPermission">Create New</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="panel-body table-responsive">
                        <table class="table table-hover" id="PermissionController">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                @if(auth()->user()->hasPermissionTo('update_permission') || auth()->user()->hasPermissionTo('delete_permission'))
                                    <th>Actions</th>
                                @endif
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
    @include('permissions.templates')
@endsection
