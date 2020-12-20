@extends('brackets/admin-ui::admin.layout.default')

<head>
    <title>Ver Solicitud de Pago</title>
    <link rel="icon" href="{{URL::asset('images/nuap.png')}}"/>
</head>

@section('body')
    <div class="container-xl">
        <div class="card">
            <form id="form-basic" method="post" enctype="multipart/form-data">
                <form class="form-horizontal form-create">
                    <div class="card-header">
                        <i class="fa fa-plus"></i>&nbsp; Solicitud de Pago de {{ $user->business_name }}
                    </div>

                    <div class="col">
                        @include('admin.payments.components.form-view-elements')
                    </div>
                </form>
            </form>
        </div>
    </div>
@endsection
