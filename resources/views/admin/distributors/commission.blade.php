@extends('brackets/admin-ui::admin.layout.default')

<head>
    <title>Actualizar comisión</title>
    <link rel="icon" href="{{URL::asset('images/nuap.png')}}"/>
</head>

@section('body')

    <bulk-action-listing
        :data="{{ $data->toJson() }}"
        :url="'{{ url('admin/distributor-edit-commission') }}'"
        :trans="{{ json_encode(trans('brackets/admin-ui::admin.dialogs')) }}"
        inline-template>

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Actualizar comisión
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
                                    <th is='sortable' :column="'business_name'">Razón Social</th>
                                    <th is='sortable' :column="'name_legal_representative'">Representante Legal</th>
                                    <th is='sortable' :column="'commission'">Comisión</th>
                                    <th></th>
                                </tr>
                                <tr v-show="(clickedBulkItemsCount > 0) || isClickedAll">
                                    <td class="bg-bulk-info d-table-cell text-center" colspan="6">
                                        <span class="align-middle font-weight-light text-dark">Distribuidores seleccionados @{{ clickedBulkItemsCount }}.  <a href="#" class="text-primary" @click="onBulkItemsClickedAll('/admin/distributor-edit-commission')" v-if="(clickedBulkItemsCount < pagination.state.total)"> <i class="fa" :class="bulkCheckingAllLoader ? 'fa-spinner' : ''"></i> Seleccionar todo</a> <span class="text-primary">|</span> <a
                                                    href="#" class="text-primary" @click="onBulkItemsClickedAllUncheck()">Deseleccionar todo</a>  </span>

                                        <span class="pull-right pr-2">
                                            <input min="0" max="100" type="number" step="0.1" id="commission" name="commission" v-model="commission">
                                            <button style="color: white" class="btn btn-sm btn-success pr-3 pl-3" @click="bulkDelete('/admin/distributor-update-commission')">Actualizar comisión</button>
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
                                    <td>@{{ item.business_name }}</td>
                                    <td>@{{ item.name_legal_representative }}</td>
                                    <td>@{{ item.commission }}%</td>
                                    <!--<td>
                                        <div class="row no-gutters">
                                            <div class="col-auto">
                                                <a class="btn btn-sm btn-spinner btn-info" :href="item.resource_url + '/edit'" title="{{ trans('brackets/admin-ui::admin.btn.edit') }}" role="button"><i class="fa fa-edit"></i></a>
                                            </div>
                                            <form class="col" @submit.prevent="deleteItem(item.resource_url)">
                                                <button type="submit" class="btn btn-sm btn-danger" title="{{ trans('brackets/admin-ui::admin.btn.delete') }}"><i class="fa fa-trash-o"></i></button>
                                            </form>
                                        </div>
                                    </td>-->
                                </tr>
                            </tbody>
                        </table>

                        <div class="no-items-found" v-if="!collection.length > 0">
                            <i class="icon-magnifier"></i>
                            <h3>No se encontraron registros</h3>
                            <p>Intenta cambiando los filtros o agregando uno nuevo</p>
                            <a class="btn btn-primary btn-spinner" href="{{ url('admin/distributor-create') }}" role="button"><i class="fa fa-plus"></i>&nbspNuevo distribuidor</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </bulk-action-listing>

@endsection