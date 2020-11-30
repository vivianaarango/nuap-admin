<div class="form-group row align-items-center" :class="{'has-danger': errors.has('rut'), 'has-success': this.fields.rut && this.fields.rut.valid }">
    <label for="rut" class="col-form-label text-md-right" :class="'col-md-4 col-md-3'">Rut</label>
    <div :class="'col-md-3 col-md-7 col-xl-5'">
        <input type="file" style="color: #b9c8de" class="" :class="{'form-control-danger': errors.has('rut'), 'form-control-success': this.fields.rut && this.fields.rut.valid}" id="rut" name="rut" placeholder="Rut">
        <div v-if="errors.has('rut')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('rut') }}</div>
    </div>
    @if($urls->url_rut != null)
        <a style="background-color: #60abcf !important;border-color: #60b5cf !important;" target="_blank" class="btn btn-sm btn-link-documents" class="col-auto" href="../../../{{ $urls->url_rut }}" title="Ver" role="button">
            <i class="fa fa-mail-forward"></i>
        </a>
    @endif
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('commerce_room'), 'has-success': this.fields.commerce_room && this.fields.commerce_room.valid }">
    <label for="commerce_room" class="col-form-label text-md-right" :class="'col-md-4 col-md-3'">Cámara de Comercio</label>
    <div :class="'col-md-3 col-md-7 col-xl-5'">
        <input type="file" style="color: #b9c8de" class="" :class="{'form-control-danger': errors.has('commerce_room'), 'form-control-success': this.fields.commerce_room && this.fields.commerce_room.valid}" id="commerce_room" name="commerce_room" placeholder="Cámara de Comercio">
        <div v-if="errors.has('commerce_room')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('commerce_room') }}</div>
    </div>
    @if($urls->url_commerce_room != null)
        <a style="background-color: #60abcf !important;border-color: #60b5cf !important;" target="_blank" class="btn btn-sm btn-link-documents" href="../../../{{ $urls->url_commerce_room }}" class="col-auto" href="item.resource_url+'/add-document'" title="Ver" role="button">
            <i class="fa fa-mail-forward"></i>
        </a>
    @endif
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('cc_legal_representative'), 'has-success': this.fields.cc_legal_representative && this.fields.cc_legal_representative.valid }">
    <label for="cc_legal_representative" class="col-form-label text-md-right" :class="'col-md-4 col-md-3'">Cédula Representante Legal</label>
    <div :class="'col-md-3 col-md-7 col-xl-5'">
        <input type="file" style="color: #b9c8de" class="" :class="{'form-control-danger': errors.has('cc_legal_representative'), 'form-control-success': this.fields.cc_legal_representative && this.fields.rut.valid}" id="cc_legal_representative" name="cc_legal_representative" placeholder="Cédula Representante Legal">
        <div v-if="errors.has('cc_legal_representative')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('cc_legal_representative') }}</div>
    </div>
    @if($urls->url_cc_legal_representative != null)
        <a style="background-color: #60abcf !important;border-color: #60b5cf !important;" target="_blank" class="btn btn-sm btn-link-documents" href="../../../{{ $urls->url_cc_legal_representative }}" class="col-auto" href="item.resource_url+'/add-document'" title="Ver" role="button">
            <i class="fa fa-mail-forward"></i>
        </a>
    @endif
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('establishment_image'), 'has-success': this.fields.establishment_image && this.fields.establishment_image.valid }">
    <label for="establishment_image" class="col-form-label text-md-right" :class="'col-md-4 col-md-3'">Foto Establecimiento Exterior</label>
    <div :class="'col-md-3 col-md-7 col-xl-5'">
        <input type="file" style="color: #b9c8de" class="" :class="{'form-control-danger': errors.has('rut'), 'form-control-success': this.fields.establishment_image && this.fields.establishment_image.valid}" id="establishment_image" name="establishment_image" placeholder="Foto Establecimiento Exterior">
        <div v-if="errors.has('establishment_image')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('establishment_image') }}</div>
    </div>
    @if($urls->url_establishment_image != null)
        <a style="background-color: #60abcf !important;border-color: #60b5cf !important;" target="_blank" class="btn btn-sm btn-link-documents" href="../../../{{ $urls->url_establishment_image }}" class="col-auto" href="item.resource_url+'/add-document'" title="Ver" role="button">
            <i class="fa fa-mail-forward"></i>
        </a>
    @endif
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('interior_image'), 'has-success': this.fields.interior_image && this.fields.interior_image.valid }">
    <label for="interior_image" class="col-form-label text-md-right" :class="'col-md-4 col-md-3'">Foto estantería, Caja, Bodega</label>
    <div :class="'col-md-3 col-md-7 col-xl-5'">
        <input type="file" style="color: #b9c8de" class="" :class="{'form-control-danger': errors.has('interior_image'), 'form-control-success': this.fields.interior_image && this.fields.interior_image.valid}" id="interior_image" name="interior_image" placeholder="Foto estantería, Caja, Bodega">
        <div v-if="errors.has('interior_image')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('interior_image') }}</div>
    </div>
    @if($urls->url_interior_image != null)
        <a style="background-color: #60abcf !important;border-color: #60b5cf !important;" target="_blank" class="btn btn-sm btn-link-documents" href="../../../{{ $urls->url_interior_image }}" class="col-auto" href="item.resource_url+'/add-document'" title="Ver" role="button">
            <i class="fa fa-mail-forward"></i>
        </a>
    @endif
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('contract'), 'has-success': this.fields.contract && this.fields.contract.valid }">
    <label for="contract" class="col-form-label text-md-right" :class="'col-md-4 col-md-3'">Contrato</label>
    <div :class="'col-md-3 col-md-7 col-xl-5'">
        <input type="file" style="color: #b9c8de" class="" :class="{'form-control-danger': errors.has('contract'), 'form-control-success': this.fields.contract && this.fields.contract.valid}" id="contract" name="contract" placeholder="Contrato">
        <div v-if="errors.has('contract')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('contract') }}</div>
    </div>
    @if($urls->url_contract != null)
        <a style="background-color: #60abcf !important;border-color: #60b5cf !important;" target="_blank" class="btn btn-sm btn-link-documents" href="../../../{{ $urls->url_contract }}" class="col-auto" href="item.resource_url+'/add-document'" title="Ver" role="button">
            <i class="fa fa-mail-forward"></i>
        </a>
    @endif
</div>