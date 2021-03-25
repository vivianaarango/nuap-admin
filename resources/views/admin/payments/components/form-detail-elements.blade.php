<div class="form-group row align-items-center" :class="{'has-danger': errors.has('status'), 'has-success': this.fields.status && this.fields.status.valid }">
    <label for="status" class="col-form-label text-md-right" :class="'col-md-6'"><b>Estado:</b></label>
    <div :class="'col-md-6'">
        @if($payment['status'] === 'Cancelado')
            <button disabled style="color: white" class="btn btn-sm btn-status-cancel"><i></i>&nbspCancelado</button>
        @endif
        @if($payment['status'] === 'Rechazado')
            <button disabled style="color: white" class="btn btn-sm btn-danger"><i></i>&nbspRechazado</button>
        @endif
        @if($payment['status'] === 'Aprobado')
            <button disabled style="color: white" class="btn btn-sm btn-success"><i></i>&nbspAprobado</button>
        @endif
        @if($payment['status'] === 'Pendiente')
            <button disabled style="color: white" class="btn btn-sm btn-info"><i></i>&nbspPendiente</button>
        @endif
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('value'), 'has-success': this.fields.value && this.fields.value.valid }">
    <label for="value" class="col-form-label text-md-right" :class="'col-md-6'"><b>Valor a pagar:</b></label>
    <div :class="'col-md-6'">
        <label for="value" class="col-form-label text-md-right">{{ $payment['value'] }}</label>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('request_date'), 'has-success': this.fields.request_date && this.fields.request_date.valid }">
    <label for="request_date" class="col-form-label text-md-right" :class="'col-md-6'"><b>Fecha de petición:</b></label>
    <div :class="'col-md-6'">
        <label for="request_date" class="col-form-label text-md-right">{{ $payment['request_date'] }}</label>
    </div>
</div>

@if ($payment['payment_date'] != null)
    <div class="form-group row align-items-center" :class="{'has-danger': errors.has('payment_date'), 'has-success': this.fields.payment_date && this.fields.payment_date.valid }">
        <label for="payment_date" class="col-form-label text-md-right" :class="'col-md-6'"><b>Fecha de pago:</b></label>
        <div :class="'col-md-6'">
            <label for="payment_date" class="col-form-label text-md-right">{{ $payment['payment_date'] }}</label>
        </div>
    </div>
@endif

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('bank'), 'has-success': this.fields.bank && this.fields.bank.valid }">
    <label for="bank" class="col-form-label text-md-right" :class="'col-md-6'"><b>Banco:</b></label>
    <div :class="'col-md-6'">
        <label for="bank" class="col-form-label text-md-right">{{ $account['bank'] }}</label>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('account'), 'has-success': this.fields.account && this.fields.account.valid }">
    <label for="account" class="col-form-label text-md-right" :class="'col-md-6'"><b>Número de cuenta:</b></label>
    <div :class="'col-md-6'">
        <label for="account" class="col-form-label text-md-right">{{ $account['account'] }}</label>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('account_type'), 'has-success': this.fields.account_type && this.fields.account_type.valid }">
    <label for="account_type" class="col-form-label text-md-right" :class="'col-md-6'"><b>Tipo de cuenta:</b></label>
    <div :class="'col-md-6'">
        <label for="account_type" class="col-form-label text-md-right">{{ $account['account_type'] }}</label>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('owner_name'), 'has-success': this.fields.owner_name && this.fields.owner_name.valid }">
    <label for="owner_name" class="col-form-label text-md-right" :class="'col-md-6'"><b>Titular:</b></label>
    <div :class="'col-md-6'">
        <label for="owner_name" class="col-form-label text-md-right">{{ $account['owner_name'] }}</label>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('owner_document'), 'has-success': this.fields.owner_document && this.fields.owner_document.valid }">
    <label for="owner_document" class="col-form-label text-md-right" :class="'col-md-6'"><b>Documento del Titular:</b></label>
    <div :class="'col-md-6'">
        <label for="owner_document" class="col-form-label text-md-right">{{ $account['owner_document'] }}</label>
    </div>
</div>

@if($payment['status'] === 'Aprobado')
    <div class="form-group row align-items-center" :class="{'has-danger': errors.has('voucher'), 'has-success': this.fields.voucher && this.fields.voucher.valid }">
        <label for="voucher" class="col-form-label text-md-right" :class="'col-md-6'"><b>Comprobante de Pago:</b></label>
        <div :class="'col-md-6'">
            @if($payment['voucher'] != null)
                <a style="background-color: #60abcf !important;border-color: #60b5cf !important;" target="_blank" class="btn btn-sm btn-link-documents" href="../../../{{ $payment['voucher'] }}" class="col-auto" href="item.resource_url+'/add-voucher'" title="Ver" role="button">
                    <i class="fa fa-mail-forward"></i>
                </a>
            @endif
            <div v-if="errors.has('voucher')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('voucher') }}</div>
        </div>
    </div>
@endif

