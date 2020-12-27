<div class="form-group row align-items-center" :class="{'has-danger': errors.has('category_id'), 'has-success': this.fields.category_id && this.fields.category_id.valid }">
    <label for="category_id" class="col-form-label text-md-right" :class="'col-md-3'"><b>Categoría:</b></label>
    @foreach ($categories as $category)
        @if ($product['category_id'] === $category->id )
            <div :class="'col-md-3 col-md-3'">
                <label for="category_id" class="col-form-label text-md-right">{{ $category->name }}</label>
            </div>
        @endif
    @endforeach
    <label for="brand" class="col-form-label text-md-right" :class="'col-md-3'"><b>Marca:</b></label>
    <div :class="'col-md-3 col-md-3'">
        <label for="brand" class="col-form-label text-md-right">{{ $product['brand'] }}</label>
    </div>
</div>

<div class="form-group row align-items-center">
    <label for="name" class="col-form-label text-md-right" :class="'col-md-3'"><b>Nombre:</b></label>
    <div :class="'col-md-3 col-md-3'">
        <label for="name" class="col-form-label text-md-right">{{ $product['name'] }}</label>
    </div>
    <label for="sku" class="col-form-label text-md-right" :class="'col-md-3'"><b>Sku:</b></label>
    <div :class="'col-md-3 col-md-3'">
        <label for="sku" class="col-form-label text-md-right">{{ $product['sku'] }}</label>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('special_price'), 'has-success': this.fields.special_price && this.fields.special_price.valid }">
    <label for="special_price" class="col-form-label text-md-right" :class="'col-md-3'"><b>Descuento:</b></label>
    <div :class="'col-md-3 col-md-3'">
        <label for="special_price" class="col-form-label text-md-right">{{ $product['special_price'] }}%</label>
    </div>
    <label for="stock" class="col-form-label text-md-right" :class="'col-md-3'"><b>Unidades:</b></label>
    <div :class="'col-md-3 col-md-3'">
        <label for="stock" class="col-form-label text-md-right">{{ $product['stock'] }}</label>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('weight'), 'has-success': this.fields.weight && this.fields.weight.valid }">
    <label for="weight" class="col-form-label text-md-right" :class="'col-md-3'"><b>Peso:</b></label>
    <div :class="'col-md-3 col-md-3'">
        <label for="weight" class="col-form-label text-md-right">{{ $product['weight'] }}</label>
    </div>
    <label for="length" class="col-form-label text-md-right" :class="'col-md-3'"><b>Largo:</b></label>
    <div :class="'col-md-3 col-md-3'">
        <label for="length" class="col-form-label text-md-right">{{ $product['length'] }}</label>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('width'), 'has-success': this.fields.width && this.fields.width.valid }">
    <label for="width" class="col-form-label text-md-right" :class="'col-md-3'"><b>Ancho:</b></label>
    <div :class="'col-md-3 col-md-3'">
        <label for="width" class="col-form-label text-md-right">{{ $product['width'] }}</label>
    </div>
    <label for="height" class="col-form-label text-md-right" :class="'col-md-3'"><b>Alto:</b></label>
    <div :class="'col-md-3 col-md-3'">
        <label for="height" class="col-form-label text-md-right">{{ $product['height'] }}</label>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('purchase_price'), 'has-success': this.fields.purchase_price && this.fields.purchase_price.valid }">
    <label for="purchase_price" class="col-form-label text-md-right" :class="'col-md-3'"><b>Precio de Compra:</b></label>
    <div :class="'col-md-3 col-md-3'">
        <label for="purchase_price" class="col-form-label text-md-right">{{ $product['purchase_price'] }}</label>
    </div>
    <label for="sale_price" class="col-form-label text-md-right" :class="'col-md-3'"><b>Precio de Venta:</b></label>
    <div :class="'col-md-3 col-md-3'">
        <label for="sale_price" class="col-form-label text-md-right">{{ $product['sale_price'] }}</label>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('description'), 'has-success': this.fields.description && this.fields.description.valid }">
    <label for="description" class="col-form-label text-md-right" :class="'col-md-3'"><b>Descripción:</b></label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <label for="description" class="col-form-label text-md-right">{{ $product['description'] }}</label>
    </div>
</div>
