@extends('brackets/admin-ui::admin.layout.master')

<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Login</title>
    <link rel="icon" href="{{URL::asset('images/nuap.png')}}"/>
</head>

@section('content')
    <div class="container" id="app">
        <div class="row align-items-center justify-content-center auth">
            <div class="col-md-6 col-lg-5">
                <div class="card">
                    <div class="card-block">
                        <auth-form
                            :action="'{{ url('/admin/user-login') }}'"
                            :data="{}"
                            inline-template>
                            <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/user-login') }}" novalidate>
                                {{ csrf_field() }}
                                <div class="auth-header">
                                    <div class="center-block">
                                        <img src="{{URL::asset('images/nuap.png')}}" style="width:40%" class="img-responsive mx-auto d-block">
                                    </div>
                                    <p class="auth-subtitle">Bienvenido a Nuap</p>
                                    <p class="auth-subtitle"><br>Usa tus credenciales para acceder a la plataforma<br></p>
                                </div>
                                <div class="auth-body">
                                    @include('brackets/admin-auth::admin.auth.includes.messages')
                                    <div class="form-group" :class="{'has-danger': errors.has('email'), 'has-success': this.fields.email && this.fields.email.valid }">
                                        <label for="email">Correo electrónico</label>
                                        <div class="input-group input-group--custom">
                                            <div class="input-group-addon"><i class="input-icon input-icon--mail"></i></div>
                                            <input type="text" v-model="form.email" v-validate="'required|email'" class="form-control" :class="{'form-control-danger': errors.has('email'), 'form-control-success': this.fields.email && this.fields.email.valid}" id="email" name="email" placeholder="Correo electrónico">
                                        </div>
                                        <div v-if="errors.has('email')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('email') }}</div>
                                    </div>

                                    <div class="form-group" :class="{'has-danger': errors.has('password'), 'has-success': this.fields.password && this.fields.password.valid }">
                                        <label for="password">Contraseña</label>
                                        <div class="input-group input-group--custom">
                                            <div class="input-group-addon"><i class="input-icon input-icon--lock"></i></div>
                                            <input type="password" v-model="form.password" class="form-control" :class="{'form-control-danger': errors.has('password'), 'form-control-success': this.fields.password && this.fields.password.valid}" id="password" name="password" placeholder="Contraseña">
                                        </div>
                                        <div v-if="errors.has('password')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('password') }}</div>
                                        <small class="form-text text-muted">
                                            La contraseña debe contener minimo 8 caracteres, una mayuscula, un número y un carácter especial.
                                        </small>
                                    </div>

                                    <div class="form-group">
                                        <input type="hidden" name="remember" value="1">
                                        <button type="submit" class="btn btn-primary btn-block btn-spinner"><i class="fa"></i>Login</button>
                                    </div>
                                    <div class="form-group text-center">
                                        <a href="{{ url('/admin/password-reset') }}" class="auth-ghost-link">¿Olvidaste tu contraseña?</a>
                                    </div>
                                </div>
                            </form>
                        </auth-form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('bottom-scripts')
    <script type="text/javascript">
        document.getElementById('password').dispatchEvent(new Event('input'));
    </script>
@endsection
