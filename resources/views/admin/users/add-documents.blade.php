@extends('brackets/admin-ui::admin.layout.default')

<head>
    <title>Agregar Documentos</title>
    <link rel="icon" href="{{URL::asset('images/nuap.png')}}"/>
</head>

@section('body')
    <div class="container-xl">
        <div class="card">
            <form id="form-basic" method="post" enctype="multipart/form-data" action="{{ url('admin/users/store-documents') }}">
                <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                <form class="form-horizontal form-create">
                    <input type="hidden" id="user_id" name="user_id" value="{{ $user->id }}">
                    <input type="hidden" id="role" name="role" value="{{ $user->role }}">
                    <input type="hidden" id="phone" name="phone" value="{{ $user->phone }}">
                    <div class="card-header">
                        <i class="fa fa-plus"></i>&nbsp; Agregar Documentos
                    </div>

                    <div class="card-body">
                        @include('admin.users.components.form-add-documents-elements')
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-download"></i>
                            &nbsp; Guardar
                        </button>
                    </div>
                </form>
            </form>
        </div>
    </div>
@endsection