@extends('brackets/admin-ui::admin.layout.default')

<head>
    <title>Editar perfil</title>
    <link rel="icon" href="{{URL::asset('images/nuap.png')}}"/>
</head>

@section('body')
    <div class="container-xl">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-pencil"></i> Editar Perfil
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6">
                                <profile-edit-profile-form
                                        :action="'{{ url('admin/update-profile') }}'"
                                        :data="{{ $user }}"
                                        :activation="!!'{{ $activation }}'"
                                        inline-template>
                                    <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="this.action">
                                        <div class="row">
                                            <div class="col-md-1 text-center"></div>
                                            <div class="col-md-12">
                                                <div class="form-group row align-items-center" :class="{'has-danger': errors.has('name'), 'has-success': this.fields.name && this.fields.name.valid }">
                                                    <label for="name" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-6' : 'col-md-6'">Nombres</label>
                                                    <div :class="isFormLocalized ? 'col-md-6' : 'col-md-6 col-xl-6'">
                                                        <input type="text" v-model="form.name" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('name'), 'form-control-success': this.fields.name && this.fields.name.valid}" id="name" name="name" placeholder="Nombres">
                                                        <div v-if="errors.has('name')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('name') }}</div>
                                                    </div>
                                                </div>
                                                <div class="form-group row align-items-center" :class="{'has-danger': errors.has('last_name'), 'has-success': this.fields.last_name && this.fields.last_name.valid }">
                                                    <label for="last_name" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-6' : 'col-md-6'">Apellidos</label>
                                                    <div :class="isFormLocalized ? 'col-md-6' : 'col-md-6 col-xl-6'">
                                                        <input type="text" v-model="form.last_name" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('last_name'), 'form-control-success': this.fields.last_name && this.fields.last_name.valid}" id="last_name" name="last_name" placeholder="Apellidos">
                                                        <div v-if="errors.has('last_name')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('last_name') }}</div>
                                                    </div>
                                                </div>
                                                <div class="form-group row align-items-center" :class="{'has-danger': errors.has('phone'), 'has-success': this.fields.phone && this.fields.phone.valid }">
                                                    <label for="phone" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-6' : 'col-md-6'">Teléfono</label>
                                                    <div :class="isFormLocalized ? 'col-md-6' : 'col-md-6 col-xl-6'">
                                                        <input type="text" v-model="form.phone" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('phone'), 'form-control-success': this.fields.phone && this.fields.phone.valid}" id="phone" name="phone" placeholder="Teléfono">
                                                        <div v-if="errors.has('phone')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('phone') }}</div>
                                                    </div>
                                                </div>
                                                <div class="form-group row align-items-center" :class="{'has-danger': errors.has('email'), 'has-success': this.fields.email && this.fields.email.valid }">
                                                    <label for="email" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-6' : 'col-md-6'">Correo electrónico</label>
                                                    <div :class="isFormLocalized ? 'col-md-6' : 'col-md-6 col-xl-6'">
                                                        <input type="text" v-model="form.email" v-validate="'required|email'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('email'), 'form-control-success': this.fields.email && this.fields.email.valid}" id="email" name="email" placeholder="Correo electrónico">
                                                        <div v-if="errors.has('email')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('email') }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa" :class="'fa-download'"></i>
                                                Guardar
                                            </button>
                                        </div>
                                    </form>
                                </profile-edit-profile-form>
                            </div>
                            <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6">
                                <form id="form-basic" method="post" enctype="multipart/form-data" action="{{ url('admin/update-image') }}">
                                    <input type="hidden" class="form-control" value="{{ $user_id }}" id="id" name="id">
                                    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                                    <div class="col-md-1 text-center"></div>
                                    <div class="col-md-12">
                                        <div class="form-group row align-items-center">
                                            @if($image_url != null)
                                                <img src="../../../{{ $image_url }}" alt="nuap" class="img-responsive" width="30%">
                                            @else
                                                <img src="{{URL::asset('images/nuap.jpg')}}" alt="nuap" class="img-responsive" width="30%">
                                            @endif
                                        </div>
                                        <div class="form-group row align-items-center" :class="{'has-danger': errors.has('image'), 'has-success': this.fields.image && this.fields.image.valid }">
                                            <input type="file" style="color: #b9c8de" class="" id="image" name="image" placeholder="Imagen">
                                        </div>
                                        <div class="">
                                            <button style="color: white" type="submit" class="btn btn-success btn-sm pull-right">
                                                <i class="fa" :class="'fa-download'"></i>
                                                Actualizar imagen
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection