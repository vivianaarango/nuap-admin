@extends('brackets/admin-ui::admin.layout.default')

<head>
    <title>Editar Usuario</title>
</head>

@section('body')

    <div class="container-xl">

        <div class="card">

            <admin-user-form
                :action="'{{ $user->resource_url }}'"
                :data="{{ $user->toJson() }}"
                :activation="!!'{{ $activation }}'"
                :showFields="'{{ $showFields }}'"
                v-cloak
                inline-template>
            
                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="this.action">

                    <div class="card-header">
                        <i class="fa fa-pencil"></i> Editando {{ $user->name .' '. $user->lastname }}
                    </div>

                    <div class="card-body">

                        @include('admin.users.components.form-edit-elements')

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
