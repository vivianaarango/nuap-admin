@extends('brackets/admin-ui::admin.layout.default')
<head>
    <title>Reporte de Tickets</title>
    <link rel="icon" href="{{URL::asset('images/nuap.png')}}"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.js" integrity="sha512-zO8oeHCxetPn1Hd9PdDleg5Tw1bAaP0YmNvPY8CwcRyUk7d7/+nyElmFrB6f7vg4f7Fv4sui1mcep8RIEShczg==" crossorigin="anonymous"></script>
</head>

@section('body')
    <div class="container-xl">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-plus"></i>&nbsp; Reporte de Tickets
            </div>
            <canvas id="pie-chart" style="width: 200px !important;"></canvas>
        </div>
        <div class="row">
            <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="panel panel-primary text-center no-boder bg-color-green">
                    <div class="panel-left pull-left green">
                        <i class="fa fa-close fa-5x"></i>
                    </div>
                    <div class="panel-right">
                        <h3>{{ $closed }}</h3>
                        <strong>Cerrado</strong>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="panel panel-primary text-center no-boder bg-color-blue">
                    <div class="panel-left pull-left blue">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="panel-right ">
                        <h3>{{ $pending_admin }}</h3>
                        <strong>Pendiente Administrador</strong>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="panel panel-primary text-center no-boder bg-color-brown">
                    <div class="panel-left pull-left brown">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="panel-right">
                        <h3>{{ $pending_user }}</h3>
                        <strong>Pendiente Usuario</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../../../../js/pie-chart.js"></script>
@endsection

<style>
    .green {
        color: #d42d3f;
    }

    .blue {
        color: #18a7ba;
    }

    .brown {
        color: #e7da0e;
    }
</style>