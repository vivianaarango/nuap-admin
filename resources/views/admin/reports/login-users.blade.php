@extends('brackets/admin-ui::admin.layout.default')
<head>
    <title>Reporte de Inicio de Sesión</title>
    <link rel="icon" href="{{URL::asset('images/nuap.png')}}"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.js" integrity="sha512-zO8oeHCxetPn1Hd9PdDleg5Tw1bAaP0YmNvPY8CwcRyUk7d7/+nyElmFrB6f7vg4f7Fv4sui1mcep8RIEShczg==" crossorigin="anonymous"></script>
</head>

@section('body')
    <div class="container-xl">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-plus"></i>&nbsp; Reporte de Inicio de Sesión
            </div>
            <canvas id="densityChart" style="width: 20% !important;"></canvas>
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-12 col-xs-12">
                <div class="panel panel-primary text-center no-boder bg-color-green">
                    <div class="panel-left pull-left green">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="panel-right">
                        <h3>{{ $distributor }}</h3>
                        <strong>Distribuidores</strong>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-12 col-xs-12">
                <div class="panel panel-primary text-center no-boder bg-color-blue">
                    <div class="panel-left pull-left blue">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="panel-right ">
                        <h3>{{ $commerce }}</h3>
                        <strong>Comercios</strong>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-12 col-xs-12">
                <div class="panel panel-primary text-center no-boder bg-color-red">
                    <div class="panel-left pull-left red">
                        <i class="fa fa fa-user fa-5x"></i>
                    </div>
                    <div class="panel-right">
                        <h3>{{ $client }}</h3>
                        <strong>Clientes</strong>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-12 col-xs-12">
                <div class="panel panel-primary text-center no-boder bg-color-brown">
                    <div class="panel-left pull-left brown">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="panel-right">
                        <h3>{{ $admin }}</h3>
                        <strong>Administradores</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../../../../js/bar-chart-login.js"></script>
@endsection

<style>
    .green {
        color: #1882d0;
    }

    .blue {
        color: #39be28;
    }

    .brown {
        color: #b8233c;
    }

    .red {
        color: #eec50b;
    }
</style>
