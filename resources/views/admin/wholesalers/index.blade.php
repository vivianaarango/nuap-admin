@extends('brackets/admin-ui::admin.layout.default')

<head>
    <title>Mayoristas</title>
</head>

@section('body')
    <admin-user-listing
        :data="{{ $data->toJson() }}"
        :activation="!!'{{ $activation }}'"
        :url="'{{ url('admin/user-wholesaler-list') }}'"
        :days="'{{ $days }}'"
        inline-template>

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i>  Mayoristas
                        <a class="btn btn-primary btn-spinner btn-sm pull-right m-b-0" href="{{ url('admin/user-create') }}" role="button"><i class="fa fa-plus"></i>&nbsp; Nuevo usuario</a>
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
                                    <th is='sortable' :column="'name'">Nombre</th>
                                    <th is='sortable' :column="'lastname'">Apellido</th>
                                    <th is='sortable' :column="'email'">Correo electrónico</th>
                                    <th is='sortable' :column="'phone'">Teléfono</th>
                                    <th is='sortable' :column="'commission'">Comisión</th>
                                    <th is='sortable' :column="'discount'">Descuento</th>
                                    <th is='sortable' :column="'activated'">Activo</th>
                                    <th is='sortable' :column="'last_logged_in'">Última sesión</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in collection">
                                    <td >@{{ item.id }}</td>
                                    <td >@{{ item.name }}</td>
                                    <td >@{{ item.lastname }}</td>
                                    <td >@{{ item.email }}</td>
                                    <td >@{{ item.phone }}</td>
                                    <td >@{{ item.commission }}</td>
                                    <td >@{{ item.discount }}</td>
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
                                            <!--<div class="col-auto">
                                                <button class="btn btn-sm btn-warning" v-show="!item.activated" @click="resendActivation(item.resource_url + '/resend-activation')" title="Resend activation" role="button"><i class="fa fa-envelope-o"></i></button>
                                            </div>-->
                                            <div class="col-auto">
                                                <a class="btn btn-sm btn-spinner btn-info" :href="item.resource_url + '/edit'" title="Editar" role="button"><i class="fa fa-edit"></i></a>
                                            </div>
                                            <form class="col" @submit.prevent="deleteItem(item.resource_url)">
                                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar"><i class="fa fa-trash-o"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="row" v-if="pagination.state.total > 0">
                            <div class="col-sm">
                                <span class="pagination-caption">{{ trans('brackets/admin-ui::admin.pagination.overview') }}</span>
                            </div>
                            <div class="col-sm-auto">
                                <pagination></pagination>
                            </div>
                        </div>

	                   <div class="no-items-found" v-if="!collection.length > 0">
		                    <i class="icon-magnifier"></i>
                            <h3>No se encontraron registros</h3>
                            <p>Intenta cambiando los filtros o agregando uno nuevo</p>
                            <a class="btn btn-primary btn-spinner" href="{{ url('admin/user-create') }}" role="button"><i class="fa fa-plus"></i>&nbspNuevo usuario</a>
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
