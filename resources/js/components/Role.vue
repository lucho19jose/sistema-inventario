<template>
    <div class="container-fluid px-3 py-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            <i class="fa fa-align-justify"></i>
                            Roles
                            <a v-if="$can('role.create')" v-on:click.prevent="openModal('create')" href="#" class="btn btn-primary float-right">
                                <i class="fas fa-plus-circle"></i> Nuevo
                            </a>
                        </h4>
                    </div>

                    <template v-if="list==1">
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input v-model="search" v-on:keyup.enter="getRoles(1, search)" type="text" class="form-control bg-light shadow-sm" placeholder="Rol a buscar">
                                        <button v-on:click.prevent="getRoles(1, search)" type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th colspan="2">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="role in roles">
                                        <td>{{ role.name }}</td>
                                        <td width="10px">
                                            <a v-if="$can('role.update')" v-on:click.prevent="openModal('edit', role)" href="#" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                        </td>
                                        <td width="10px">
                                            <a v-if="$can('role.destroy')" v-on:click.prevent="deleteRole(role)" href="#" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <nav class="mt-3">
                                <ul class="pagination">
                                    <li class="page-item" v-if="pagination.current_page > 1">
                                        <a href="#" class="page-link" v-on:click.prevent="changePage(pagination.current_page  - 1, search)">
                                            <span>Atras</span>
                                        </a>
                                    </li>

                                    <li class="page-item" v-for="page in pagesNumber" v-bind:class="[page == isActived ? 'active' : '']">
                                        <a href="#" class="page-link" v-on:click.prevent="changePage(page, search)">{{ page }}</a>
                                    </li>

                                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                        <a href="#" class="page-link" v-on:click.prevent="changePage(pagination.current_page  + 1, search)">
                                            <span>Siguiente</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </template>
                    <template v-if="list==0">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Nombre</label>
                                <input v-model="name" placeholder="Nombre del rol ...." type="text" name="name" class="form-control bg-light shadow-sm" :class="{ 'is-invalid': hasErrorName}">
                                <span class="text-danger" ole="alert">{{ errorName }}</span>
                            </div>
                            <div class="form-group">
                                <label for="special">Permiso especial</label>
                                <div>
                                    <input v-model="special" name="special" type="radio" value="Administrador"> Acceso total
                                    <input v-model="special" class="ml-3" name="special" type="radio" value=""> Ningun acceso
                                    <input v-model="special" class="ml-3" name="special" type="radio" value=""> Solo los de la lista
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="permission">Lista de permisos</label>
                                <div class="row">
                                    <div  v-for="permi in permissions" class="col-lg-6 col-sm-12">
                                        <input v-model="permission" type="checkbox" name="permission[]" :value="permi.id"> {{ permi.description }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button v-on:click.prevent="closeModal" type="button" class="btn btn-secondary">Cerrar</button>
                                    <button v-if="typeAction==1" type="button" class="btn btn-primary" value="Guardar" v-on:click.prevent="createRole">Guardar</button>
                                    <button v-if="typeAction==2" type="button" class="btn btn-primary" value="Guardar" v-on:click.prevent="updateRole(id)">Actualizar</button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Swal from 'sweetalert2/dist/sweetalert2.js'
import 'sweetalert2/src/sweetalert2.scss'

export default {
    data(){
        return {
            roles: [],
            permissions: [],
            permission: [],
            pagination: {
                'total': 0,
                'current_page': 0,
                'per_page': 0,
                'last_page': 0,
                'from': 0,
                'to': 0
            },
            id: 0,
            name: '',
            special: '',
            permission_id: '',
            errorName: '',
            errorPermission: '',
            modalTitle: '',
            typeAction: 0,
            offset: 3,
            search: '',
            list: 1,
            hasErrorName: false,
            hasErrorPermission: false,
        }
    },

    created: function(){
        this.getRoles(1, '');
    },

    computed: {
        isActived: function(){
            return this.pagination.current_page;
        },
        pagesNumber: function(){
            if(!this.pagination.to){
                return [];
            }
            //Desde que pagina
            var from = this.pagination.current_page - this.offset;
            if(from < 1){
                from = 1;
            }
            //Asta que pagina
            var to = from + (this.offset * 2);
            if(to >= this.pagination.last_page) {
                to = this.pagination.last_page;
            }

            var pagesArray = [];
            while (from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;
        },
    },

    methods: {
        //Obtner las categorias
        getRoles: function(page, search){
            var url = 'roles?page=' + page + '&search=' + search;
            axios.get(url).then(response => {
                this.roles = response.data.roles.data;
                this.pagination = response.data.pagination;
            });
        },

        //Elimnar una categoria
        deleteRole: function(role){
            var url = 'roles/' + role.id;
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podréis revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, bórralo!'
            }).then((result) => {
                if (result.value) {
                    //Metodo para eliminar
                    axios.delete(url).then(response => {
                        this.getRoles(1, '');
                    });
                    //Asta qui
                    Swal.fire(
                        'Eliminado!',
                        'Rol eliminado correctamente.',
                        'success'
                        )
                }
            })
        },

        //Obtener categorias

        getPermissions: function(){
            var url = 'roles/create';
            axios.get(url).then(response => {
                this.permissions = response.data.permissions;
            });
        },

        getPermissionsEdit: function(role){
            var url = 'roles/' + role.id + '/edit';
            axios.get(url).then(response => {
                this.permissions = response.data.permissions;
                this.permission = response.data.assignedPermissions;
            });
        },

        //crear roleo
        createRole: function(){
            var url = 'roles';
            axios.post(url, {
                'name': this.name,
                'permissions': this.permission,
                'special': this.special
            }).then(response => {
                this.getRoles(1, '');
                this.clear();
                this.list = 1;
                $('#modalRole').modal('hide');
                toastr.success('Nuevo rol creada con exito', 'Rol', {
                    "closeButton": true,
                    "positionClass": "toast-bottom-right"
                });
            }).catch(error => {
                if (typeof error.response.data.errors.name != 'undefined') {
                    this.errorName = error.response.data.errors.name[0];
                    this.hasErrorName = true;
                }
            });
        },

        //actualizar roleo
        updateRole: function(id){
            var url = 'roles/' + id;
            axios.put(url, {
                'id': this.id,
                'name': this.name,
                'permissions': this.permission,
                'special': this.special
            }).then(response => {
                this.getRoles(1, '');
                this.clear();
                this.list = 1;
                $('#modalRole').modal('hide');
                toastr.success('Rol actualizado con exito', 'Rol', {
                    "closeButton": true,
                    "positionClass": "toast-bottom-right"
                });
            }).catch(error => {
                if (typeof error.response.data.errors.name != 'undefined') {
                    this.errorName = error.response.data.errors.name[0];
                    this.hasErrorName = true;
                }
            });
        },

        openModal: function(modal, role){
            this.list = 0;
            if (modal == 'create'){
                this.typeAction = 1;
                this.clear();
                this.hasErrorFalse();
                this.getPermissions();
            }
            else if(modal == 'edit'){
                this.hasErrorFalse();
                this.getPermissionsEdit(role);
                this.typeAction = 2;
                this.id = role.id;
                this.name = role.name;
            }
        },

        closeModal: function(){
            this.list = 1;
        },

        clear: function(){
            this.id = 0;
            this.name = '';
            this.permission_id = '0';
            this.permission = [];
            this.special= '';
            this.hasErrorFalse();
        },

        hasErrorFalse: function(){
            this.errorName = '';
            this.errorPermission = '';
            this.hasErrorName = false;
            this.hasErrorPermission = false;
        },

        //Para el cambio de pagina
        changePage: function(page, search){
            this.pagination.current_page = page;
            this.getRoles(page, search);
        }
    }
}
</script>
