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
            <canvas id="densityChart" width="600" height="400"></canvas>
        </div>
    </div>
    <script src="../../../../js/bar-chart-login.js"></script>
@endsection

