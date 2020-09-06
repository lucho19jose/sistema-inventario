<div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
            @canany(['category.index', 'category.create', 'category.update', 'category.destroy', 'product.index', 'product.create', 'product.update', 'product.destroy'])
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-warehouse"></i>
                    <p>
                        Almacén
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    @canany(['category.index', 'category.create', 'category.update', 'category.destroy'])
                    <li v-on:click.prevent="menu=1" class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-tag nav-icon"></i>
                            <p>Categorías</p>
                        </a>
                    </li>
                    @endcan
                    @canany(['product.index', 'product.create', 'product.update', 'product.destroy'])
                    <li v-on:click.prevent="menu=2" class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-check-square nav-icon"></i>
                            <p>Productos</p>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcan
            @canany(['input.index', 'input.create', 'input.show', 'provider.index', 'provider.create', 'provider.update', 'provider.destroy'])
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-clipboard-list"></i>
                    <p>
                        Ingresos
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    @canany(['input.index', 'input.create', 'input.show'])
                    <li v-on:click.prevent="menu=3" class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-clipboard-list nav-icon"></i>
                            <p>Ingreso</p>
                        </a>
                    </li>
                    @endcan
                    @canany(['provider.index', 'provider.create', 'provider.update', 'provider.destroy'])
                    <li v-on:click.prevent="menu=4" class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-address-card nav-icon"></i>
                            <p>Proveedores</p>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcan
            @canany(['output.index', 'output.create', 'output.show', 'staff.index', 'staff.create', 'staff.update', 'staff.destroy', 'branch.index', 'branch.create', 'branch.update', 'branch.destroy'])
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-clipboard"></i>
                    <p>
                        Salidas
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    @canany(['output.index', 'output.create', 'output.show'])
                    <li v-on:click.prevent="menu=5" class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-clipboard nav-icon"></i>
                            <p>Salida</p>
                        </a>
                    </li>
                    @endcan
                    @canany(['staff.index', 'staff.create', 'staff.update', 'staff.destroy'])
                    <li v-on:click.prevent="menu=6" class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-user nav-icon"></i>
                            <p>Personal</p>
                        </a>
                    </li>
                    @endcan
                    @canany(['branch.index', 'branch.create', 'branch.update', 'branch.destroy'])
                    <li v-on:click.prevent="menu=7" class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-home nav-icon"></i>
                            <p>Sucursal</p>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcan
            @canany(['user.index', 'user.create', 'user.update', 'user.destroy', 'user.reset', 'role.index', 'role.create', 'role.update', 'role.destroy'])
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                        Acceso
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    @canany(['user.index', 'user.create', 'user.update', 'user.destroy', 'user.reset'])
                    <li v-on:click.prevent="menu=8" class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-user-tie nav-icon"></i>
                            <p>Usuarios</p>
                        </a>
                    </li>
                    @endcan
                    @canany(['role.index', 'role.create', 'role.update', 'role.destroy'])
                    <li v-on:click.prevent="menu=9" class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-user-cog nav-icon"></i>
                            <p>Roles</p>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcan
            @canany(['input.report', 'output.report', 'product.report'])
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-table"></i>
                    <p>
                        Reportes
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    @canany(['input.report'])
                    <li v-on:click.prevent="menu=10" class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-clipboard-list nav-icon"></i>
                            <p>Ingreso de Productos</p>
                        </a>
                    </li>
                    @endcan
                    @canany(['output.report'])
                    <li v-on:click.prevent="menu=11" class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-clipboard nav-icon"></i>
                            <p>Salida de Productos</p>
                        </a>
                    </li>
                    @endcan
                    @canany(['product.report'])
                    <li v-on:click.prevent="menu=12" class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-check-square nav-icon"></i>
                            <p>Stock Disponible</p>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcan
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>