@extends('brackets/admin-ui::admin.layout.default')
<head>
    <title>Reporte de Ventas</title>
    <link rel="icon" href="{{URL::asset('images/nuap.png')}}"/>
</head>

@section('body')
    <div class="container-xl">
        <div class="card">
            <form id="form-basic" method="post" enctype="multipart/form-data" action="{{ url('admin/report/export-all-sales') }}">
                <div class="card-header">
                    <i class="fa fa-plus"></i>&nbsp; Reporte de Ventas
                </div>
                <div class="card-body">
                    <div class="form-group row align-items-center" :class="{'has-danger': errors.has('init_date'), 'has-success': this.fields.init_date && this.fields.init_date.valid }">
                        <label for="init_date" class="col-form-label text-md-right" :class="'col-md-3'">Fecha Inicial</label>
                        <div :class="'col-md-4 col-md-9 col-xl-7'">
                            <input required type="date" class="form-control" id="init_date" name="init_date" placeholder="Fecha Inicial">
                            <div v-if="errors.has('init_date')" class="form-control-feedback form-text" v-cloak>@{{errors.first('init_date') }}</div>
                        </div>
                    </div>
                    <div class="form-group row align-items-center" :class="{'has-danger': errors.has('finish_date'), 'has-success': this.fields.finish_date && this.fields.finish_date.valid }">
                        <label for="finish_date" class="col-form-label text-md-right" :class="'col-md-3'">Fecha Final</label>
                        <div :class="'col-md-4 col-md-9 col-xl-7'">
                            <input required type="date" class="form-control" id="finish_date" name="finish_date" placeholder="Fecha Final">
                            <div v-if="errors.has('finish_date')" class="form-control-feedback form-text" v-cloak>@{{errors.first('finish_date') }}</div>
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

