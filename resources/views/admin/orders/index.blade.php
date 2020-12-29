@extends('brackets/admin-ui::admin.layout.default')

<head>
    <title>Ventas</title>
    <link rel="icon" href="{{URL::asset('images/nuap.png')}}"/>
</head>

@section('body')
    <admin-user-listing
            :data="{{ $data->toJson() }}"
            :activation="!!'{{ $activation }}'"
            :url="'{{ url('admin/order-list') }}'"
            inline-template>

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i>  Ventas
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
                                <th is='sortable' :column="'total_products'">Número de Productos</th>
                                <th is='sortable' :column="'total_amount'">Total Productos</th>
                                <th is='sortable' :column="'delivery_amount'">Costo de Envío</th>
                                <th is='sortable' :column="'total_discount'">Descuento</th>
                                <th is='sortable' :column="'total'">Total</th>
                                <th is='sortable' :column="'status'"><div class="col text-center">Estado</div></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(item, index) in collection">
                                <td >@{{ item.id }}</td>
                                <td >@{{ item.total_products }} unidades</td>
                                <td >@{{ item.total_amount }}</td>
                                <td >@{{ item.delivery_amount }}</td>
                                <td >@{{ item.total_discount }}</td>
                                <td >@{{ item.total }}</td>
                                <td v-if="item.status === 'Cancelado'">
                                    <div class="col text-center"><button disabled style="color: white" class="btn btn-sm btn-danger"><i></i>&nbspCancelado</button></div>
                                </td>
                                <td v-if="item.status === 'Iniciado'">
                                    <div class="col text-center"><button disabled style="color: white" class="btn btn-sm btn-status-cancel"><i></i>&nbspIniciado</button></div>
                                </td>
                                <td v-if="item.status === 'Aceptado'">
                                    <div class="col text-center"><button disabled style="color: white" class="btn btn-sm btn-success"><i></i>&nbspAceptado</button></div>
                                </td>
                                <td v-if="item.status === 'Alistamiento'">
                                    <div class="col text-center"><button disabled style="color: white" class="btn btn-sm btn-status-cancel"><i></i>&nbspAlistamiento</button></div>
                                </td>
                                <td v-if="item.status === 'Circulación'">
                                    <div class="col text-center"><button disabled style="color: white" class="btn btn-sm btn-status-cancel"><i></i>&nbspCirculación</button></div>
                                </td>
                                <td v-if="item.status === 'Entregado'">
                                    <div class="col text-center"><button disabled style="color: white" class="btn btn-sm btn-success"><i></i>&nbspEntregado</button></div>
                                </td>
                                <td>
                                    <div class="row no-gutters">
                                        <div class="row no-gutters">
                                            <div class="col-auto">
                                                <a class="btn btn-sm btn-spinner btn-info" :href="item.resource_url+'/view'" title="Ver pedido" role="button"><i class="fa fa-eye"></i></a>
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
@endsection
