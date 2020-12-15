<div class="form-group row align-items-center" :class="{'has-danger': errors.has('category_id'), 'has-success': this.fields.category_id && this.fields.category_id.valid }">
    <label for="category_id" class="col-form-label text-md-right" :class="'col-md-3'"><b>Categoría:</b></label>
    @foreach ($categories as $category)
        @if ($product['category_id'] === $category->id )
            <div :class="'col-md-4 col-md-9 col-xl-7'">
                <label for="category_id" class="col-form-label text-md-right">{{ $category->name }}</label>
            </div>
        @endif
    @endforeach
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('name'), 'has-success': this.fields.name && this.fields.name.valid }">
    <label for="name" class="col-form-label text-md-right" :class="'col-md-3'"><b>Nombre:</b></label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <label for="name" class="col-form-label text-md-right">{{ $product['name'] }}</label>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('brand'), 'has-success': this.fields.brand && this.fields.brand.valid }">
    <label for="brand" class="col-form-label text-md-right" :class="'col-md-3'"><b>Marca:</b></label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <label for="brand" class="col-form-label text-md-right">{{ $product['brand'] }}</label>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('description'), 'has-success': this.fields.description && this.fields.description.valid }">
    <label for="description" class="col-form-label text-md-right" :class="'col-md-3'"><b>Descripción:</b></label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <label for="description" class="col-form-label text-md-right">{{ $product['description'] }}</label>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('stock'), 'has-success': this.fields.stock && this.fields.stock.valid }">
    <label for="stock" class="col-form-label text-md-right" :class="'col-md-3'"><b>Unidades:</b></label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <label for="stock" class="col-form-label text-md-right">{{ $product['stock'] }}</label>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('weight'), 'has-success': this.fields.weight && this.fields.weight.valid }">
    <label for="weight" class="col-form-label text-md-right" :class="'col-md-3'"><b>Peso:</b></label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <label for="weight" class="col-form-label text-md-right">{{ $product['weight'] }}</label>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('length'), 'has-success': this.fields.length && this.fields.length.valid }">
    <label for="length" class="col-form-label text-md-right" :class="'col-md-3'"><b>Largo:</b></label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <label for="length" class="col-form-label text-md-right">{{ $product['length'] }}</label>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('width'), 'has-success': this.fields.width && this.fields.width.valid }">
    <label for="width" class="col-form-label text-md-right" :class="'col-md-3'"><b>Ancho:</b></label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <label for="width" class="col-form-label text-md-right">{{ $product['width'] }}</label>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('height'), 'has-success': this.fields.height && this.fields.height.valid }">
    <label for="height" class="col-form-label text-md-right" :class="'col-md-3'"><b>Alto:</b></label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <label for="height" class="col-form-label text-md-right">{{ $product['height'] }}</label>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('purchase_price'), 'has-success': this.fields.purchase_price && this.fields.purchase_price.valid }">
    <label for="purchase_price" class="col-form-label text-md-right" :class="'col-md-3'"><b>Precio de Compra:</b></label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <label for="purchase_price" class="col-form-label text-md-right">{{ $product['purchase_price'] }}</label>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('sale_price'), 'has-success': this.fields.sale_price && this.fields.sale_price.valid }">
    <label for="sale_price" class="col-form-label text-md-right" :class="'col-md-3'"><b>Precio de Venta:</b></label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <label for="sale_price" class="col-form-label text-md-right">{{ $product['sale_price'] }}</label>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('special_price'), 'has-success': this.fields.special_price && this.fields.special_price.valid }">
    <label for="special_price" class="col-form-label text-md-right" :class="'col-md-3'"><b>Precio Especial:</b></label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <label for="special_price" class="col-form-label text-md-right">{{ $product['special_price'] }}</label>
    </div>
</div>