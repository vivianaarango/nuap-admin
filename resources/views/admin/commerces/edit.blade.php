@extends('brackets/admin-ui::admin.layout.default')

<head>
    <title>Editar Comercio</title>
    <link rel="icon" href="{{URL::asset('images/nuap.png')}}"/>
</head>

@section('body')

    <div class="container-xl">
        <div class="card">
            <admin-user-form
                :action="'{{ $url }}'"
                :data="{{ $user }}"
                :activation="!!'{{ $activation }}'"
                :business_name="'{{ $business_name }}'"
                v-cloak
                inline-template>
            
                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="this.action">

                    <div class="card-header">
                        <i class="fa fa-pencil"></i> Editando {{ $business_name }}
                    </div>

                    <div class="card-body">
                        @include('admin.commerces.components.form-edit-elements')
                    </div>

                    <div class="card-footer">
	                    <button type="submit" class="btn btn-primary" :disabled="submiting">
		                    <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            Guardar
	                    </button>
                    </div>
                </form>

            </admin-user-form>
        </div>
    </div>
@endsection
