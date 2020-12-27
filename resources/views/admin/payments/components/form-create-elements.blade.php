<div class="form-group row align-items-center" :class="{'has-danger': errors.has('bank'), 'has-success': this.fields.bank && this.fields.bank.valid }">
    <label for="bank" class="col-form-label text-md-right" :class="'col-md-3'">Banco</label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <select class="form-control" id="bank" name="bank" required>
            <option disabled selected>Banco</option>
            <option value="BANCAMIA S.A.">BANCAMIA S.A.</option>
            <option value="BANCO AGRARIO">BANCO AGRARIO</option>
            <option value="BANCO AV VILLAS">BANCO AV VILLAS</option>
            <option value="BANCO BBVA COLOMBIA S.A.">BANCO BBVA COLOMBIA S.A.</option>
            <option value="BANCO CAJA SOCIAL">BANCO CAJA SOCIAL</option>
            <option value="BANCO COOPERATIVO COOPCENTRAL">BANCO COOPERATIVO COOPCENTRAL</option>
            <option value="BANCO DAVIVIENDA">BANCO DAVIVIENDA</option>
            <option value="BANCO DE BOGOTA">BANCO DE BOGOTA</option>
            <option value="BANCO DE OCCIDENTE">BANCO DE OCCIDENTE</option>
            <option value="BANCO FALABELLA">BANCO FALABELLA </option>
            <option value="BANCO GNB SUDAMERIS">BANCO GNB SUDAMERIS</option>
            <option value="BANCO ITAU">BANCO ITAU</option>
            <option value="BANCO PICHINCHA S.A.">BANCO PICHINCHA S.A.</option>
            <option value="BANCO POPULAR">BANCO POPULAR</option>
            <option value="BANCO PROCREDIT">BANCO PROCREDIT</option>
            <option value="BANCO SANTANDER COLOMBIA">BANCO SANTANDER COLOMBIA</option>
            <option value="BANCO SERFINANZA">BANCO SERFINANZA</option>
            <option value="BANCOLOMBIA">BANCOLOMBIA</option>
            <option value="BANCOOMEVA S.A">BANCOOMEVA S.A.</option>
            <option value="CFA COOPERATIVA FINANCIERA">CFA COOPERATIVA FINANCIERA</option>
            <option value="CITIBANK">CITIBANK </option>
            <option value="CONFIAR COOPERATIVA FINANCIERA">CONFIAR COOPERATIVA FINANCIERA</option>
            <option value="NEQUI">NEQUI</option>
            <option value="RAPPIPAY">RAPPIPAY</option>
            <option value="SCOTIABANK COLPATRIA">SCOTIABANK COLPATRIA</option>
        </select>
        <div v-if="errors.has('bank')" class="form-control-feedback form-text" v-cloak>@{{errors.first('bank') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('account'), 'has-success': this.fields.account && this.fields.account.valid }">
    <label for="account" class="col-form-label text-md-right" :class="'col-md-3'">Número de cuenta</label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        @if(isset($account->account))
            <input value="{{ $account->account }}" type="text" onkeypress="return isNumberKey(event)" required class="form-control" :class="{'form-control-danger': errors.has('account'), 'form-control-success': this.fields.account && this.fields.account.valid}" id="account" name="account" placeholder="Número de cuenta">
        @else
            <input type="text" onkeypress="return isNumberKey(event)" required class="form-control" :class="{'form-control-danger': errors.has('account'), 'form-control-success': this.fields.account && this.fields.account.valid}" id="account" name="account" placeholder="Número de cuenta">
        @endif
        <div v-if="errors.has('account')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('account') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('account_type'), 'has-success': this.fields.account_type && this.fields.account_type.valid }">
    <label for="account_type" class="col-form-label text-md-right" :class="'col-md-3'">Tipo de Cuenta</label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <select required class="form-control" id="account_type" name="account_type">
            <option disabled selected>Tipo de Cuenta</option>
            <option value="Ahorros">Ahorros</option>
            <option value="Corriente">Corriente</option>
            <option value="No aplica">No aplica</option>
        </select>
        <div v-if="errors.has('account_type')" class="form-control-feedback form-text" v-cloak>@{{errors.first('account_type') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('owner_name'), 'has-success': this.fields.owner_name && this.fields.owner_name.valid }">
    <label for="owner_name" class="col-form-label text-md-right" :class="'col-md-3'">Titular</label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        @if(isset($account->owner_name))
            <input value="{{ $account->owner_name }}" type="text" required class="form-control" :class="{'form-control-danger': errors.has('owner_name'), 'form-control-success': this.fields.owner_name && this.fields.owner_name.valid}" id="owner_name" name="owner_name" placeholder="Titular">
        @else
            <input type="text" required class="form-control" :class="{'form-control-danger': errors.has('owner_name'), 'form-control-success': this.fields.owner_name && this.fields.owner_name.valid}" id="owner_name" name="owner_name" placeholder="Titular">
        @endif
        <div v-if="errors.has('owner_name')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('owner_name') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('owner_document_type'), 'has-success': this.fields.owner_document_type && this.fields.owner_document_type.valid }">
    <label for="owner_document_type" class="col-form-label text-md-right" :class="'col-md-3'">Tipo de Documento del Titular</label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <select required class="form-control" id="owner_document_type" name="owner_document_type">
            <option disabled selected>Tipo de Documento del Titular</option>
            <option value="Cédula">Cédula</option>
            <option value="NIT">NIT</option>
            <option value="Cédula de Extranjería">Cédula de Extranjería</option>
            <option value="Pasaporte">Pasaporte</option>
        </select>
        <div v-if="errors.has('owner_document_type')" class="form-control-feedback form-text" v-cloak>@{{errors.first('owner_document_type') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('owner_document'), 'has-success': this.fields.owner_document && this.fields.owner_document.valid }">
    <label for="owner_document" class="col-form-label text-md-right" :class="'col-md-3'">Documento del Titular</label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        @if(isset($account->owner_document))
            <input value="{{ $account->owner_document }}" type="text" onkeypress="return isNumberKey(event)" required class="form-control" :class="{'form-control-danger': errors.has('owner_document'), 'form-control-success': this.fields.owner_document && this.fields.owner_document.valid}" id="owner_document" name="owner_document" placeholder="Documento del Titular">
        @else
            <input type="text" onkeypress="return isNumberKey(event)" required class="form-control" :class="{'form-control-danger': errors.has('owner_document'), 'form-control-success': this.fields.owner_document && this.fields.owner_document.valid}" id="owner_document" name="owner_document" placeholder="Documento del Titular">
        @endif
        <div v-if="errors.has('owner_document')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('owner_document') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('certificate'), 'has-success': this.fields.certificate && this.fields.certificate.valid }">
    <label for="certificate" class="col-form-label text-md-right" :class="'col-md-3'">Certificado</label>
    <div :class="'col-md-4 col-md-8 col-xl-6'">
        <input type="file" style="color: #b9c8de" class="" :class="{'form-control-danger': errors.has('certificate'), 'form-control-success': this.fields.certificate && this.fields.certificate.valid}" id="certificate" name="certificate" placeholder="Certificado">
        <div v-if="errors.has('certificate')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('certificate') }}</div>
    </div>
    @if( isset($account->certificate))
        <a style="background-color: #60abcf !important;border-color: #60b5cf !important;" target="_blank" class="btn btn-sm btn-link-documents" href="../../../{{ $account->certificate }}" class="col-auto" href="item.resource_url+'/add-document'" title="Ver" role="button">
            <i class="fa fa-mail-forward"></i>
        </a>
    @endif
</div>
