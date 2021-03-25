@extends('brackets/admin-ui::admin.layout.default')

<head>
    <title>Distribuidores</title>
    <link rel="icon" href="{{URL::asset('images/nuap.png')}}"/>
</head>

@section('body')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="form-group row align-items-center">
                        <img src="{{URL::asset('images/banner.jpeg')}}" alt="nuap" class="img-responsive" width="100%">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <div class="panel panel-primary text-center no-boder bg-color-green">
                            <div class="panel-left pull-left green">
                                <a href="{{ url('admin/payment-list') }}"><i class="fa fa-dollar fa-5x"></i></a>
                            </div>
                            <div class="panel-right pull-right">
                                <h3>{{ $pending_payments }}</h3>
                                <a style="color: black" href="{{ url('admin/payment-list') }}"><strong>Pagos Pendientes</strong></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <div class="panel panel-primary text-center no-boder bg-color-blue">
                            <div class="panel-left pull-left blue">
                                <a href="{{ url('admin/order-list') }}"><i class="fa fa-shopping-cart fa-5x"></i></a>
                            </div>
                            <div class="panel-right pull-right">
                                <h3>{{ $order_process }}</h3>
                                <a style="color: black" href="{{ url('admin/order-list') }}"><strong>Pedidos en Proceso</strong></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <div class="panel panel-primary text-center no-boder bg-color-red">
                            <div class="panel-left pull-left red">
                                <a href="{{ url('admin/ticket-list') }}"><i class="fa fa fa-comments fa-5x"></i></a>
                            </div>
                            <div class="panel-right pull-right">
                                <h3>{{ $open_tickets }}</h3>
                                <a style="color: black" href="{{ url('admin/ticket-list') }}"><strong>Tickets Pendientes</strong></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <div class="panel panel-primary text-center no-boder bg-color-brown">
                            <div class="panel-left pull-left brown">
                                <a href="{{ url('admin/product-distributor-list') }}"><i class="fa fa-shopping-bag fa-5x"></i></a>
                            </div>
                            <div class="panel-right pull-right">
                                <h3>{{ $approved_products }}</h3>
                                <a style="color: black" href="{{ url('admin/product-distributor-list') }}"><strong>Productos Aprobados</strong></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
    .text-center {
        text-align: center;
    }
    .panel-primary {
        border-color: #428bca;
    }
    .panel {
        margin-bottom: 20px;
        background-color: #fff;
        border: 1px solid transparent;
        border-radius: 4px;
        -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
    }

    .panel-left {
        width: 35%;
        height: 158px;
        background: #5CB85C;
    }

    .panel-left .fa-5x {
        /* font-size: 5em; */
        color: #fff;
        padding: 40px 0;
        margin-bottom: 30px;
    }

    .green {
        background-color: #5cb85c;
        color: #fff;
    }

    .green:hover {
        background-color: #2FA22F;
        color: #fff;
    }

    .blue {
        background-color: #4CB1CF;
        color: #fff;
    }

    .blue:hover {
        background-color: #0489B1;
        color: #fff;
    }

    .brown {
        background-color: #f0ad4e;
        color: #fff;
    }

    .brown:hover {
        background-color: #C67606;
        color: #fff;
    }

    .red {
        background-color: #F0433D;
        color: #fff;
    }

    .red:hover {
        background-color: #BC0802;
        color: #fff;
    }

    .text-center {
        text-align: center;
    }

    body {
        font-family: 'Open Sans', sans-serif;
    }

    .panel-right h3 {
        font-size: 50px;
        padding: 31px 10px 13px;
    }

    h1, .h1, h2, .h2, h3, .h3 {
        margin-top: 7px;
        margin-bottom: -5px;
    }

    .panel-right {
        width: 65%;
        height: 158px;
        background: #F7F7F7;
        margin-bottom: 30px;
    }

    body {
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: 14px;
        line-height: 1.42857143;
        color: #333;
        background-color: #fff;
    }
</style>