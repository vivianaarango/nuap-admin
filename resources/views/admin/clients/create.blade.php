@extends('brackets/admin-ui::admin.layout.default')

<head>
    <title>Crear Cliente</title>
    <link rel="icon" href="{{URL::asset('images/nuap.png')}}"/>
</head>

@section('body')

    <div class="container-xl">
        <div class="card">
            <admin-user-form
                    :action="'{{ url('admin/client-store') }}'"
                    :activation="!!'{{ $activation }}'"
                    v-cloak
                    inline-template>

                <form class="form-horizontal form-create" method="post" @submit.prevent="onSubmit" :action="this.action">
                    <div class="card-header">
                        <i class="fa fa-plus"></i>&nbsp; Nuevo Cliente
                    </div>

                    <div class="card-body">
                        @include('admin.clients.components.form-elements')
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            &nbsp; Guardar
                        </button>
                    </div>
                </form>
            </admin-user-form>
        </div>
    </div>

@endsection