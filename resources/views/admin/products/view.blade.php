@extends('brackets/admin-ui::admin.layout.default')

<head>
    <title>Ver Producto</title>
    <link rel="icon" href="{{URL::asset('images/nuap.png')}}"/>
</head>

@section('body')
    <div class="container-xl">
        <div class="card">
            <form id="form-basic" method="post" enctype="multipart/form-data">
                <form class="form-horizontal form-create">
                    <div class="card-header">
                        <i class="fa fa-plus"></i>&nbsp; Ver Producto
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-xl-5 col-xxl-4">
                            <img src="../../../{{ $product['image'] }}" alt="nuap" class="img-responsive" height="380px" width="380px">
                        </div>
                        <div class="col">
                            @include('admin.products.components.form-view-elements')
                        </div>
                    </div>
                </form>
            </form>
        </div>
    </div>
@endsection
