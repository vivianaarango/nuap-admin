@extends('brackets/admin-ui::admin.layout.default')

<head>
    <title>Actualizar descuento</title>
    <link rel="icon" href="{{URL::asset('images/nuap.png')}}"/>
</head>

@section('body')

    <bulk-action-listing
        :data="{{ $data->toJson() }}"
        :url="'{{ url('admin/product-edit-discount') }}'"
        :trans="{{ json_encode(trans('brackets/admin-ui::admin.dialogs')) }}"
        inline-template>

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Actualizar descuento
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
                                    <th class="bulk-checkbox">
                                        <input class="form-check-input" id="enabled" type="checkbox" v-model="isClickedAll" v-validate="''" data-vv-name="enabled"  name="enabled_fake_element" @click="onBulkItemsClickedAllWithPagination()">
                                        <label class="form-check-label" for="enabled">
                                            #
                                        </label>
                                    </th>
                                    <th is='sortable' :column="'id'">ID</th>
                                    <th is='sortable' :column="'name'">Nombre</th>
                                    <th is='sortable' :column="'category_id'">Categoría</th>
                                    <th is='sortable' :column="'brand'">Marca</th>
                                    <th is='sortable' :column="'special_price'">Descuento</th>
                                    <th></th>
                                </tr>
                                <tr v-show="(clickedBulkItemsCount > 0) || isClickedAll">
                                    <td class="bg-bulk-info d-table-cell text-center" colspan="6">
                                        <span class="align-middle font-weight-light text-dark">Productos seleccionados @{{ clickedBulkItemsCount }}.  <a href="#" class="text-primary" @click="onBulkItemsClickedAll('/admin/product-edit-discount')" v-if="(clickedBulkItemsCount < pagination.state.total)"> <i class="fa" :class="bulkCheckingAllLoader ? 'fa-spinner' : ''"></i> Seleccionar todo</a> <span class="text-primary">|</span> <a
                                                    href="#" class="text-primary" @click="onBulkItemsClickedAllUncheck()">Eliminar selección</a>  </span>

                                        <span class="pull-right pr-2">
                                            <input min="0" max="100" type="number" step="0.1" id="commission" name="commission" v-model="commission">
                                            <button style="color: white" class="btn btn-sm btn-success pr-3 pl-3" @click="bulkDelete('/admin/product-update-discount')">Actualizar descuento</button>
                                        </span>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in collection" :key="item.id" :class="bulkItems[item.id] ? 'bg-bulk' : ''">
                                    <td class="bulk-checkbox">
                                        <input class="form-check-input" :id="'enabled' + item.id" type="checkbox" v-model="bulkItems[item.id]" v-validate="''" :data-vv-name="'enabled' + item.id"  :name="'enabled' + item.id + '_fake_element'" @click="onBulkItemClicked(item.id)" :disabled="bulkCheckingAllLoader">
                                        <label class="form-check-label" :for="'enabled' + item.id">
                                        </label>
                                    </td>
                                    <td>@{{ item.id }}</td>
                                    <td>@{{ item.name }}</td>
                                    <td>@{{ item.category_id }}</td>
                                    <td>@{{ item.brand }}</td>
                                    <td>@{{ item.special_price }}%</td>
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
    </bulk-action-listing>

@endsection