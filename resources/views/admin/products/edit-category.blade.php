@extends('brackets/admin-ui::admin.layout.default')

<head>
    <title>Editar Categoría</title>
    <link rel="icon" href="{{URL::asset('images/nuap.png')}}"/>
</head>

@section('body')
    <div class="container-xl">
        <div class="card">
            <form id="form-basic" method="post" enctype="multipart/form-data" action="{{ $url }}">
                <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                <input type="hidden" name="category_id" id="category_id" value="{{ $product['category_id'] }}" />
                <form class="form-horizontal form-create">
                    <div class="card-header">
                        <i class="fa fa-plus"></i>&nbsp; Editar Categoría
                    </div>

                    <div class="card-body">
                        @include('admin.products.components.form-edit-category-elements')
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
