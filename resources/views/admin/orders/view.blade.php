@extends('brackets/admin-ui::admin.layout.default')

<head>
    <title>Pedido</title>
    <link rel="icon" href="{{URL::asset('images/nuap.png')}}"/>
</head>

@section('body')
    <div class="container-xl">
        <bulk-action-form
                v-cloak
                inline-template>
            <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="this.action" novalidate>
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                @include('admin.orders.components.form-view-elements')
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-12 col-xl-5 col-xxl-4">
                        <div class="card">
                            <div class="card-header">
                                <i class="fa fa-pencil"></i> Productos
                            </div>
                            <div id="cont-message" style="width: auto; height: 300px; overflow-y: scroll;">
                                <div class="card-block">
                                    @foreach ($products as $product)
                                        <div class="">
                                            <img src="{{ URL::asset($product->image) }}" alt="nuap" class="img-responsive center-block align-content-center" width="50%" style="margin-left: 25%">
                                        </div>
                                        <div class="row">
                                            <label for="name" class="col-form-label text-md-right" :class="'col-md-3'"><b>Nombre:</b></label>
                                            <div :class="'col-md-3 col-md-3'">
                                                <label for="name" class="col-form-label text-md-right">{{ $product->name }}</label>
                                            </div>
                                            <label for="brand" class="col-form-label text-md-right" :class="'col-md-3'"><b>Marca:</b></label>
                                            <div :class="'col-md-3 col-md-3'">
                                                <label for="brand" class="col-form-label text-md-right">{{ $product->brand }}</label>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <label for="quantity" class="col-form-label text-md-right" :class="'col-md-3'"><b>Cantidad:</b></label>
                                            <div :class="'col-md-3 col-md-3'">
                                                <label for="quantity" class="col-form-label text-md-right">{{ $product->quantity }} unidades</label>
                                            </div>
                                            <label for="price" class="col-form-label text-md-right" :class="'col-md-3'"><b>Precio:</b></label>
                                            <div :class="'col-md-3 col-md-3'">
                                                <label for="price" class="col-form-label text-md-right">{{ $product->price }}</label>
                                            </div>
                                        </div>
                                        <hr>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </bulk-action-form>
        @if($status != null)
            <form id="form-basic" method="post" enctype="multipart/form-data" action="{{ url('admin/order/change-status') }}">
                <div style="padding-top: 0px" class="card-footer pull-right">
                    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                    <input type="hidden" value="{{ $order['id'] }}" name="order_id" id="order_id">
                    <button style="color: white" type="submit" class="btn btn-primary">
                        <i class="fa fa-refresh"></i>
                        &nbsp; Cambiar a {{ $status }}
                    </button>
                </div>
            </form>
        @endif
        @if($status != null && $status != 'Entregado')
            <form id="form-deliver" method="post" enctype="multipart/form-data" action="{{ url('admin/order/deliver-date') }}">
                <div style="padding-top: 0px" class="card-footer pull-right">
                    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                    <input type="hidden" value="{{ $order['id'] }}" name="order_id" id="order_id">
                    <div class="input-group">
                        <input value='{{ $order['delivery_date'] }}' required min="date()" class="form-control" id="deliver_date" name="deliver_date" placeholder="Estimado de entrega" type="datetime-local" />
                        <span class="input-group-append">
                            @if($order['delivery_date'] != null && $status != 'Entregado')
                                <button style="color: white" type="submit" class="btn btn-success"><i class="fa fa-calendar"></i>&nbsp; Actualizar</button>
                            @else
                                <button style="color: white" type="submit" class="btn btn-success"><i class="fa fa-calendar"></i>&nbsp; Agregar</button>
                            @endif
                        </span>
                    </div>
                </div>
            </form>
        @endif
        <!--@if( $order['status'] === 'Iniciado')
            <form id="form-basic" method="post" enctype="multipart/form-data" action="{{ url('admin/order/change-status') }}">
                <div style="padding-top: 0px" class="card-footer pull-right">
                    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                    <input type="hidden" value="{{ $order['id'] }}" name="order_id" id="order_id">
                    <button style="color: white" type="submit" class="btn btn-sm btn-danger">
                        <i class="fa fa-remove"></i>
                        &nbsp; Cancelar
                    </button>
                </div>
            </form>
        @endif-->
    </div>
@endsection
