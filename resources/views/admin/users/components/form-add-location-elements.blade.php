<div class="form-group row align-items-center" :class="{'has-danger': errors.has('city'), 'has-success': this.fields.city && this.fields.city.valid }">
    <div class="col-md-2"></div>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <select class="form-control" id="city" name="city" required>
            <option value="Arauca">Arauca</option>
            <option value="Armenia">Armenia</option>
            <option value="Barranquilla">Barranquilla</option>
            <option value="Bogotá">Bogotá</option>
            <option value="Bucaramanga">Bucaramanga</option>
            <option value="Cali">Cali</option>
            <option value="Cartagena">Cartagena</option>
            <option value="Cúcuta">Cúcuta</option>
            <option value="Florencia">Florencia</option>
            <option value="Ibagué">Ibagué</option>
            <option value="Leticia">Leticia</option>
            <option value="Manizales">Manizales</option>
            <option value="Medellín">Medellín</option>
            <option value="Mitú">Mitú</option>
            <option value="Mocoa">Mocoa</option>
            <option value="Montería">Montería</option>
            <option value="Neiva">Neiva</option>
            <option value="Pasto">Pasto</option>
            <option value="Pereira">Pereira</option>
            <option value="Popayán">Popayán</option>
            <option value="Puerto Carreño">Puerto Carreño</option>
            <option value="Puerto Inírida">Puerto Inírida</option>
            <option value="Quibdó">Quibdó</option>
            <option value="Riohacha">Riohacha</option>
            <option value="San Andrés">San Andrés</option>
            <option value="San José del Guaviare">San José del Guaviare</option>
            <option value="Santa Marta">Santa Marta</option>
            <option value="Sincelejo">Sincelejo</option>
            <option value="Tunja">Tunja</option>
            <option value="Valledupar">Valledupar</option>
            <option value="Villavicencio">Villavicencio</option>
            <option value="Yopal">Yopal</option>
        </select>
        <div v-if="errors.has('city')" class="form-control-feedback form-text" v-cloak>@{{errors.first('city') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('location'), 'has-success': this.fields.location && this.fields.location.valid }">
    <div class="col-md-2"></div>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <input type="text" required class="form-control" :class="{'form-control-danger': errors.has('location'), 'form-control-success': this.fields.location && this.fields.location.valid}" id="location" name="location" placeholder="Localidad">
        <div v-if="errors.has('location')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('location') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('neighborhood'), 'has-success': this.fields.neighborhood && this.fields.neighborhood.valid }">
    <div class="col-md-2"></div>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <input type="text" required class="form-control" :class="{'form-control-danger': errors.has('neighborhood'), 'form-control-success': this.fields.neighborhood && this.fields.neighborhood.valid}" id="neighborhood" name="neighborhood" placeholder="Barrio">
        <div v-if="errors.has('neighborhood')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('neighborhood') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('address'), 'has-success': this.fields.address && this.fields.address.valid }">
    <div class="col-md-2"></div>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <input type="text" required class="form-control" :class="{'form-control-danger': errors.has('address'), 'form-control-success': this.fields.address && this.fields.address.valid}" id="address" name="address" placeholder="Dirección">
        <div v-if="errors.has('address')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('address') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" >
    <div class="col-md-2"></div>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <input type="hidden" id="latitude" name="latitude">
        <input type="hidden" id="longitude" name="longitude">
        <div class="map-style">
            <div class="content-map" id="map"></div>
        </div>
    </div>
</div>