<template>
    <div class="container-fluid px-3 py-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            <i class="fa fa-align-justify"></i>
                            Personal
                            <a v-on:click.prevent="openModal('create')" href="#" class="btn btn-primary float-right">
                                <i class="fas fa-plus-circle"></i> Nuevo
                            </a>
                        </h4>
                    </div>

                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select v-model="criterion" class="form-control col-md-3">
                                        <option value="first_name" autofocus>Nombre</option>
                                        <option value="last_name">Apellido</option>
                                        <option value="dni">DNI</option>
                                    </select>
                                    <input v-model="search" v-on:keyup.enter="getStaffs(1, search, criterion)" type="text" class="form-control bg-light shadow-sm" placeholder="Personal a buscar">
                                    <button v-on:click.prevent="getStaffs(1, search, criterion)" type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Nombres</th>
                                    <th>Apellidos</th>
                                    <th>DNI</th>
                                    <th>Dirección</th>
                                    <th>Telefono</th>
                                    <th>Email</th>
                                    <th colspan="2">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="staff in staffs">
                                    <td>{{ staff.first_name }}</td>
                                    <td>{{ staff.last_name }}</td>
                                    <td>{{ staff.dni }}</td>
                                    <td>{{ staff.address }}</td>
                                    <td>{{ staff.phone }}</td>
                                    <td>{{ staff.email }}</td>
                                    <td width="10px">
                                        <a v-on:click.prevent="openModal('edit', staff)" href="#" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                    </td>
                                    <td width="10px">
                                        <a v-on:click.prevent="deleteStaff(staff)" href="#" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>
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
                        <div class="modal fade" id="modalStaff">
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
                                            <label for="first_name">Nombres</label>
                                            <input v-model="first_name" placeholder="Nombres del personal ...." type="text" name="first_name" class="form-control bg-light shadow-sm" :class="{ 'is-invalid': hasErrorFirstName}">
                                            <span class="text-danger" ole="alert">{{ errorFirstName }}</span>
                                        </div>
                                        <div class="form-group">
                                            <label for="last_name">Apellidos</label>
                                            <input v-model="last_name" placeholder="Apellidos del personal ...." type="text" name="last_name" class="form-control bg-light shadow-sm" :class="{ 'is-invalid': hasErrorLastName}">
                                            <span class="text-danger" ole="alert">{{ errorLastName }}</span>
                                        </div>
                                        <div class="form-group">
                                            <label for="dni">DNI</label>
                                            <input v-model="dni" placeholder="Número de DNI ...." type="number" name="dni" class="form-control bg-light shadow-sm" :class="{ 'is-invalid': hasErrorDNI}">
                                            <span class="text-danger" ole="alert">{{ errorDNI }}</span>
                                        </div>
                                        <div class="form-group">
                                            <label for="address">Dirección</label>
                                            <input v-model="address" placeholder="Dirección de proveedor ...." type="text" name="address" class="form-control bg-light shadow-sm" :class="{ 'is-invalid': hasErrorAddress}">
                                            <span class="text-danger" ole="alert">{{ errorAddress }}</span>
                                        </div>
                                        <div class="form-group">
                                            <label for="phone">Telefono</label>
                                            <input v-model="phone" placeholder="Número de telefono ...." type="number" name="phone" class="form-control bg-light shadow-sm" :class="{ 'is-invalid': hasErrorPhone}">
                                            <span class="text-danger" ole="alert">{{ errorPhone }}</span>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input v-model="email" placeholder="Email de proveedor ...." type="email" name="email" class="form-control bg-light shadow-sm" :class="{ 'is-invalid': hasErrorEmail}">
                                            <span class="text-danger" ole="alert">{{ errorEmail }}</span>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        <button v-if="typeAction==1" type="button" class="btn btn-primary" value="Guardar" v-on:click.prevent="createStaff">Guardar</button>
                                        <button v-if="typeAction==2" type="button" class="btn btn-primary" value="Guardar" v-on:click.prevent="updateStaff(id)">Actulizar</button>
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
import axios from 'axios'
import toastr from 'toastr'
import 'toastr/build/toastr.css'
import Swal from 'sweetalert2/dist/sweetalert2.js'
import 'sweetalert2/src/sweetalert2.scss'

export default {
    data(){
        return {
            staffs: [],
            pagination: {
                'total': 0,
                'current_page': 0,
                'per_page': 0,
                'last_page': 0,
                'from': 0,
                'to': 0
            },
            id: 0,
            first_name: '',
            last_name: '',
            dni: '',
            address: '',
            phone: '',
            email: '',
            errorFirstName: '',
            errorLastName: '',
            errorDNI: '',
            errorAddress: '',
            errorPhone: '',
            errorEmail: '',
            modalTitle: '',
            typeAction: 0,
            offset: 3,
            search: '',
            criterion: 'first_name',
            hasErrorFirstName: false,
            hasErrorLastName: false,
            hasErrorDNI: false,
            hasErrorAddress: false,
            hasErrorPhone: false,
            hasErrorEmail: false,
        }
    },

    created: function(){
        this.getStaffs(1, '', '');
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
        getStaffs: function(page, search, criterion){
            var url = 'staffs?page=' + page + '&search=' + search + '&criterion=' + criterion;
            axios.get(url).then(response => {
                this.staffs = response.data.staffs.data;
                this.pagination = response.data.pagination;
            });
        },

        //Elimnar una categoria
        deleteStaff: function(staff){
            var url = 'staffs/' + staff.id;
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
                        this.getStaffs(1, '', '');
                    });
                    //Asta qui
                    Swal.fire(
                        'Eliminado!',
                        'Personal eliminado correctamente.',
                        'success'
                        )
                }
            })
        },

        //crear producto
        createStaff: function(){
            var url = 'staffs';
            axios.post(url, {
                'first_name': this.first_name,
                'last_name': this.last_name,
                'dni': this.dni,
                'address': this.address,
                'phone': this.phone,
                'email': this.email
            }).then(response => {
                this.getStaffs(1, '', '');
                this.clear();
                $('#modalStaff').modal('hide');
                toastr.success('Nueva personal creada con exito', 'Personal', {
                    "closeButton": true,
                    "positionClass": "toast-bottom-right"
                });
            }).catch(error => {
                if(typeof error.response.data.errors.first_name != 'undefined') {
                    this.errorFirstName = error.response.data.errors.first_name[0];
                    this.hasErrorFirstName = true;
                }
                if(typeof error.response.data.errors.last_name != 'undefined') {
                    this.errorLastName = error.response.data.errors.last_name[0];
                    this.hasErrorLastName = true;
                }
                if(typeof error.response.data.errors.dni != 'undefined') {
                    this.errorDNI = error.response.data.errors.dni[0];
                    this.hasErrorDNI = true;
                }
                if(typeof error.response.data.errors.address != 'undefined') {
                    this.errorAddress = error.response.data.errors.address[0];
                    this.hasErrorAddress = true;
                }
                if(typeof error.response.data.errors.phone != 'undefined') {
                    this.errorPhone = error.response.data.errors.phone[0];
                    this.hasErrorPhone = true;
                }
                if(typeof error.response.data.errors.email != 'undefined') {
                    this.errorEmail = error.response.data.errors.email[0];
                    this.hasErrorEmail = true;
                }
            });
        },

        //actualizar producto
        updateStaff: function(id){
            var url = 'staffs/' + id;
            axios.put(url, {
                'id': this.id,
                'first_name': this.first_name,
                'last_name': this.last_name,
                'dni': this.dni,
                'address': this.address,
                'phone': this.phone,
                'email': this.email
            }).then(response => {
                this.getStaffs(1, '', '');
                $('#modalStaff').modal('hide');
                toastr.success('Personal actualizado con exito', 'Personal', {
                    "closeButton": true,
                    "positionClass": "toast-bottom-right"
                });
            }).catch(error => {
                    if(typeof error.response.data.errors.first_name != 'undefined') {
                        this.errorFirstName = error.response.data.errors.first_name[0];
                        this.hasErrorFirstName = true;
                    }
                    if(typeof error.response.data.errors.last_name != 'undefined') {
                        this.errorLastName = error.response.data.errors.last_name[0];
                        this.hasErrorLastName = true;
                    }
                    if(typeof error.response.data.errors.dni != 'undefined') {
                        this.errorDNI = error.response.data.errors.dni[0];
                        this.hasErrorDNI = true;
                    }
                    if(typeof error.response.data.errors.address != 'undefined') {
                        this.errorAddress = error.response.data.errors.address[0];
                        this.hasErrorAddress = true;
                    }
                    if(typeof error.response.data.errors.phone != 'undefined') {
                        this.errorPhone = error.response.data.errors.phone[0];
                        this.hasErrorPhone = true;
                    }
                    if(typeof error.response.data.errors.email != 'undefined') {
                        this.errorEmail = error.response.data.errors.email[0];
                        this.hasErrorEmail = true;
                    }
                });
        },

        openModal: function(modal, staff){
            if (modal == 'create'){
                this.typeAction = 1;
                this.modalTitle = 'Nueva Personal';
                this.clear();
                $('#modalStaff').modal('show');
                this.hasErrorFalse();
            }
            else if(modal == 'edit'){
                this.hasErrorFalse();
                this.typeAction = 2;
                this.modalTitle = 'Actulizar Personal';
                this.id = staff.id;
                this.first_name = staff.first_name;
                this.last_name = staff.last_name;
                this.dni = staff.dni;
                this.address = staff.address;
                this.phone = staff.phone;
                this.email = staff.email;
                $('#modalStaff').modal('show');
            }
        },

        clear: function(){
            this.id = 0;
            this.first_name = '';
            this.last_name = '';
            this.dni = '';
            this.address = '';
            this.phone = '';
            this.email = '';
            this.hasErrorFalse();
        },

        hasErrorFalse: function(){
            this.errorFirstName = '';
            this.hasErrorFirstName = false;
            this.errorLastName = '';
            this.hasErrorLastName = false;
            this.errorDNI = '';
            this.hasErrorDNI = false;
            this.errorPhone = '';
            this.hasErrorAddress = false;
            this.errorAddress = '';
            this.hasErrorPhone = false;
            this.errorEmail = '';
            this.hasErrorEmail = false;
        },

        //Para el cambio de pagina
        changePage: function(page, search, criterion){
            this.pagination.current_page = page;
            this.getStaffs(page, search, criterion);
        }
    }
}
</script>
