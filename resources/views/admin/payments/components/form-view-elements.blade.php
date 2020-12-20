<div class="form-group row align-items-center" :class="{'has-danger': errors.has('value'), 'has-success': this.fields.value && this.fields.value.valid }">
    <label for="value" class="col-form-label text-md-right" :class="'col-md-5'"><b>Valor a pagar:</b></label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <label for="value" class="col-form-label text-md-right">{{ $payment['value'] }}</label>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('request_date'), 'has-success': this.fields.request_date && this.fields.request_date.valid }">
    <label for="request_date" class="col-form-label text-md-right" :class="'col-md-5'"><b>Fecha de petición:</b></label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <label for="request_date" class="col-form-label text-md-right">{{ $payment['request_date'] }}</label>
    </div>
</div>

@if ($payment['payment_date'] != null)
    <div class="form-group row align-items-center" :class="{'has-danger': errors.has('payment_date'), 'has-success': this.fields.payment_date && this.fields.payment_date.valid }">
        <label for="payment_date" class="col-form-label text-md-right" :class="'col-md-5'"><b>Fecha de pago:</b></label>
        <div :class="'col-md-4 col-md-9 col-xl-7'">
            <label for="payment_date" class="col-form-label text-md-right">{{ $payment['payment_date'] }}</label>
        </div>
    </div>
@endif

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('bank'), 'has-success': this.fields.bank && this.fields.bank.valid }">
    <label for="bank" class="col-form-label text-md-right" :class="'col-md-5'"><b>Banco:</b></label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <label for="bank" class="col-form-label text-md-right">{{ $account['bank'] }}</label>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('account'), 'has-success': this.fields.account && this.fields.account.valid }">
    <label for="account" class="col-form-label text-md-right" :class="'col-md-5'"><b>Número de cuenta:</b></label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <label for="account" class="col-form-label text-md-right">{{ $account['account'] }}</label>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('account_type'), 'has-success': this.fields.account_type && this.fields.account_type.valid }">
    <label for="account_type" class="col-form-label text-md-right" :class="'col-md-5'"><b>Tipo de cuenta:</b></label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <label for="account_type" class="col-form-label text-md-right">{{ $account['account_type'] }}</label>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('owner_name'), 'has-success': this.fields.owner_name && this.fields.owner_name.valid }">
    <label for="owner_name" class="col-form-label text-md-right" :class="'col-md-5'"><b>Titular:</b></label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <label for="owner_name" class="col-form-label text-md-right">{{ $account['owner_name'] }}</label>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('owner_document'), 'has-success': this.fields.owner_document && this.fields.owner_document.valid }">
    <label for="owner_document" class="col-form-label text-md-right" :class="'col-md-5'"><b>Documento del Titular:</b></label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <label for="owner_document" class="col-form-label text-md-right">{{ $account['owner_document'] }}</label>
    </div>
</div>

