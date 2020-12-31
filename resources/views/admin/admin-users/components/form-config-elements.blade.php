<div class="form-group row align-items-center" :class="{'has-danger': errors.has('shipping_cost'), 'has-success': this.fields.shipping_cost && this.fields.shipping_cost.valid }">
    <label for="shipping_cost" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Costo de envío</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
        <input onkeypress="return isNumberKey(event)" type="text" v-validate="'required'" v-model="form.shipping_cost" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('shipping_cost'), 'form-control-success': this.fields.shipping_cost && this.fields.shipping_cost.valid}" id="shipping_cost" name="shipping_cost" placeholder="Costo de envío">
        <div v-if="errors.has('shipping_cost')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('shipping_cost') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('distance'), 'has-success': this.fields.distance && this.fields.distance.valid }">
    <label for="distance" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Distancia</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
        <input onkeypress="return isNumberKey(event)" type="text" v-validate="'required'" v-model="form.distance" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('distance'), 'form-control-success': this.fields.distance && this.fields.distance.valid}" id="distance" name="distance" placeholder="Distancia">
        <div v-if="errors.has('distance')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('distance') }}</div>
    </div>
</div>
