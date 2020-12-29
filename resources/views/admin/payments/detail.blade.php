@extends('brackets/admin-ui::admin.layout.default')

<head>
    <title>Ver Solicitud de Pago</title>
    <link rel="icon" href="{{URL::asset('images/nuap.png')}}"/>
</head>

@section('body')
    <div class="container-xl">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-plus"></i>&nbsp; Solicitud de Pago de {{ $user->business_name }}
            </div>
            @if($payment['status'] === 'Pendiente')
                <form id="form-basic" method="post" enctype="multipart/form-data" action="{{ url('admin/payment/cancel-payment') }}">
                    <div style="padding-top: 0px" class="card-footer pull-right">
                        <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                        <input type="hidden" value="{{ $payment['id'] }}" name="payment_id" id="payment_id">
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fa fa-window-close"></i>
                            &nbsp; Cancelar solicitud
                        </button>
                    </div>
                </form>
            @endif
            <form id="form-basic" method="post" enctype="multipart/form-data" action="{{ url('admin/payment/upload-voucher') }}">
                <form class="form-horizontal form-create">
                    <div class="col">
                        @include('admin.payments.components.form-detail-elements')
                    </div>
                </form>
            </form>
        </div>
    </div>
@endsection
