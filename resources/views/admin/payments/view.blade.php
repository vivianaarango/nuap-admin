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
                <form id="form-basic" method="post" enctype="multipart/form-data" action="{{ url('admin/payment/rejected-payment') }}">
                    <div style="padding-top: 0px" class="card-footer pull-right">
                        <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                        <input type="hidden" value="{{ $payment['id'] }}" name="payment_id" id="payment_id">
                        <button type="submit" class="btn btn-danger">
                            <i class="fa fa-window-close"></i>
                            &nbsp; Rechazar solicitud
                        </button>
                    </div>
                </form>
            @endif
            <form id="form-basic" method="post" enctype="multipart/form-data" action="{{ url('admin/payment/upload-voucher') }}">
                <form class="form-horizontal form-create">
                    <div class="col">
                        @include('admin.payments.components.form-view-elements')
                    </div>
                    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                    <input type="hidden" value="{{ $payment['id'] }}" name="payment_id" id="payment_id">
                    <input type="hidden" value="{{ $phone }}" name="phone" id="phone">
                    <input type="hidden" value="{{ $payment['value'] }}" name="value" id="value">
                    @if($payment['status'] === 'Pendiente')
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-download"></i>
                                &nbsp; Subir Comprobante
                            </button>
                        </div>
                    @endif
                </form>
            </form>
        </div>
    </div>
@endsection
