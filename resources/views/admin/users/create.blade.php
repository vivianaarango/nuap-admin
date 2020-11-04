@extends('brackets/admin-ui::admin.layout.default')

<head>
    <title>Crear Usuario</title>
</head>

@section('body')

    <div class="container-xl">

        <div class="card">

            <admin-user-form
                :action="'{{ url('admin/user-store') }}'"
                :activation="!!'{{ $activation }}'"
                v-cloak
                inline-template>

                <form class="form-horizontal form-create" method="post" @submit.prevent="onSubmit" :action="this.action">

                    <div class="card-header">
                        <i class="fa fa-plus"></i>&nbsp; Nuevo Usuario
                    </div>

                    <div class="card-body">

                        @include('admin.users.components.form-elements')

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

<script>
    function displayOptions()
    {
        let role = document.getElementById('role')
        let commission = document.getElementById('commission-block');
        let discount = document.getElementById('discount-block');

        if (role.value === 'Mayorista') {
            commission.style.display = 'flex';
            discount.style.display = 'flex';
        } else {
            commission.style.display = 'none';
            discount.style.display = 'none';
        }
    }
</script>