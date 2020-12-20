@extends('brackets/admin-ui::admin.layout.default')

<head>
    <title>Solicitar pago</title>
    <link rel="icon" href="{{URL::asset('images/nuap.png')}}"/>
</head>

@section('body')
    <div class="container-xl">
        <div class="card">
            <form id="form-basic" method="post" enctype="multipart/form-data" action="{{ url('admin/payment-store') }}">
                <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                <form class="form-horizontal form-create">
                    <div class="card-header">
                        <i class="fa fa-plus"></i>&nbsp; Solicitar pago
                    </div>

                    <div class="card-body">
                        @include('admin.payments.components.form-create-elements')
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-download"></i>
                            &nbsp; Solicitar
                        </button>
                    </div>
                </form>
            </form>
        </div>
    </div>
@endsection
