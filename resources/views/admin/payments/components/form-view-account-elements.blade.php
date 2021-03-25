<div class="form-group row align-items-center" :class="{'has-danger': errors.has('status'), 'has-success': this.fields.status && this.fields.status.valid }">
    <label for="status" class="col-form-label text-md-right" :class="'col-md-6'"><b>Estado:</b></label>
    <div :class="'col-md-4 col-md-6 col-xl-6'">
        @if($account['status'] === 1)
            <button disabled style="color: white" class="btn btn-sm btn-success"><i></i>&nbspAprobada</button>
        @endif
        @if($account['status'] === 0)
            <button disabled style="color: white" class="btn btn-sm btn-danger"><i></i>&nbspPendiente</button>
        @endif
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('bank'), 'has-success': this.fields.bank && this.fields.bank.valid }">
    <label for="bank" class="col-form-label text-md-right" :class="'col-md-6'"><b>Banco:</b></label>
    <div :class="'col-md-4 col-md-6 col-xl-6'">
        <label for="bank" class="col-form-label text-md-right">{{ $account['bank'] }}</label>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('account'), 'has-success': this.fields.account && this.fields.account.valid }">
    <label for="account" class="col-form-label text-md-right" :class="'col-md-6'"><b>Número de cuenta:</b></label>
    <div :class="'col-md-4 col-md-6 col-xl-6'">
        <label for="account" class="col-form-label text-md-right">{{ $account['account'] }}</label>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('account_type'), 'has-success': this.fields.account_type && this.fields.account_type.valid }">
    <label for="account_type" class="col-form-label text-md-right" :class="'col-md-6'"><b>Tipo de cuenta:</b></label>
    <div :class="'col-md-4 col-md-6 col-xl-6'">
        <label for="account_type" class="col-form-label text-md-right">{{ $account['account_type'] }}</label>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('owner_name'), 'has-success': this.fields.owner_name && this.fields.owner_name.valid }">
    <label for="owner_name" class="col-form-label text-md-right" :class="'col-md-6'"><b>Titular:</b></label>
    <div :class="'col-md-4 col-md-6 col-xl-6'">
        <label for="owner_name" class="col-form-label text-md-right">{{ $account['owner_name'] }}</label>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('owner_document_type'), 'has-success': this.fields.owner_document_type && this.fields.owner_document_type.valid }">
    <label for="owner_document_type" class="col-form-label text-md-right" :class="'col-md-6'"><b>Tipo de Documento:</b></label>
    <div :class="'col-md-4 col-md-6 col-xl-6'">
        <label for="owner_document_type" class="col-form-label text-md-right">{{ $account['owner_document_type'] }}</label>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('owner_document'), 'has-success': this.fields.owner_document && this.fields.owner_document.valid }">
    <label for="owner_document" class="col-form-label text-md-right" :class="'col-md-6'"><b>Documento del Titular:</b></label>
    <div :class="'col-md-4 col-md-6 col-xl-6'">
        <label for="owner_document" class="col-form-label text-md-right">{{ $account['owner_document'] }}</label>
    </div>
</div>

@if($account['certificate'] != null)
    <div class="form-group row align-items-center" :class="{'has-danger': errors.has('certificate'), 'has-success': this.fields.certificate && this.fields.certificate.valid }">
        <label for="certificate" class="col-form-label text-md-right" :class="'col-md-6'"><b>Certificado bancario:</b></label>
        <div :class="'col-md-4 col-md-6 col-xl-6'">
            <a style="background-color: #60abcf !important;border-color: #60b5cf !important;" target="_blank" class="btn btn-sm btn-link-documents" href="../../../{{ $account['certificate'] }}" class="col-auto" title="Ver" role="button">
                <i class="fa fa-mail-forward"></i>
            </a>
            <div v-if="errors.has('certificate')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('certificate') }}</div>
        </div>
    </div>
@endif