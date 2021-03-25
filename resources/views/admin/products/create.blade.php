@extends('brackets/admin-ui::admin.layout.default')

<head>
    <title>Crear Producto</title>
    <link rel="icon" href="{{URL::asset('images/nuap.png')}}"/>
</head>

@section('body')
    <div class="container-xl">
        <div class="card">
            <form id="form-basic" method="post" enctype="multipart/form-data" action="{{ url('admin/product-store') }}">
                <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                <form class="form-horizontal form-create">
                    <div class="card-header">
                        <i class="fa fa-plus"></i>&nbsp; Crear Producto
                    </div>

                    <div class="card-body">
                        @include('admin.products.components.form-create-elements')
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

<script>
    function getUserID() {
        let typeUser = document.getElementById('user_type')
        let selectCommerce = document.getElementById('commerce')
        let selectDistributor = document.getElementById('distributor')
        let userType = typeUser.value;

        if (userType === 'Distribuidor') {
            selectCommerce.style.display = 'none';
            selectDistributor.style.display = 'flex';
        }
        if (userType === 'Comercio') {
            selectCommerce.style.display = 'flex';
            selectDistributor.style.display = 'none';
        }
    }
</script>