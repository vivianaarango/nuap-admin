<div class="form-group row align-items-center" :class="{'has-danger': errors.has('email'), 'has-success': this.fields.email && this.fields.email.valid }">
    <label for="identity_type" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Correo electrónico</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
        <input type="text" v-model="form.email" v-validate="'required|email'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('email'), 'form-control-success': this.fields.email && this.fields.email.valid}" id="email" name="email" placeholder="Correo electrónico">
        <div v-if="errors.has('email')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('email') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('phone'), 'has-success': this.fields.phone && this.fields.phone.valid }">
    <label for="phone" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Teléfono</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
        <input onkeypress="return isNumberKey(event)" type="text" v-model="form.phone" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('phone'), 'form-control-success': this.fields.phone && this.fields.phone.valid}" id="phone" name="phone" placeholder="Teléfono">
        <div v-if="errors.has('phone')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('phone') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('password'), 'has-success': this.fields.password && this.fields.password.valid }">
    <label for="password" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Contraseña</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
        <input type="password" v-model="form.password" v-validate="'min:8|required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('password'), 'form-control-success': this.fields.password && this.fields.password.valid}" id="password" name="password" placeholder="Contraseña" ref="password">
        <div v-if="errors.has('password')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('password') }}</div>
        <small class="form-text text-muted">
            La contraseña debe contener minimo 8 caracteres, una mayuscula, un número y un carácter especial.
        </small>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('password_confirmation'), 'has-success': this.fields.password_confirmation && this.fields.password_confirmation.valid }">
    <label for="password_confirmation" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Confirma tu contraseña</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
        <input type="password" v-model="form.password_confirmation" v-validate="'confirmed:password|min:8|required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('password_confirmation'), 'form-control-success': this.fields.password_confirmation && this.fields.password_confirmation.valid}" id="password_confirmation" name="password_confirmation" placeholder="Confirma tu contraseña" data-vv-as="password">
        <div v-if="errors.has('password_confirmation')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('password_confirmation') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('business_name'), 'has-success': this.fields.business_name && this.fields.business_name.valid }">
    <label for="business_name" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Razón Social</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
        <input type="text" v-model="form.business_name" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('business_name'), 'form-control-success': this.fields.business_name && this.fields.business_name.valid}" id="business_name" name="business_name" placeholder="Razón Social">
        <div v-if="errors.has('business_name')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('business_name') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('city'), 'has-success': this.fields.city && this.fields.city.valid }">
    <label for="city" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Ciudad</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
        <select v-model="form.city" @input="validate($event)" class="form-control" id="city" name="city" required>
            <option value="Bogotá">Bogotá</option>
            <option value="Medellin">Medellin</option>
            <option value="Barranquilla">Barranquilla</option>
            <option value="Santander">Santander</option>
        </select>
        <div v-if="errors.has('city')" class="form-control-feedback form-text" v-cloak>@{{errors.first('city') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('location'), 'has-success': this.fields.location && this.fields.location.valid }">
    <label for="location" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Localidad</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
        <input type="text" v-model="form.location" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('location'), 'form-control-success': this.fields.location && this.fields.location.valid}" id="location" name="location" placeholder="Localidad">
        <div v-if="errors.has('location')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('location') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('neighborhood'), 'has-success': this.fields.neighborhood && this.fields.neighborhood.valid }">
    <label for="neighborhood" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Barrio</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
        <input type="text" v-model="form.neighborhood" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('neighborhood'), 'form-control-success': this.fields.neighborhood && this.fields.neighborhood.valid}" id="neighborhood" name="neighborhood" placeholder="Barrio">
        <div v-if="errors.has('neighborhood')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('neighborhood') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('address'), 'has-success': this.fields.address && this.fields.address.valid }">
    <label for="address" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Dirección</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
        <input type="text" v-model="form.address" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('address'), 'form-control-success': this.fields.address && this.fields.address.valid}" id="address" name="address" placeholder="Dirección">
        <div v-if="errors.has('address')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('address') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" >
    <label class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Ubicación</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
        <div class="map">
            <div class="content-map" id="map"></div>
        </div>
    </div>
</div>

<div id="commission-block" class="form-group row align-items-center" :class="{'has-danger': errors.has('commission'), 'has-success': this.fields.commission && this.fields.commission.valid }">
    <label for="commission" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Comisión</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
        <input min="0" max="100" type="number" step="0.1" v-model="form.commission" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('commission'), 'form-control-success': this.fields.commission && this.fields.commission.valid}" id="commission" name="commission" placeholder="Comisión">
        <div v-if="errors.has('commission')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('commission') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('type'), 'has-success': this.fields.type && this.fields.type.valid }">
    <label for="type" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Tipo</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
        <select v-model="form.type" @input="validate($event)" class="form-control" id="type" name="type">
            <option value="Distribuidor">Distribuidor</option>
            <option value="Mayorista">Mayorista</option>
        </select>
        <div v-if="errors.has('type')" class="form-control-feedback form-text" v-cloak>@{{errors.first('type') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('name_legal_representative'), 'has-success': this.fields.name_legal_representative && this.fields.name_legal_representative.valid }">
    <label for="name_legal_representative" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Representante Legal</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
        <input type="text" v-model="form.name_legal_representative" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('name_legal_representative'), 'form-control-success': this.fields.name_legal_representative && this.fields.name_legal_representative.valid}" id="name_legal_representative" name="name_legal_representative" placeholder="Representante Legal">
        <div v-if="errors.has('name_legal_representative')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('name_legal_representative') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('cc_legal_representative'), 'has-success': this.fields.cc_legal_representative && this.fields.cc_legal_representative.valid }">
    <label for="cc_legal_representative" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Identificación Representante Legal</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
        <input onkeypress="return isNumberKey(event)" type="text" v-model="form.cc_legal_representative" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('cc_legal_representative'), 'form-control-success': this.fields.cc_legal_representative && this.fields.cc_legal_representative.valid}" id="cc_legal_representative" name="cc_legal_representative" placeholder="Identificación Representante Legal">
        <div v-if="errors.has('cc_legal_representative')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('cc_legal_representative') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('contact_legal_representative'), 'has-success': this.fields.contact_legal_representative && this.fields.contact_legal_representative.valid }">
    <label for="contact_legal_representative" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Contacto Principal</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
        <input onkeypress="return isNumberKey(event)" type="text" v-model="form.contact_legal_representative" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('contact_legal_representative'), 'form-control-success': this.fields.contact_legal_representative && this.fields.contact_legal_representative.valid}" id="contact_legal_representative" name="contact_legal_representative" placeholder="Contacto Principal">
        <div v-if="errors.has('contact_legal_representative')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('contact_legal_representative') }}</div>
    </div>
</div>
