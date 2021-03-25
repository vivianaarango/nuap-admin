@extends('brackets/admin-ui::admin.layout.default')
<head>
    <title>Reporte de Ventas</title>
    <link rel="icon" href="{{URL::asset('images/nuap.png')}}"/>
</head>

@section('body')
    <div class="container-xl">
        <div class="card">
            <form id="form-basic" method="post" enctype="multipart/form-data" action="{{ url('admin/report/export-sales') }}">
                <div class="card-header">
                    <i class="fa fa-plus"></i>&nbsp; Reporte de Ventas
                </div>
                <div class="card-body">
                    <div class="form-group row align-items-center" :class="{'has-danger': errors.has('user_type'), 'has-success': this.fields.user_type && this.fields.user_type.valid }">
                        <label for="user_id" class="col-form-label text-md-right" :class="'col-md-3'">Tipo de Usuario</label>
                        <div :class="'col-md-4 col-md-9 col-xl-7'">
                            <select class="form-control" id="user_type" name="user_type" required>
                                <option value="Distribuidor">Distribuidor</option>
                                <option value="Comercio">Comercio</option>
                            </select>
                            <div v-if="errors.has('user_type')" class="form-control-feedback form-text" v-cloak>@{{errors.first('user_type') }}</div>
                        </div>
                    </div>
                    <div class="form-group row align-items-center" :class="{'has-danger': errors.has('month'), 'has-success': this.fields.month && this.fields.month.valid }">
                        <label for="month" class="col-form-label text-md-right" :class="'col-md-3'">Mes</label>
                        <div :class="'col-md-4 col-md-9 col-xl-7'">
                            <select class="form-control" id="month" name="month" required>
                                <option value="01">Enero</option>
                                <option value="02">Febrero</option>
                                <option value="03">Marzo</option>
                                <option value="04">Abril</option>
                                <option value="05">Mayo</option>
                                <option value="06">Junio</option>
                                <option value="07">Julio</option>
                                <option value="08">Agosto</option>
                                <option value="09">Septiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>
                            <div v-if="errors.has('month')" class="form-control-feedback form-text" v-cloak>@{{errors.first('month') }}</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                    <button type="submit" class="text-white btn btn-success btn-sm pull-right m-b-0 ml-2">
                        <i class="fa fa-file-excel-o"></i>&nbsp;Exportar
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

