<div class="sidebar">
    <div id="role" style="visibility: hidden">{{ $activation }}</div>
    @if($activation == 'Administrador')
        <nav class="sidebar-nav">
            <ul class="nav">
                <li class="nav-title">Administradores</li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/admin-users-create') }}"><i class="nav-icon icon-plus"></i>Nuevo administrador</a></li>
                <li class="nav-title">Usuarios</li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/distributor-list') }}"><i class="nav-icon icon-grid"></i>Distribuidores</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/distributor-edit-commission') }}"><i class="nav-icon icon-pin"></i>Comisiones Distribuidores</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/commerce-list') }}"><i class="nav-icon icon-handbag"></i>Comercios</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/client-list') }}"><i class="nav-icon icon-user"></i>Clientes</a></li>
                <li class="nav-title">Productos</li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/product-list') }}"><i class="nav-icon icon-plus"></i>Inventario</a></li>
                <!--<li class="nav-item"><a class="nav-link" href="{{ url('admin/posts') }}"><i class="nav-icon icon-globe"></i> #2: With media</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/translatable-articles') }}"><i class="nav-icon icon-ghost"></i> #3: Translatable</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/exports') }}"><i class="nav-icon icon-drop"></i> #4: With export</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/articles-with-relationships') }}"><i class="nav-icon icon-graduation"></i> #5: With relationship</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/bulk-actions') }}"><i class="nav-icon icon-book-open"></i> #6: {{ trans('admin.bulk-action.title') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/tags') }}"><i class="nav-icon icon-tag"></i> #7: {{ trans('admin.tag.title') }}</a></li> -->
                {{-- Do not delete me :) I'm used for auto-generation menu items --}}

                <!--<li class="nav-title">{{ trans('brackets/admin-ui::admin.sidebar.settings') }}</li>-->
                <!-- <li class="nav-item"><a class="nav-link" href="{{ url('admin/admin-users') }}"><i class="nav-icon icon-user"></i> {{ __('Manage access') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/translations') }}"><i class="nav-icon icon-location-pin"></i> {{ __('Translations') }}</a></li> -->
                {{-- Do not delete me :) I'm also used for auto-generation menu items --}}
                {{--<li class="nav-item"><a class="nav-link" href="{{ url('admin/configuration') }}"><i class="nav-icon icon-settings"></i> {{ __('Configuration') }}</a></li>--}}
            </ul>
        </nav>
        <button class="sidebar-minimizer brand-minimizer" type="button"></button>
    @else
        <nav class="sidebar-nav">
            <ul class="nav">
                <li class="nav-title">Productos</li>
                <li class="nav-item"><a class="nav-link" href=""><i class="nav-icon icon-plus"></i>Inventario</a></li>
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