@extends('brackets/admin-ui::admin.layout.default')

<head>
    <title>Clientes</title>
    <link rel="icon" href="{{URL::asset('images/nuap.png')}}"/>
</head>

@section('body')
    <admin-user-listing
        :data="{{ $data->toJson() }}"
        :activation="!!'{{ $activation }}'"
        :url="'{{ url('admin/client-list') }}'"
        :days="'{{ $days }}'"
        inline-template>

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i>  Clientes
                        <a style="color: white" class="btn btn-success btn-sm pull-right m-b-0 ml-2" href="{{ url('admin/client/export') }}" role="button"><i class="fa fa-file-excel-o"></i>&nbsp;Exportar</a>
                        <a class="btn btn-primary btn-spinner btn-sm pull-right m-b-0" href="{{ url('admin/client-create') }}" role="button"><i class="fa fa-plus"></i>&nbsp; Nuevo cliente</a>
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
                                    <th is='sortable' :column="'user_id'">ID</th>
                                    <th is='sortable' :column="'name'">Nombre</th>
                                    <th is='sortable' :column="'last_name'">Apellido</th>
                                    <th is='sortable' :column="'identity_number'">Número de identificación</th>
                                    <th is='sortable' :column="'activated'">Activo</th>
                                    <th is='sortable' :column="'last_logged_in'">Última sesión</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in collection">
                                    <td >@{{ item.user_id }}</td>
                                    <td >@{{ item.name }}</td>
                                    <td >@{{ item.last_name }}</td>
                                    <td >@{{ item.identity_number }}</td>
                                    <td v-if="item.status === 1">
                                        <label class="switch switch-3d switch-success">
                                            <input type="checkbox" class="switch-input" v-model="collection[index].status" @change="toggleSwitch(item.resource_url+'/status', 'status', collection[index])">
                                            <span class="switch-slider"></span>
                                        </label>
                                    </td>
                                    <td v-if="item.status === 0">
                                        <label class="switch switch-3d switch-danger">
                                            <input type="checkbox" class="switch-input" v-model="collection[index].status" @change="toggleSwitch(item.resource_url+'/status', 'status', collection[index])">
                                            <span class="switch-slider"></span>
                                        </label>
                                    </td>
                                    <td >@{{ item.last_logged_in }}</td>
                                    <td>
                                        <div class="row no-gutters">
                                            <div class="col-auto">
                                                <a style="background-color: #d90909"  class="btn btn-sm btn-spinner btn-success" :href="item.resource_url+'/add-location'" title="Agregar ubicación" role="button"><i class="fa fa-map-marker"></i></a>
                                            </div>
                                            <div class="col-auto">
                                                <a class="btn btn-sm btn-spinner btn-info" :href="item.resource_url+'/edit'" title="Editar" role="button"><i class="fa fa-edit"></i></a>
                                            </div>
                                            <!--<form class="col" @submit.prevent="deleteItem(item.resource_url)">
                                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar"><i class="fa fa-trash-o"></i></button>
                                            </form>-->
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
                            <p>Intenta cambiando los filtros o agregando uno nuevo</p>
                            <a class="btn btn-primary btn-spinner" href="{{ url('admin/client-create') }}" role="button"><i class="fa fa-plus"></i>&nbspNuevo cliente</a>
	                    </div>
                        <form method="get" :action="this.url">
                            <div class="col col-lg-6 col-xl-4 form-group float-right">
                                <div class="input-group">
                                    <input type="number" value="{{$days}}" name="days" id="days" class="form-control" placeholder="Días sin iniciar sesión"/>
                                    <span class="input-group-append">
                                        <button type="submit" class="btn btn-success"><i class="fa fa-search"></i></button>
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </admin-user-listing>

@endsection
