<div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-warehouse"></i>
                    <p>
                        Almacén
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li v-on:click.prevent="menu=1" class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-tag nav-icon"></i>
                            <p>Categorías</p>
                        </a>
                    </li>
                    <li v-on:click.prevent="menu=2" class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-check-square nav-icon"></i>
                            <p>Productos</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-clipboard-list"></i>
                    <p>
                        Entradas
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li v-on:click.prevent="menu=3" class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-clipboard-list nav-icon"></i>
                            <p>Entradas</p>
                        </a>
                    </li>
                    <li v-on:click.prevent="menu=4" class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-address-card nav-icon"></i>
                            <p>Proveedores</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-clipboard"></i>
                    <p>
                        Salidas
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li v-on:click.prevent="menu=5" class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-clipboard nav-icon"></i>
                            <p>Salidas</p>
                        </a>
                    </li>
                    <li v-on:click.prevent="menu=6" class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-user nav-icon"></i>
                            <p>Personal</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                        Acceso
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li v-on:click.prevent="menu=7" class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-user-tie nav-icon"></i>
                            <p>Usuarios</p>
                        </a>
                    </li>
                    <li v-on:click.prevent="menu=8" class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-user-cog nav-icon"></i>
                            <p>Roles</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-table"></i>
                    <p>
                        Reportes
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li v-on:click.prevent="menu=9" class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-clipboard-list nav-icon"></i>
                            <p>Ingreso de Productos</p>
                        </a>
                    </li>
                    <li v-on:click.prevent="menu=10" class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-clipboard nav-icon"></i>
                            <p>Salida de Productos</p>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>