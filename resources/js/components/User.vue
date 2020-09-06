<template>
    <div class="container-fluid px-3 py-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            <i class="fa fa-align-justify"></i>
                            Usuarios
                            <a v-on:click.prevent="openModal('create')" href="#" class="btn btn-primary float-right">
                                <i class="fas fa-plus-circle"></i> Nuevo
                            </a>
                        </h4>
                    </div>

                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select v-model="criterion" class="form-control col-md-6">
                                        <option value="staff_name" autofocus>Nombre de personal</option>
                                        <option value="email">Email</option>
                                    </select>
                                    <input v-model="search" v-on:keyup.enter="getUsers(1, search, criterion)" type="text" class="form-control bg-light shadow-sm" placeholder="Usuario a buscar">
                                    <button v-on:click.prevent="getUsers(1, search, criterion)" type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Nombre de Personal</th>
                                    <th>Correo electrónico</th>
                                    <th>Creado</th>
                                    <th colspan="3">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="user in users">
                                    <td>{{ user.staff_name }}</td>
                                    <td>{{ user.email }}</td>
                                    <td>{{ date(user.created_at) }}</td>
                                    <td width="10px">
                                        <a v-on:click.prevent="resetUser(user.id)" href="#" class="btn btn-primary btn-sm"><i class="fas fa-sync-alt"></i></a>
                                    </td>
                                    <td width="10px">
                                        <a v-on:click.prevent="openModal('edit', user)" href="#" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                    </td>
                                    <td width="10px">
                                        <a v-on:click.prevent="deleteUser(user)" href="#" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <nav class="mt-3">
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a href="#" class="page-link" v-on:click.prevent="changePage(pagination.current_page  - 1, search, criterion)">
                                        <span>Atras</span>
                                    </a>
                                </li>

                                <li class="page-item" v-for="page in pagesNumber" v-bind:class="[page == isActived ? 'active' : '']">
                                    <a href="#" class="page-link" v-on:click.prevent="changePage(page, search, criterion)">{{ page }}</a>
                                </li>

                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a href="#" class="page-link" v-on:click.prevent="changePage(pagination.current_page  + 1, search, criterion)">
                                        <span>Siguiente</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div>
                    <form method="POST">
                        <div class="modal fade" id="modalUser">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h3 class="modal-title">{{ modalTitle }}</h3>
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="staff">Personal</label>
                                            <select v-model="staff_id" class="form-control bg-light shadow-sm" :class="{ 'is-invalid': hasErrorStaff}" v-on:change="getStaffEmail">
                                                <option value="" disabled>Seleccione</option>
                                                <option v-for="staff in staffs" :key="staff.id" :value="staff.id" v-text="staff.staff_name">
                                                </option>
                                            </select>
                                            <span class="text-danger" ole="alert">{{ errorStaff }}</span>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Correo electrónico</label>
                                            <input v-model="email" placeholder="Correo electronico del  usuario ...." type="text" name="email" class="form-control bg-light shadow-sm" :class="{ 'is-invalid': hasErrorEmail}" value="email">
                                            <span class="text-danger" ole="alert">{{ errorEmail }}</span>
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Contraseña</label>
                                            <input v-model="password" placeholder="Contraseña del usuario ...." type="password" name="password" class="form-control bg-light shadow-sm" :class="{ 'is-invalid': hasErrorPassword}">
                                            <span class="text-danger" ole="alert">{{ errorPassword }}</span>
                                        </div>
                                        <div class="form-group">
                                            <label for="password_confirmation">Confirmar contraseña</label>
                                            <input v-model="password_confirmation" placeholder="Ingrese nuevamente la contraseña ...." type="password" name="password_confirmation" class="form-control bg-light shadow-sm" :class="{ 'is-invalid': hasErrorPassword_conf}">
                                            <span class="text-danger" ole="alert">{{ errorPassword_conf }}</span>
                                        </div>
                                        <div class="form-group">
                                            <label for="role">Rol</label>
                                            <select v-model="role" class="form-control bg-light shadow-sm" v-on:change="getStaffEmail">
                                                <option value="a">Sin rol</option>
                                                <option v-for="role in roles" :key="role.id" :value="role.name" v-text="role.name">
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        <button v-if="typeAction==1" type="button" class="btn btn-primary" value="Guardar" v-on:click.prevent="createUser">Guardar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div>
                    <form method="POST">
                        <div class="modal fade" id="modalUserUpdate">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h3 class="modal-title">{{ modalTitle }}</h3>
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="email">Correo electrónico</label>
                                            <p>{{ email }}</p>
                                        </div>
                                        <div class="form-group">
                                            <label for="role">Rol</label>
                                            <select v-model="role" class="form-control bg-light shadow-sm" v-on:change="getStaffEmail">
                                                <option value="a">Sin rol</option>
                                                <option v-for="role in roles" :key="role.id" :value="role.name" v-text="role.name">
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        <button v-if="typeAction==2" type="button" class="btn btn-primary" value="Guardar" v-on:click.prevent="updateUser(id)">Actualizar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment'
import Swal from 'sweetalert2/dist/sweetalert2.js'
import 'sweetalert2/src/sweetalert2.scss'
import vSelect from "vue-select"
Vue.component('v-select', vSelect)
import 'vue-select/dist/vue-select.css'
//Cambiar el formato de las fechas al español
moment.locale('es');

export default {
    data(){
        return {
            users: [],
            staffs: [],
            roles: [],
            pagination: {
                'total': 0,
                'current_page': 0,
                'per_page': 0,
                'last_page': 0,
                'from': 0,
                'to': 0
            },
            id: 0,
            staff_id: '',
            role: 'a',
            email: '',
            password: '',
            password_confirmation: '',
            errorStaff: '',
            errorEmail: '',
            errorPassword: '',
            errorPassword_conf: '',
            modalTitle: '',
            typeAction: 0,
            offset: 3,
            search: '',
            criterion: 'email',
            hasErrorStaff: false,
            hasErrorEmail: false,
            hasErrorPassword: false,
            hasErrorPassword_conf: false,
        }
    },

    created: function(){
        this.getUsers(1, '', '');
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

        date: function(d){
            return moment(d).format('L');
        },

        //Obtner las categorias
        getUsers: function(page, search, criterion){
            var url = 'users?page=' + page + '&search=' + search + '&criterion=' + criterion;
            axios.get(url).then(response => {
                this.users = response.data.users.data;
                this.pagination = response.data.pagination;
            });
        },

        //Elimnar una categoria
        deleteUser: function(user){
            var url = 'users/' + user.id;
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
                        this.getUsers(1, '', '');
                    });
                    //Asta qui
                    Swal.fire(
                        'Eliminado!',
                        'Usuario eliminado correctamente.',
                        'success'
                        )
                }
            })
        },

        //obtener personal
        getStaff: function(id){
            var url = 'users/create?role=' +id;
            axios.get(url).then(response => {
                this.staffs = response.data.staffs;
                this.roles = response.data.roles;
                if (typeof response.data.role[0] == 'undefined') {
                    this.role = 'a';
                }
                else{
                  this.role = response.data.role[0];
                }
            });
        },

        //Ontener email del personal
        getStaffEmail: function(){
            for(var i = 0; i < this.staffs.length; i++){
                if (this.staffs[i].id == this.staff_id) {
                    this.email = this.staffs[i].email;
                    this.password = this.staffs[i].dni;
                    this.password_confirmation = this.staffs[i].dni;
                }
            }
        },

        //crear usuario
        createUser: function(){
            var url = 'users';
            axios.post(url, {
                'staff_id': this.staff_id,
                'email': this.email,
                'password': this.password,
                'password_confirmation': this.password_confirmation,
                'role': this.role
            }).then(response => {
                this.getUsers(1, '', '');
                this.clear();
                $('#modalUser').modal('hide');
                toastr.success('Nuevo usuario creada con exito', 'Usuario', {
                    "closeButton": true,
                    "positionClass": "toast-bottom-right"
                });
            }).catch(error => {
                if (typeof error.response.data.errors.staff_id != 'undefined') {
                    this.errorStaff = error.response.data.errors.staff_id[0];
                    this.hasErrorStaff = true;
                }
                if (typeof error.response.data.errors.email != 'undefined') {
                    this.errorEmail = error.response.data.errors.email[0];
                    this.hasErrorEmail = true;
                }
                if (typeof error.response.data.errors.password != 'undefined') {
                    this.errorPassword = error.response.data.errors.password[0];
                    this.hasErrorPassword = true;
                }
                if (typeof error.response.data.errors.password_confirmation != 'undefined') {
                    this.errorPassword_conf = error.response.data.errors.password_confirmation[0];
                    this.hasErrorPassword_conf = true;
                }
            });
        },

        updateUser: function(id){
            var url = 'users/' + id;
            axios.put(url, {
                'role': this.role
            }).then(response => {
                this.getUsers(1, '', '');
                this.clear();
                $('#modalUserUpdate').modal('hide');
                toastr.success('Usuario actualizado con exito', 'Usuario', {
                    "closeButton": true,
                    "positionClass": "toast-bottom-right"
                });
            });
        },

        //actualizar usuario
        resetUser: function(id){
            var url = 'users/' + id + '/reset';
            Swal.fire({
                title: '¿Restablecer contraseña?',
                text: "¡Se restablecera a la contraseña por defecto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, restablecer!'
            }).then((result) => {
                if (result.value) {
                    //Metodo para restablecer
                    axios.get(url).then(response => {
                        this.getUsers(1, '', '');
                        this.clear();
                    });
                    //Asta qui
                    Swal.fire(
                        'Restablecido!',
                        'Contraseña restablecida correctamente.',
                        'success'
                        )
                }
            });
        },

        openModal: function(modal, user){
            if (modal == 'create'){
                this.typeAction = 1;
                this.modalTitle = 'Nuevo Usuario';
                this.clear();
                $('#modalUser').modal('show');
                this.hasErrorFalse();
                this.getStaff('0');
            }
            else if(modal == 'edit'){
                this.hasErrorFalse();
                this.typeAction = 2;
                this.modalTitle = 'Actualizar Usuario';
                this.id = user.id;
                this.email = user.email;
                $('#modalUserUpdate').modal('show');
                this.getStaff(user.id);
            }
        },

        clear: function(){
            this.id = 0;
            this.staff_id = '';
            this.staff_name = '';
            this.staffs = [];
            this.email = '';
            this.password = '';
            this.password_confirmation = '';
            this.role = 'a';
            this.hasErrorFalse();
        },

        hasErrorFalse: function(){
            this.errorStaff = '';
            this.errorEmail= '';
            this.errorPassword = '';
            this.errorPassword_conf= '';
            this.hasErrorStaff = false;
            this.hasErrorEmail = false;
            this.hasErrorPassword = false;
            this.hasErrorPassword_conf = false;
        },

        //Para el cambio de pagina
        changePage: function(page, search, criterion){
            this.pagination.current_page = page;
            this.getUsers(page, search, criterion);
        }
    }
}
</script>
