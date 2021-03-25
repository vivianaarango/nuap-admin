@extends('brackets/admin-ui::admin.layout.default')

<head>
    <title>Cambiar contraseña</title>
    <link rel="icon" href="{{URL::asset('images/nuap.png')}}"/>
</head>

@section('body')

    <div class="container-xl">

        <div class="card">

            <profile-edit-password-form
                :action="'{{ url('admin/update-password') }}'"
                :data="{{ $user->toJson() }}"
                :activation="!!'{{ $activation }}'"

                inline-template>

                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="this.action">

                    <div class="card-header">
                        <i class="fa fa-pencil"></i> Cambiar contraseña
                    </div>

                    <div class="card-body">
                        <div class="form-group row align-items-center" :class="{'has-danger': errors.has('password'), 'has-success': this.fields.password && this.fields.password.valid }">
                            <label for="password" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Nueva contraseña</label>
                            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
                                <input type="password" v-model="form.password" v-validate="'min:8|required'"  @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('password'), 'form-control-success': this.fields.password && this.fields.password.valid}" id="password" name="password" placeholder="Nueva contraseña" ref="password">
                                <div v-if="errors.has('password')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('password') }}</div>
                                <small class="form-text text-muted">
                                    La contraseña debe contener minimo 8 caracteres, una mayuscula, un número y un carácter especial.
                                </small>
                            </div>
                        </div>
                        <div class="form-group row align-items-center" :class="{'has-danger': errors.has('password_confirmation'), 'has-success': this.fields.password_confirmation && this.fields.password_confirmation.valid }">
                            <label for="password_confirmation" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Confirma tu contraseña</label>
                            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
                                <input type="password" v-model="form.password_confirmation" v-validate="'required|confirmed:password|min:8'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('password_confirmation'), 'form-control-success': this.fields.password_confirmation && this.fields.password_confirmation.valid}" id="password_confirmation" name="password_confirmation" placeholder="Confirma tu contraseña" data-vv-as="password">
                                <div v-if="errors.has('password_confirmation')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('password_confirmation') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button id="save-password" type="submit" class="btn btn-primary">
                            <i class="fa" :class="'fa-download'"></i>
                            Guardar
                        </button>
                    </div>

                </form>

            </profile-edit-password-form>

        </div>

    </div>

@endsection
