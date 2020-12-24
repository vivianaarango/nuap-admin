<div class="form-group row align-items-center" :class="{'has-danger': errors.has('user_type'), 'has-success': this.fields.user_type && this.fields.user_type.valid }">
    <label for="user_id" class="col-form-label text-md-right" :class="'col-md-3'">Tipo de Usuario</label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <select onchange="getUserID()" class="form-control" id="user_type" name="user_type" required>
            <option disabled selected>Tipo de Usuario</option>
            <option value="Distribuidor">Distribuidor</option>
            <option value="Comercio">Comercio</option>
        </select>
        <div v-if="errors.has('user_type')" class="form-control-feedback form-text" v-cloak>@{{errors.first('user_type') }}</div>
    </div>
</div>

<div id="commerce" style="display: none" class="form-group row align-items-center" :class="{'has-danger': errors.has('user_id'), 'has-success': this.fields.user_id && this.fields.user_id.valid }">
    <label for="user_id" class="col-form-label text-md-right" :class="'col-md-3'">Comercio</label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <select class="form-control" id="user_id" name="user_id" required>
            <option disabled selected>Comercios</option>
            @foreach ($commerces as $commerce)
                <option value="{{ $commerce->id }}">{{ $commerce->business_name }}</option>
            @endforeach
        </select>
        <div v-if="errors.has('user_id')" class="form-control-feedback form-text" v-cloak>@{{errors.first('user_id') }}</div>
    </div>
</div>

<div id="distributor" style="display: none" class="form-group row align-items-center" :class="{'has-danger': errors.has('user_id'), 'has-success': this.fields.user_id && this.fields.user_id.valid }">
    <label for="user_id" class="col-form-label text-md-right" :class="'col-md-3'">Distribuidores</label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <select class="form-control" id="user_id" name="user_id" required>
            <option disabled selected>Distribuidores</option>
            @foreach ($distributors as $distributor)
                <option value="{{ $distributor->id }}">{{ $distributor->business_name }}</option>
            @endforeach
        </select>
        <div v-if="errors.has('user_id')" class="form-control-feedback form-text" v-cloak>@{{errors.first('user_id') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('category_id'), 'has-success': this.fields.category_id && this.fields.category_id.valid }">
    <label for="category_id" class="col-form-label text-md-right" :class="'col-md-3'">Categoría</label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <select class="form-control" id="category_id" name="category_id" required>
            <option disabled selected>Categoría</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
        <div v-if="errors.has('category_id')" class="form-control-feedback form-text" v-cloak>@{{errors.first('category_id') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('name'), 'has-success': this.fields.name && this.fields.name.valid }">
    <label for="name" class="col-form-label text-md-right" :class="'col-md-3'">Nombre</label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <input type="text" required class="form-control" :class="{'form-control-danger': errors.has('name'), 'form-control-success': this.fields.name && this.fields.name.valid}" id="name" name="name" placeholder="Nombre">
        <div v-if="errors.has('name')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('name') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('sku'), 'has-success': this.fields.sku && this.fields.sku.valid }">
    <label for="sku" class="col-form-label text-md-right" :class="'col-md-3'">Sku</label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <input type="text" required class="form-control" :class="{'form-control-danger': errors.has('sku'), 'form-control-success': this.fields.sku && this.fields.sku.valid}" id="sku" name="sku" placeholder="Sku">
        <div v-if="errors.has('sku')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('sku') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('brand'), 'has-success': this.fields.brand && this.fields.brand.valid }">
    <label for="brand" class="col-form-label text-md-right" :class="'col-md-3'">Marca</label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <input type="text" required class="form-control" :class="{'form-control-danger': errors.has('brand'), 'form-control-success': this.fields.brand && this.fields.brand.valid}" id="brand" name="brand" placeholder="Marca">
        <div v-if="errors.has('brand')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('brand') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('description'), 'has-success': this.fields.description && this.fields.description.valid }">
    <label for="description" class="col-form-label text-md-right" :class="'col-md-3'">Descripción</label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <textarea  required class="form-control" :class="{'form-control-danger': errors.has('description'), 'form-control-success': this.fields.description && this.fields.description.valid}" id="description" name="description" placeholder="Descripción"></textarea>
        <div v-if="errors.has('description')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('description') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('stock'), 'has-success': this.fields.stock && this.fields.stock.valid }">
    <label for="stock" class="col-form-label text-md-right" :class="'col-md-3'">Unidades</label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <input type="number" required class="form-control" :class="{'form-control-danger': errors.has('stock'), 'form-control-success': this.fields.stock && this.fields.stock.valid}" id="stock" name="stock" placeholder="Unidades">
        <div v-if="errors.has('stock')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('stock') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('weight'), 'has-success': this.fields.weight && this.fields.weight.valid }">
    <label for="weight" class="col-form-label text-md-right" :class="'col-md-3'">Peso</label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <input type="number" required class="form-control" :class="{'form-control-danger': errors.has('weight'), 'form-control-success': this.fields.weight && this.fields.weight.valid}" id="weight" name="weight" placeholder="Peso">
        <div v-if="errors.has('weight')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('weight') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('length'), 'has-success': this.fields.length && this.fields.length.valid }">
    <label for="length" class="col-form-label text-md-right" :class="'col-md-3'">Largo</label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <input type="number" required class="form-control" :class="{'form-control-danger': errors.has('length'), 'form-control-success': this.fields.length && this.fields.length.valid}" id="length" name="length" placeholder="Largo">
        <div v-if="errors.has('length')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('length') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('width'), 'has-success': this.fields.width && this.fields.width.valid }">
    <label for="width" class="col-form-label text-md-right" :class="'col-md-3'">Ancho</label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <input type="number" required class="form-control" :class="{'form-control-danger': errors.has('width'), 'form-control-success': this.fields.width && this.fields.width.valid}" id="width" name="width" placeholder="Ancho">
        <div v-if="errors.has('width')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('width') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('height'), 'has-success': this.fields.height && this.fields.height.valid }">
    <label for="height" class="col-form-label text-md-right" :class="'col-md-3'">Alto</label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <input type="number" required class="form-control" :class="{'form-control-danger': errors.has('height'), 'form-control-success': this.fields.height && this.fields.height.valid}" id="height" name="height" placeholder="Alto">
        <div v-if="errors.has('height')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('height') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('purchase_price'), 'has-success': this.fields.purchase_price && this.fields.purchase_price.valid }">
    <label for="purchase_price" class="col-form-label text-md-right" :class="'col-md-3'">Precio de Compra</label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <input type="number" required class="form-control" :class="{'form-control-danger': errors.has('purchase_price'), 'form-control-success': this.fields.purchase_price && this.fields.purchase_price.valid}" id="purchase_price" name="purchase_price" placeholder="Precio de Compra">
        <div v-if="errors.has('purchase_price')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('purchase_price') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('sale_price'), 'has-success': this.fields.sale_price && this.fields.sale_price.valid }">
    <label for="sale_price" class="col-form-label text-md-right" :class="'col-md-3'">Precio de Venta</label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <input type="number" required class="form-control" :class="{'form-control-danger': errors.has('sale_price'), 'form-control-success': this.fields.sale_price && this.fields.sale_price.valid}" id="sale_price" name="sale_price" placeholder="Precio de Venta">
        <div v-if="errors.has('sale_price')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('sale_price') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('special_price'), 'has-success': this.fields.special_price && this.fields.special_price.valid }">
    <label for="special_price" class="col-form-label text-md-right" :class="'col-md-3'">Descuento</label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <input min="0" max="100" type="number" step="0.1" required class="form-control" :class="{'form-control-danger': errors.has('special_price'), 'form-control-success': this.fields.special_price && this.fields.special_price.valid}" id="special_price" name="special_price" placeholder="Precio Especial">
        <div v-if="errors.has('special_price')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('special_price') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('image'), 'has-success': this.fields.image && this.fields.image.valid }">
    <label for="image" class="col-form-label text-md-right" :class="'col-md-3'">Imagen</label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <input required type="file" style="color: #b9c8de" class="" :class="{'form-control-danger': errors.has('image'), 'form-control-success': this.fields.image && this.fields.image.valid}" id="image" name="image" placeholder="Imagen">
        <div v-if="errors.has('image')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('image') }}</div>
    </div>
</div>

<div class="form-check row" :class="{'has-danger': errors.has('is_featured'), 'has-success': this.fields.is_featured && this.fields.is_featured.valid }">
    <div class="ml-md-auto" :class="'col-md-9'">
        <input class="form-check-input" id="is_featured" type="checkbox" v-validate="''" data-vv-name="is_featured"  name="is_featured">
        <label class="form-check-label" for="is_featured">
            Destacado
        </label>
        <div v-if="errors.has('is_featured')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('is_featured') }}</div>
    </div>
</div>

<div class="form-check row" :class="{'has-danger': errors.has('has_special_price'), 'has-success': this.fields.has_special_price && this.fields.has_special_price.valid }">
    <div class="ml-md-auto" :class="'col-md-9'">
        <input class="form-check-input" id="has_special_price" type="checkbox" v-validate="''" data-vv-name="has_special_price"  name="has_special_price">
        <label class="form-check-label" for="has_special_price">
            Activar Descuento
        </label>
        <div v-if="errors.has('has_special_price')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('has_special_price') }}</div>
    </div>
</div>
