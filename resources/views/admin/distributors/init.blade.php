@extends('brackets/admin-ui::admin.layout.default')

<head>
    <title>Distribuidores</title>
    <link rel="icon" href="{{URL::asset('images/nuap.png')}}"/>
</head>

@section('body')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="row">
                    <div class="col-md-6 col-xl-4">
                        <div class="card mb-3 widget-content bg-midnight-bloom">
                            <div class="widget-content-wrapper text-white">
                                <div class="widget-content-left">
                                    <div style="margin-right: 100;" class="widget-heading">Total</div>
                                    <div class="widget-subheading">Hoy</div>
                                </div>
                                <div class="widget-content-right">
                                    <div class="widget-numbers text-white"><span>{{ $today_total }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-4">
                        <div class="card mb-3 widget-content bg-arielle-smile">
                            <div class="widget-content-wrapper text-white">
                                <div class="widget-content-left">
                                    <div style="margin-right: 100;" class="widget-heading">Total</div>
                                    <div class="widget-subheading">Está semana</div>
                                </div>
                                <div class="widget-content-right">
                                    <div class="widget-numbers text-white"><span>{{ $week_total }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-4">
                        <div class="card mb-3 widget-content bg-grow-early">
                            <div class="widget-content-wrapper text-white">
                                <div class="widget-content-left">
                                    <div style="margin-right: 100;" class="widget-heading">Total</div>
                                    <div class="widget-subheading">El último mes</div>
                                </div>
                                <div class="widget-content-right">
                                    <div class="widget-numbers text-white"><span>{{ $month_total }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-xl-none d-lg-block col-md-6 col-xl-4">
                        <div class="card mb-3 widget-content bg-premium-dark">
                            <div class="widget-content-wrapper text-white">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Products Sold</div>
                                    <div class="widget-subheading">Revenue streams</div>
                                </div>
                                <div class="widget-content-right">
                                    <div class="widget-numbers text-warning"><span>$14M</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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

    @media (max-width: 767.98px)
        .mbg-3, body .card.mb-3 {
            margin-bottom: 15px !important;
        }

    .card.mb-3 {
        margin-bottom: 30px !important;
    }

    .widget-content {
        padding: 1rem;
        flex-direction: row;
        align-items: center;
    }

    .widget-content .widget-content-wrapper {
        display: flex;
        flex: 1;
        position: relative;
        align-items: center;
    }

    .widget-content .widget-content-left .widget-heading {
        opacity: .8;
        font-weight: bold;
    }

    .widget-content .widget-content-left .widget-subheading {
        opacity: .5;
    }

    .widget-content .widget-content-right {
        margin-left: auto;
    }

    @media (max-width: 991.98px)
        .widget-content .widget-numbers {
            font-size: 1.6rem;
            line-height: 1;
        }

        .widget-content .widget-numbers {
            font-weight: bold;
            font-size: 1.8rem;
            display: block;
        }

    .text-white {
        color: #fff !important;
    }

    .card {
        box-shadow: 0 0.46875rem 2.1875rem rgb(4 9 20 / 3%), 0 0.9375rem 1.40625rem rgb(4 9 20 / 3%), 0 0.25rem 0.53125rem rgb(4 9 20 / 5%), 0 0.125rem 0.1875rem rgb(4 9 20 / 3%);
        border-width: 0;
        transition: all .2s;
    }

    .bg-midnight-bloom {
        background-image: linear-gradient(
                -20deg
                , #2b5876 0%, #4e4376 100%) !important;
    }

    .card {
        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 1px solid rgba(26,54,126,0.125);
        border-radius: .25rem;
    }

    body {
        margin: 0;
        font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";
        font-size: .88rem;
        font-weight: 400;
        line-height: 1.5;
        color: #495057;
        text-align: left;
        background-color: #fff;
    }

    .card {
        box-shadow: 0 0.46875rem 2.1875rem rgb(4 9 20 / 3%), 0 0.9375rem 1.40625rem rgb(4 9 20 / 3%), 0 0.25rem 0.53125rem rgb(4 9 20 / 5%), 0 0.125rem 0.1875rem rgb(4 9 20 / 3%);
        border-width: 0;
        transition: all .2s;
    }

    .bg-arielle-smile {
        background-image: radial-gradient(circle 248px at center, #16d9e3 0%, #30c7ec 47%, #46aef7 100%) !important;
    }

    .bg-grow-early {
        background-image: linear-gradient(to top, #0ba360 0%, #3cba92 100%) !important;
    }
</style>
