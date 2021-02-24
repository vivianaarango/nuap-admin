<div class="sidebar">
    <div id="role" style="visibility: hidden">{{ $activation }}</div>
    @if($activation == 'Administrador')
        <nav class="sidebar-nav">
            <ul class="nav">
                <li class="nav-title">Administradores</li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/list') }}"><i class="nav-icon icon-list"></i>Lista Administradores</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/admin-users-create') }}"><i class="nav-icon icon-pencil"></i>Nuevo Administrador</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/config-create') }}"><i class="nav-icon icon-settings"></i>Configuración</a></li>
                <li class="nav-title">Usuarios</li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/distributor-list') }}"><i class="nav-icon icon-grid"></i>Distribuidores</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/distributor-edit-commission') }}"><i class="nav-icon icon-pin"></i>Comisiones Distribuidores</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/commerce-list') }}"><i class="nav-icon icon-handbag"></i>Comercios</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/commerce-edit-commission') }}"><i class="nav-icon icon-pin"></i>Comisiones Comercios</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/client-list') }}"><i class="nav-icon icon-user"></i>Clientes</a></li>
                <li class="nav-title">Productos</li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/product-list') }}"><i class="nav-icon icon-basket"></i>Inventario</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/product-edit-discount') }}"><i class="nav-icon icon-refresh"></i>Descuentos</a></li>
                <li class="nav-title">Pagos</li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/payment-admin-list') }}"><i class="nav-icon icon-wallet"></i>Solicitudes</a></li>
                <li class="nav-title">Soporte</li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/ticket-admin-list') }}"><i class="nav-icon icon-envelope"></i>Tickets</a></li>
                <li class="nav-title">Reportes</li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/report/new-users') }}"><i class="nav-icon icon-chart"></i>Usuarios Nuevos</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/report/login-users') }}"><i class="nav-icon icon-login"></i>Inicio de Sesión</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/report/export-payments') }}"><i class="nav-icon icon-wallet"></i>Pagos Realizados</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/report/export-pending-payments') }}"><i class="nav-icon icon-clock"></i>Pagos Pendientes</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/report/sales') }}"><i class="nav-icon icon-handbag"></i>Ventas Mensuales</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/report/export-all-sales') }}"><i class="nav-icon icon-basket-loaded"></i>Ventas Generales</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/report/tickets') }}"><i class="nav-icon icon-pie-chart"></i>Tickets</a></li>
            </ul>
        </nav>
        <button class="sidebar-minimizer brand-minimizer" type="button"></button>
    @else
        <nav class="sidebar-nav">
            <ul class="nav">
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/distributor') }}"><i class="nav-icon icon-layers"></i>Dashboard</a></li>
                <li class="nav-title">Productos</li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/product-distributor-list') }}"><i class="nav-icon icon-plus"></i>Inventario</a></li>
                <li class="nav-title">Pagos</li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/payment-list') }}"><i class="nav-icon icon-grid"></i>Consultar</a></li>
                <li class="nav-title">Ventas</li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/order-list') }}"><i class="nav-icon icon-basket"></i>Consultar</a></li>
                <li class="nav-title">Soporte</li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/ticket-list') }}"><i class="nav-icon icon-envelope"></i>Tickets</a></li>
            </ul>
        </nav>
        <button class="sidebar-minimizer brand-minimizer" type="button"></button>
    @endif
</div>

<script>
    window.onload = function()
    {
        document.getElementsByClassName("hidden-md-down")[0].innerHTML = document.getElementById('role').textContent
    }
</script>