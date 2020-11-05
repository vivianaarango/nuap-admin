@extends('brackets/admin-ui::admin.layout.default')

<head>
    <title>Editar perfil</title>
</head>

@section('body')

    <div class="container-xl">

        <div class="card">

            <profile-edit-profile-form
                :action="'{{ url('admin/update-profile') }}'"
                :data="{{ $user->toJson() }}"
                :activation="!!'{{ $activation }}'"
                
                inline-template>

                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="this.action">

                    <div class="card-header">
                        <i class="fa fa-pencil"></i> Editar perfil
                    </div>

                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-4 text-center">
                                <div class="avatar-upload">
                                    @include('brackets/admin-ui::admin.includes.avatar-uploader', [
                                        'mediaCollection' => app(\app\Models\User::class)->getMediaCollection('avatar'),
                                        'media' => $user->getThumbs200ForCollection('avatar')
                                    ])
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group row align-items-center" :class="{'has-danger': errors.has('name'), 'has-success': this.fields.name && this.fields.name.valid }">
                                    <label for="name" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Nombres</label>
                                    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
                                        <input type="text" v-model="form.name" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('name'), 'form-control-success': this.fields.name && this.fields.name.valid}" id="name" name="name" placeholder="Nombres">
                                        <div v-if="errors.has('name')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('name') }}</div>
                                    </div>
                                </div>
                                
                                <div class="form-group row align-items-center" :class="{'has-danger': errors.has('lastname'), 'has-success': this.fields.lastname && this.fields.lastname.valid }">
                                    <label for="lastname" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Apellidos</label>
                                    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
                                        <input type="text" v-model="form.lastname" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('lastname'), 'form-control-success': this.fields.lastname && this.fields.lastname.valid}" id="lastname" name="lastname" placeholder="Apellidos">
                                        <div v-if="errors.has('lastname')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('lastname') }}</div>
                                    </div>
                                </div>

                                <div class="form-group row align-items-center" :class="{'has-danger': errors.has('phone'), 'has-success': this.fields.phone && this.fields.phone.valid }">
                                    <label for="phone" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Teléfono</label>
                                    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
                                        <input type="text" v-model="form.phone" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('phone'), 'form-control-success': this.fields.phone && this.fields.phone.valid}" id="phone" name="phone" placeholder="Teléfono">
                                        <div v-if="errors.has('phone')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('phone') }}</div>
                                    </div>
                                </div>
                                
                                <div class="form-group row align-items-center" :class="{'has-danger': errors.has('email'), 'has-success': this.fields.email && this.fields.email.valid }">
                                    <label for="email" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">Correo electrónico</label>
                                    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
                                        <input type="text" v-model="form.email" v-validate="'required|email'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('email'), 'form-control-success': this.fields.email && this.fields.email.valid}" id="email" name="email" placeholder="Correo electrónico">
                                        <div v-if="errors.has('email')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('email') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            Guardar
                        </button>
                    </div>

                </form>

            </profile-edit-profile-form>

        </div>

    </div>

@endsection