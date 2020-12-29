@extends('brackets/admin-ui::admin.layout.default')

<head>
    <title>Solicitudes de Pago</title>
    <link rel="icon" href="{{URL::asset('images/nuap.png')}}"/>
</head>

@section('body')
    <admin-user-listing
            :data="{{ $data->toJson() }}"
            :activation="!!'{{ $activation }}'"
            :url="'{{ url('admin/payment-admin-list') }}'"
            inline-template>

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i>  Solicitudes de Pago
                    </div>
                    <div class="card-body" v-cloak>
                        <form @submit.prevent="">
                            <div class="row justify-content-md-between">
                                <div class="col col-lg-7 col-xl-5 form-group">
                                    <div class="input-group">
                                        <input class="form-control" placeholder="Buscar" v-model="search" @keyup.enter="filter('search', $event.target.value)" />
                                        <span class="input-group-append">
                                            <button type="button" class="btn btn-primary" @click="filter('search', search)"><i class="fa fa-search"></i>&nbsp; Buscar</button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-sm-auto form-group ">
                                    <select class="form-control" v-model="pagination.state.per_page">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>

                            </div>
                        </form>
                        <table class="table table-hover table-listing">
                            <thead>
                            <tr>
                                <th is='sortable' :column="'id'">ID</th>
                                <th is='sortable' :column="'bank'">Banco</th>
                                <th is='sortable' :column="'value'">Valor a pagar</th>
                                <th is='sortable' :column="'request_date'">Fecha de petición</th>
                                <th is='sortable' :column="'payment_date'">Fecha de pago</th>
                                <th is='sortable' :column="'status'"><div class="col text-center">Estado</div></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(item, index) in collection">
                                <td >@{{ item.id }}</td>
                                <td >@{{ item.bank }}</td>
                                <td >@{{ item.value }}</td>
                                <td >@{{ item.request_date }}</td>
                                <td v-if="item.payment_date === null">
                                    No se ha hecho el pago.
                                </td>
                                <td v-if="item.payment_date != null">@{{ item.payment_date }}</td>
                                <td v-if="item.status === 'Cancelado'">
                                    <div class="col text-center"><button disabled style="color: white" class="btn btn-sm btn-status-cancel"><i></i>&nbspCancelado</button></div>
                                </td>
                                <td v-if="item.status === 'Rechazado'">
                                    <div class="col text-center"><button disabled style="color: white" class="btn btn-sm btn-danger"><i></i>&nbspRechazado</button></div>
                                </td>
                                <td v-if="item.status === 'Aprobado'">
                                    <div class="col text-center"><button disabled style="color: white" class="btn btn-sm btn-success"><i></i>&nbspAprobado</button></div>
                                </td>
                                <td v-if="item.status === 'Pendiente'">
                                    <div class="col text-center"><button disabled style="color: white" class="btn btn-sm btn-info"><i></i>&nbspPendiente</button></div>
                                </td>
                                <td>
                                    <div class="row no-gutters">
                                        <div class="row no-gutters">
                                            <div class="col-auto">
                                                <a class="btn btn-sm btn-spinner btn-info" :href="item.resource_url+'/detail'" title="Ver solicitud" role="button"><i class="fa fa-eye"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="row" v-if="pagination.state.total > 0">
                            <div class="col-sm">
                            </div>
                            <div class="col-sm-auto">
                                <pagination></pagination>
                            </div>
                        </div>
                        <div class="no-items-found" v-if="!collection.length > 0">
                            <i class="icon-magnifier"></i>
                            <h3>No se encontraron registros</h3>
                            <p>Intenta cambiando los filtros</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </admin-user-listing>
    <div class="card">
        <div class="card-header">
            <i class="fa fa-align-justify"></i>  Solicitar Retiro
        </div>
        @if($account->status === 1)
            <form id="form-basic" method="post" enctype="multipart/form-data" action="{{ url('admin/payment/request-payment') }}">
                <div style="padding-top: 0px" class="card-footer">
                    <div class="form-group row align-items-center">
                        <label style="color: #2d3093" for="issues" class="col-form-label text-md-right" :class="'col-md-3'"><b>Saldo disponible:</b></label>
                        <div :class="'col-md-4 col-md-7 col-xl-7'">
                            <label id="requested_value" style="color: #2d3093" class="col-form-label text-md-right"><b>{{ $balance->requested_value }}</b></label>
                        </div>
                    </div>
                    <div class="form-group row align-items-center" :class="{'has-danger': errors.has('value'), 'has-success': this.fields.value && this.fields.value.valid }">
                        <label for="value" class="col-form-label text-md-right" :class="'col-md-3'">Valor de solicitud</label>
                        <div :class="'col-md-4 col-md-7 col-xl-7'">
                            <input type="number" max="{{ $balance->requested_value }}" required class="form-control" :class="{'form-control-danger': errors.has('value'), 'form-control-success': this.fields.value && this.fields.value.valid}" id="value" name="value" placeholder="Valor">
                            <div v-if="errors.has('value')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('value') }}</div>
                        </div>
                    </div>
                    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                    <button style="color: white" type="submit" class="btn btn-info center-block">
                        <i class="fa fa-money"></i>
                        &nbsp; Retirar
                    </button>
                </div>
            </form>
        @else
            <label for="note" class="col-form-label" :class="'col-md-3'">Debes tener una cuenta bancaria activa para poder realizar el retiro, por favor agregala desde tu perfil.</label>
        @endif
    </div>
@endsection
