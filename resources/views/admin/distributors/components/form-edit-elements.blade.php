<div class="form-group row align-items-center" :class="{'has-danger': errors.has('name'), 'has-success': this.fields.name && this.fields.name.valid }">
    <label for="name" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Nombres</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
        <input type="text" v-model="form.name" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('first_name'), 'form-control-success': this.fields.name && this.fields.name.valid}" id="name" name="name" placeholder="Nombres">
        <div v-if="errors.has('name')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('name') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('lastname'), 'has-success': this.fields.lastname && this.fields.lastname.valid }">
    <label for="lastname" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Apellidos</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
        <input type="text" v-model="form.lastname" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('lastname'), 'form-control-success': this.fields.lastname && this.fields.lastname.valid}" id="lastname" name="lastname" placeholder="Apellidos">
        <div v-if="errors.has('lastname')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('lastname') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('identity_type'), 'has-success': this.fields.identity_type && this.fields.identity_type.valid }">
    <label for="email" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Tipo de identificación</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
        <select v-model="form.identity_type" @input="validate($event)" class="form-control" name="identity_type">
            <option value="Cédula">Cédula</option>
            <option value="Nit">Nit</option>
            <option value="Cédula de extranjería">Cédula de extranjería</option>
        </select>
        <div v-if="errors.has('identity_type')" class="form-control-feedback form-text" v-cloak>@{{errors.first('identity_type') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('identity_number'), 'has-success': this.fields.identity_number && this.fields.identity_number.valid }">
    <label for="identity_number" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Número de identificación</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
        <input type="text" v-model="form.identity_number" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('identity_number'), 'form-control-success': this.fields.identity_number && this.fields.identity_number.valid}" id="identity_number" name="identity_number" placeholder="Número de identificación">
        <div v-if="errors.has('identity_number')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('identity_number') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('phone'), 'has-success': this.fields.phone && this.fields.phone.valid }">
    <label for="phone" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Teléfono</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
        <input type="text" v-model="form.phone" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('phone'), 'form-control-success': this.fields.phone && this.fields.phone.valid}" id="phone" name="phone" placeholder="Teléfono">
        <div v-if="errors.has('phone')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('phone') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('email'), 'has-success': this.fields.email && this.fields.email.valid }">
    <label for="identity_type" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Correo electrónico</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
        <input type="text" v-model="form.email" v-validate="'required|email'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('email'), 'form-control-success': this.fields.email && this.fields.email.valid}" id="email" name="email" placeholder="Correo electrónico">
        <div v-if="errors.has('email')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('email') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('password'), 'has-success': this.fields.password && this.fields.password.valid }">
    <label for="password" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Contraseña</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
        <input type="password" v-model="form.password" v-validate="'min:8'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('password'), 'form-control-success': this.fields.password && this.fields.password.valid}" id="password" name="password" placeholder="Contraseña" ref="password">
        <div v-if="errors.has('password')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('password') }}</div>
        <small class="form-text text-muted">
            La contraseña debe contener minimo 8 caracteres, una mayuscula, un número y un carácter especial.
        </small>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('password_confirmation'), 'has-success': this.fields.password_confirmation && this.fields.password_confirmation.valid }">
    <label for="password_confirmation" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Confirma tu contraseña</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
        <input type="password" v-model="form.password_confirmation" v-validate="'confirmed:password|min:8'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('password_confirmation'), 'form-control-success': this.fields.password_confirmation && this.fields.password_confirmation.valid}" id="password_confirmation" name="password_confirmation" placeholder="Confirma tu contraseña" data-vv-as="password">
        <div v-if="errors.has('password_confirmation')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('password_confirmation') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('role'), 'has-success': this.fields.role && this.fields.role.valid }">
    <label for="role" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Tipo de usuario</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
        <select v-model="form.role" @input="validate($event)" @change="onChange($event)" class="form-control" id="role" name="role">
            <option value="Administrador">Administrador</option>
            <option value="Mayorista">Mayorista</option>
            <option value="Comercio">Comercio</option>
            <option value="Usuario">Usuario</option>
        </select>
        <div v-if="errors.has('role')" class="form-control-feedback form-text" v-cloak>@{{errors.first('role') }}</div>
    </div>
</div>

@if($showFields == true)
    <div style="display: flex" id="commission-block" class="form-group row align-items-center" :class="{'has-danger': errors.has('commission'), 'has-success': this.fields.commission && this.fields.commission.valid }">
        <label for="commission" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Comisión</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
            <input type="number" step="0.1" v-model="form.commission" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('commission'), 'form-control-success': this.fields.commission && this.fields.commission.valid}" id="commission" name="commission" placeholder="Comisión">
            <div v-if="errors.has('commission')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('commission') }}</div>
        </div>
    </div>

    <div style="display: flex" id="discount-block" class="form-group row align-items-center" :class="{'has-danger': errors.has('discount'), 'has-success': this.fields.discount && this.fields.discount.valid }">
        <label for="discount" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Descuento</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
            <input type="number" step="0.1" v-model="form.discount" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('discount'), 'form-control-success': this.fields.discount && this.fields.discount.valid}" id="discount" name="discount" placeholder="Descuento">
            <div v-if="errors.has('discount')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('discount') }}</div>
        </div>
    </div>

@else
    <div style="display: none" id="commission-block" class="form-group row align-items-center" :class="{'has-danger': errors.has('commission'), 'has-success': this.fields.commission && this.fields.commission.valid }">
        <label for="commission" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Comisión</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
            <input type="number" step="0.1" v-model="form.commission" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('commission'), 'form-control-success': this.fields.commission && this.fields.commission.valid}" id="commission" name="commission" placeholder="Comisión">
            <div v-if="errors.has('commission')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('commission') }}</div>
        </div>
    </div>

    <div style="display: none" id="discount-block" class="form-group row align-items-center" :class="{'has-danger': errors.has('discount'), 'has-success': this.fields.discount && this.fields.discount.valid }">
        <label for="discount" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Descuento</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
            <input type="number" step="0.1" v-model="form.discount" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('discount'), 'form-control-success': this.fields.discount && this.fields.discount.valid}" id="discount" name="discount" placeholder="Descuento">
            <div v-if="errors.has('discount')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('discount') }}</div>
        </div>
    </div>
@endif
