<div class="dropdown-menu dropdown-menu-right">
    <div class="dropdown-header text-center"><strong> Cuenta </strong></div>
    @if($activation =='Administrador')
        <a href="{{ url('admin/edit-profile') }}" class="dropdown-item"><i class="fa fa-user"></i> Perfil</a>
    @else
        <a href="{{ url('admin/edit-profile-distributor') }}" class="dropdown-item"><i class="fa fa-user"></i> Perfil</a>
    @endif
    <a href="{{ url('admin/edit-password') }}" class="dropdown-item"><i class="fa fa-key"></i> Contraseña</a>
    <a href="{{ url('admin/user-logout') }}" onclick="clearSession()" class="dropdown-item"><i class="fa fa-lock"></i> Cerrar sesión</a>
</div>

<script>
    function clearSession() {
        sessionStorage.removeItem('active');
    }
</script>