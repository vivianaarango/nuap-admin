@extends('brackets/admin-ui::admin.layout.default')

<head>
    <title>Tickets</title>
    <link rel="icon" href="{{URL::asset('images/nuap.png')}}"/>
</head>

@section('body')
    <admin-user-listing
            :data="{{ $data->toJson() }}"
            :activation="!!'{{ $activation }}'"
            :url="'{{ url('admin/ticket-list') }}'"
            inline-template>

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i>  Tickets
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
                                <th is='sortable' :column="'name'">Asunto</th>
                                <th is='sortable' :column="'email'">Razón Social</th>
                                <th is='sortable' :column="'description'">Descripción</th>
                                <th is='sortable' :column="'updated_at'">Última respuesta</th>
                                <th is='sortable' :column="'init_date'">Fecha de creación</th>
                                <th is='sortable' :column="'is_closed'">Estado</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(item, index) in collection">
                                <td >@{{ item.id }}</td>
                                <td >@{{ item.issues }}</td>
                                <td >@{{ item.email }}</td>
                                <td >@{{ item.description }}</td>
                                <td >@{{ item.updated_at }}</td>
                                <td >@{{ item.init_date }}</td>
                                <td v-if="item.is_closed === 0">
                                    <button disabled style="color: white" class="btn btn-sm btn-success"><i class="fa fa-send"></i>&nbsp;&nbspAbierto</button>
                                </td>
                                <td v-if="item.is_closed === 1">
                                    <button disabled style="color: white" class="btn btn-sm btn-danger"><i class="fa fa-lock"></i>&nbsp;&nbspCerrado</button>
                                </td>
                                <td>
                                    <div class="row no-gutters">
                                        <div class="col-auto">
                                            <a class="btn btn-sm btn-spinner btn-info" :href="item.resource_url+'/view'" title="Ver ticket" role="button"><i class="fa fa-commenting"></i></a>
                                        </div>
                                        <form v-if="item.is_closed === 0" class="col" @submit.prevent="closeTicket(item.resource_url)">
                                            <button type="submit" class="btn btn-sm btn-danger" title="Cerrar Ticket"><i class="fa fa-lock"></i></button>
                                        </form>
                                        <form v-if="item.is_closed === 1" class="col" @submit.prevent="closeTicket(item.resource_url)">
                                            <button disabled type="submit" class="btn btn-sm btn-danger" title="Cerrar Ticket"><i class="fa fa-lock"></i></button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>

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
