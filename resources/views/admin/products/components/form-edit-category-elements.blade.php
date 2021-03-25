<div class="form-group row align-items-center" :class="{'has-danger': errors.has('name'), 'has-success': this.fields.name && this.fields.name.valid }">
    <label for="name" class="col-form-label text-md-right" :class="'col-md-3'">Nombre</label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <input value="{{ $product['name'] }}" type="text" required class="form-control" :class="{'form-control-danger': errors.has('name'), 'form-control-success': this.fields.name && this.fields.name.valid}" id="name" name="name" placeholder="Nombre">
        <div v-if="errors.has('name')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('name') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('description'), 'has-success': this.fields.description && this.fields.description.valid }">
    <label for="description" class="col-form-label text-md-right" :class="'col-md-3'">Descripción</label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <textarea required class="form-control" :class="{'form-control-danger': errors.has('description'), 'form-control-success': this.fields.description && this.fields.description.valid}" id="description" name="description" placeholder="Descripción">{{ $product['description'] }}</textarea>
        <div v-if="errors.has('description')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('description') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('image'), 'has-success': this.fields.image && this.fields.image.valid }">
    <label for="image" class="col-form-label text-md-right" :class="'col-md-3'">Imagen</label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <input type="file" style="color: #b9c8de" class="" :class="{'form-control-danger': errors.has('image'), 'form-control-success': this.fields.image && this.fields.image.valid}" id="image" name="image" placeholder="Imagen">
        <div v-if="errors.has('image')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('image') }}</div>
        @if($product['image'] != null)
            <a style="background-color: #60abcf !important;border-color: #60b5cf !important;" target="_blank" class="btn btn-sm btn-link-documents" href="../../../{{ $product['image'] }}" class="col-auto" title="Ver" role="button">
                <i class="fa fa-mail-forward"></i>
            </a>
        @endif
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('parent_id'), 'has-success': this.fields.parent_id && this.fields.parent_id.valid }">
    <label for="parent_id" class="col-form-label text-md-right" :class="'col-md-3'">Subcategoría</label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <select class="form-control" id="parent_id" name="parent_id" required>
            <option disabled selected>Subcategoría</option>
            @foreach ($categories as $category)
                @if ($product['parent_id'] === $category->id )
                    <option selected value="{{ $category->id }}">{{ $category->name }}</option>
                @else
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endif
            @endforeach
        </select>
        <div v-if="errors.has('parent_id')" class="form-control-feedback form-text" v-cloak>@{{errors.first('parent_id') }}</div>
    </div>
</div>