@extends('brackets/admin-ui::admin.layout.default')

<head>
    <title>Cuenta Bancaria</title>
    <link rel="icon" href="{{URL::asset('images/nuap.png')}}"/>
</head>

@section('body')
    <div class="container-xl">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-plus"></i>&nbsp; Cuenta Bancaria
            </div>

            <form id="form-basic" method="post" enctype="multipart/form-data" action="{{ url('admin/payment/change-status-account') }}">
                <div style="padding-top: 0px" class="card-footer pull-right">
                    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                    <input type="hidden" value="{{ $account['id'] }}" name="account_id" id="account_id">
                    <button style="color: white" type="submit" class="btn btn-sm btn-primary">
                        <i class="fa fa-refresh"></i>
                        &nbsp; Cambiar estado
                    </button>
                </div>
            </form>

            <form class="form-horizontal form-create">
                <div class="col">
                    @include('admin.payments.components.form-view-account-elements')
                </div>
            </form>
        </div>
    </div>
@endsection
