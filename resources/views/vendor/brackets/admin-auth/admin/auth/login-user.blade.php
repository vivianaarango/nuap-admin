@extends('brackets/admin-ui::admin.layout.master')

<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
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
                                :action="'{{ url('/admin/validate') }}'"
                                :data="{}"
                                inline-template>
                            <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/validate') }}" novalidate>
                                {{ csrf_field() }}
                                <div style="padding-top: 5px" class="auth-header">
                                    <div class="center-block">
                                        <img src="{{URL::asset('images/nuap.png')}}" style="width:40%" class="img-responsive mx-auto d-block">
                                    </div>
                                    <p class="auth-subtitle">Bienvenido a Nuap</p>
                                    <p class="auth-subtitle"><br>Valida tu número de celular para poder continuar<br></p>
                                </div>
                                <div style="padding-top: 5px; padding-bottom: 2px" class="auth-body">
                                    @if (isset($send_message))
                                        <input type="hidden" id="phone_validated" name="phone_validated" value="{{ $phone_validated }}">
                                        <div class="form-group" :class="{'has-danger': errors.has('code'), 'has-success': this.fields.code && this.fields.code.valid }">
                                            <label for="code">Ingresa el código enviado</label>
                                            <div class="input-group input-group--custom">
                                                <div class="input-group-addon"><i class="input-icon input-icon--phone"></i></div>
                                                <input onkeypress="return isNumberKey(event)" type="text" v-model="form.code" v-validate="'required'" class="form-control" :class="{'form-control-danger': errors.has('code'), 'form-control-success': this.fields.code && this.fields.code.valid}" id="code" name="code" placeholder="Código">
                                            </div>
                                            <div v-if="errors.has('code')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('code') }}</div>
                                        </div>
                                        <div class="form-group">
                                            <input type="hidden" name="remember" value="1">
                                            <button type="submit" class="btn btn-primary btn-block btn-spinner"><i class="fa"></i>Verificar código</button>
                                        </div>
                                    @else
                                        <div class="form-group" :class="{'has-danger': errors.has('phone'), 'has-success': this.fields.phone && this.fields.phone.valid }">
                                            <label for="phone">Ingresa tu número de celular</label>
                                            <div class="input-group input-group--custom">
                                                <div class="input-group-addon"><i class="input-icon input-icon--phone"></i></div>
                                                <input onkeypress="return isNumberKey(event)" type="text" v-model="form.phone" v-validate="'required'" class="form-control" :class="{'form-control-danger': errors.has('phone'), 'form-control-success': this.fields.phone && this.fields.phone.valid}" id="phone" name="phone" placeholder="Celular">
                                            </div>
                                            <div v-if="errors.has('phone')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('phone') }}</div>
                                        </div>

                                        <div class="form-group">
                                            <input type="hidden" name="remember" value="1">
                                            <button type="submit" class="btn btn-primary btn-block btn-spinner"><i class="fa"></i>Enviar mensaje</button>
                                        </div>
                                    @endif
                                    @include('brackets/admin-auth::admin.auth.includes.messages')
                                    @if (isset($error))
                                        <div style="padding-top: 1px" class="">
                                            <p style="font-weight: bold;
                                                    text-align: center;
                                                    color: #e00032;
                                                    font-size: 12px;
                                                    letter-spacing: 0;
                                                    margin-bottom: 0;">
                                                <br>{{ $error }}<br></p>
                                        </div>
                                    @endif
                                    @if (isset($success))
                                        <div style="padding-top: 1px" class="">
                                            <p style="font-weight: bold;
                                                text-align: center;
                                                color: #22941c;
                                                font-size: 12px;
                                                letter-spacing: 0;
                                                margin-bottom: 0;">
                                                <br>{{ $success }}<br></p>
                                        </div>
                                    @endif
                                    @if (isset($return_login))
                                        <div class="form-group text-center">
                                            <a href="{{ url('/admin/login') }}" class="auth-ghost-link">Ingresar credenciales</a>
                                        </div>
                                    @endif
                                </div>
                            </form>
                        </auth-form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    /*function execute() {
        $.get( "https://thenuap.com/admin/validate-sms-status", function(response) {
            if (response.validate === false) {
                window.location.replace(response.redirect);
            }
        });
    }
    setTimeout(execute, 0);*/
</script>

@section('bottom-scripts')
    <script type="text/javascript">
        document.getElementById('password').dispatchEvent(new Event('input'));
    </script>
@endsection
