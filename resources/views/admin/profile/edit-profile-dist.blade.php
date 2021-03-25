@extends('brackets/admin-ui::admin.layout.default')

<head>
    <title>Perfil</title>
    <link rel="icon" href="{{URL::asset('images/nuap.png')}}"/>
</head>

@section('body')

    <div class="container-xl">
        <div class="card">
            <profile-edit-profile-form
                    :action="'{{ url('admin/update-profile') }}'"
                    :data="{{ $user }}"
                    :activation="!!'{{ $activation }}'"
                    inline-template>

                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="this.action">
                    <div class="card-header">
                        <i class="fa fa-pencil"></i> Perfil
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2 text-center"></div>
                            <div class="col-md-8">
                                <div class="form-group row align-items-center" :class="{'has-danger': errors.has('business_name'), 'has-success': this.fields.business_name && this.fields.business_name.valid }">
                                    <label for="business_name" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Razón Social</label>
                                    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
                                        <input disabled type="text" v-model="form.business_name" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('business_name'), 'form-control-success': this.fields.business_name && this.fields.business_name.valid}" id="business_name" name="business_name" placeholder="Razón Social">
                                        <div v-if="errors.has('business_name')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('business_name') }}</div>
                                    </div>
                                </div>
                                <div class="form-group row align-items-center" :class="{'has-danger': errors.has('nit'), 'has-success': this.fields.nit && this.fields.nit.valid }">
                                    <label for="nit" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Nit</label>
                                    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
                                        <input disabled type="text" v-model="form.nit" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('nit'), 'form-control-success': this.fields.nit && this.fields.nit.valid}" id="nit" name="nit" placeholder="Nit">
                                        <div v-if="errors.has('nit')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('nit') }}</div>
                                    </div>
                                </div>

                                <div class="form-group row align-items-center" :class="{'has-danger': errors.has('phone'), 'has-success': this.fields.phone && this.fields.phone.valid }">
                                    <label for="phone" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Teléfono</label>
                                    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
                                        <input disabled type="text" v-model="form.phone" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('phone'), 'form-control-success': this.fields.phone && this.fields.phone.valid}" id="phone" name="phone" placeholder="Teléfono">
                                        <div v-if="errors.has('phone')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('phone') }}</div>
                                    </div>
                                </div>

                                <div class="form-group row align-items-center" :class="{'has-danger': errors.has('email'), 'has-success': this.fields.email && this.fields.email.valid }">
                                    <label for="email" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Correo electrónico</label>
                                    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
                                        <input disabled type="text" v-model="form.email" v-validate="'required|email'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('email'), 'form-control-success': this.fields.email && this.fields.email.valid}" id="email" name="email" placeholder="Correo electrónico">
                                        <div v-if="errors.has('email')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('email') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </profile-edit-profile-form>
            <div class="card-footer">
                <a style="color: white" class="btn btn-success btn-spinner btn-sm pull-right m-b-0" href="{{ url('admin/payment-create') }}" role="button"><i class="fa fa-money"></i>&nbsp; Cuenta Bancaria</a>
            </div>
        </div>
    </div>

@endsection