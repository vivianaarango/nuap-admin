<div class="card-header">
    <i class="fa fa-user"></i> {{ $order['client_type'] }}
</div>
<div class="form-group row align-items-center">
    <label for="email" class="col-form-label text-md-right" :class="'col-md-3'"><b>Correo Electrónico:</b></label>
    <div :class="'col-md-3 col-md-3'">
        <label for="email" class="col-form-label text-md-right">{{ $user['email'] }}</label>
    </div>
    <label for="phone" class="col-form-label text-md-right" :class="'col-md-3'"><b>Teléfono:</b></label>
    <div :class="'col-md-3 col-md-3'">
        <label for="phone" class="col-form-label text-md-right">{{ $user['phone'] }}</label>
    </div>
</div>
@if($order['client_type'] === 'Usuario')
    <div class="form-group row align-items-center">
        <label for="name" class="col-form-label text-md-right" :class="'col-md-3'"><b>Nombre:</b></label>
        <div :class="'col-md-3 col-md-3'">
            <label for="name" class="col-form-label text-md-right">{{ $client['name'] }}</label>
        </div>
        <label for="last_name" class="col-form-label text-md-right" :class="'col-md-3'"><b>Apellido:</b></label>
        <div :class="'col-md-3 col-md-3'">
            <label for="last_name" class="col-form-label text-md-right">{{ $client['last_name'] }}</label>
        </div>
    </div>
@else
    <div class="form-group row align-items-center">
        <label for="business_name" class="col-form-label text-md-right" :class="'col-md-3'"><b>Razón Social:</b></label>
        <div :class="'col-md-3 col-md-3'">
            <label for="business_name" class="col-form-label text-md-right">{{ $client['business_name'] }}</label>
        </div>
        <label for="nit" class="col-form-label text-md-right" :class="'col-md-3'"><b>Nit:</b></label>
        <div :class="'col-md-3 col-md-3'">
            <label for="nit" class="col-form-label text-md-right">{{ $client['nit'] }}</label>
        </div>
    </div>
@endif

<div class="card-header">
    <i class="fa fa-pencil"></i> Pedido
</div>
<div class="form-group row align-items-center">
    <label for="total_products" class="col-form-label text-md-right" :class="'col-md-3'"><b>Número de productos:</b></label>
    <div :class="'col-md-3 col-md-3'">
        <label for="total_products" class="col-form-label text-md-right">{{ $order['total_products'] }} unidades</label>
    </div>
    <label for="total_amount" class="col-form-label text-md-right" :class="'col-md-3'"><b>Total Productos:</b></label>
    <div :class="'col-md-3 col-md-3'">
        <label for="total_amount" class="col-form-label text-md-right">{{ $order['total_amount'] }}</label>
    </div>
</div>

<div class="form-group row align-items-center">
    <label for="delivery_amount" class="col-form-label text-md-right" :class="'col-md-3'"><b>Costo de envío:</b></label>
    <div :class="'col-md-3 col-md-3'">
        <label for="delivery_amount" class="col-form-label text-md-right">{{ $order['delivery_amount'] }}</label>
    </div>
    <label for="total_discount" class="col-form-label text-md-right" :class="'col-md-3'"><b>Descuento:</b></label>
    <div :class="'col-md-3 col-md-3'">
        <label for="total_discount" class="col-form-label text-md-right">{{ $order['total_discount'] }}</label>
    </div>
</div>

<div class="form-group row align-items-center">
    <label style="color: #2d3093" for="total" class="col-form-label text-md-right" :class="'col-md-3'"><b>Total:</b></label>
    <div :class="'col-md-3 col-md-3'">
        <label style="color: #2d3093" for="total" class="col-form-label text-md-right"><b>{{ $order['total'] }}</b></label>
    </div>
    <label for="status" class="col-form-label text-md-right" :class="'col-md-3'"><b>Estado:</b></label>
    <div :class="'col-md-3 col-md-3'">
        @if($order['status'] === 'Cancelado')
            <div class="col text-center"><button disabled style="color: white" class="btn btn-sm btn-danger"><i></i>&nbspCancelado</button></div>
        @endif
        @if($order['status'] === 'Iniciado')
            <div class="col text-center"><button disabled style="color: white" class="btn btn-sm btn-status-cancel"><i></i>&nbspIniciado</button></div>
        @endif
        @if($order['status'] === 'Aceptado')
            <div class="col text-center"><button disabled style="color: white" class="btn btn-sm btn-success"><i></i>&nbspAceptado</button></div>
        @endif
        @if($order['status'] === 'Alistamiento')
            <div class="col text-center"><button disabled style="color: white" class="btn btn-sm btn-status-cancel"><i></i>&nbspAlistamiento</button></div>
        @endif
        @if($order['status'] === 'Circulación')
            <div class="col text-center"><button disabled style="color: white" class="btn btn-sm btn-status-cancel"><i></i>&nbspCirculación</button></div>
        @endif
        @if($order['status'] === 'Entregado')
            <div class="col text-center"><button disabled style="color: white" class="btn btn-sm btn-success"><i></i>&nbspEntregado</button></div>
        @endif
    </div>
</div>
