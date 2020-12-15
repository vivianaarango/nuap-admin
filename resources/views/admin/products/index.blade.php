@extends('brackets/admin-ui::admin.layout.default')

<head>
    <title>Inventario</title>
    <link rel="icon" href="{{URL::asset('images/nuap.png')}}"/>
</head>

@section('body')
    <admin-user-listing
            :data="{{ $data->toJson() }}"
            :activation="!!'{{ $activation }}'"
            :url="'{{ url('admin/product-list') }}'"
            inline-template>

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i>  Inventario
                        <a class="btn btn-primary btn-spinner btn-sm pull-right m-b-0" href="{{ url('admin/product-create') }}" role="button"><i class="fa fa-plus"></i>&nbsp; Nuevo producto</a>
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
                                <!--<th is='sortable' :column="'id'">Imagen</th>-->
                                <th is='sortable' :column="'id'">ID</th>
                                <th is='sortable' :column="'name'">Nombre</th>
                                <th is='sortable' :column="'description'">Descripción</th>
                                <th is='sortable' :column="'category'">Categoría</th>
                                <th is='sortable' :column="'brand'">Marca</th>
                                <th is='sortable' :column="'stock'">Inventario</th>
                                <th is='sortable' :column="'purchase_price'">Precio de Compra</th>
                                <th is='sortable' :column="'sale_price'">Precio de Venta</th>
                                <th is='sortable' :column="'status'">Activo</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(item, index) in collection">
                                <!--<img :src="">-->
                                <td >@{{ item.id }}</td>
                                <td >@{{ item.name }}</td>
                                <td >@{{ item.description }}</td>
                                <td >@{{ item.category }}</td>
                                <td >@{{ item.brand }}</td>
                                <td >@{{ item.stock }}</td>
                                <td >@{{ item.purchase_price }}</td>
                                <td >@{{ item.sale_price }}</td>
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
                                <td>
                                    <div class="row no-gutters">
                                        <div class="col-auto">
                                            <a class="btn btn-sm btn-spinner btn-info" :href="item.resource_url+'/edit'" title="Editar" role="button"><i class="fa fa-edit"></i></a>
                                        </div>
                                        <form class="col" @submit.prevent="deleteItem(item.resource_url)">
                                            <button type="submit" class="btn btn-sm btn-danger" title="Eliminar"><i class="fa fa-trash-o"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <div class="no-items-found" v-if="!collection.length > 0">
                            <i class="icon-magnifier"></i>
                            <h3>No se encontraron registros</h3>
                            <p>Intenta cambiando los filtros o agregando uno nuevo</p>
                            <a class="btn btn-primary btn-spinner" href="{{ url('admin/product-create') }}" role="button"><i class="fa fa-plus"></i>&nbspNuevo producto</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </admin-user-listing>
@endsection
