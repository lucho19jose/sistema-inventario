<template>
    <div class="container-fluid px-3 py-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            <i class="fa fa-align-justify"></i>
                            Proveedores
                            <a v-on:click.prevent="openModal('create')" href="#" class="btn btn-primary float-right">
                                <i class="fas fa-plus-circle"></i> Nuevo
                            </a>
                        </h4>
                    </div>

                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input v-model="search" v-on:keyup.enter="getProviders(1, search)" type="text" class="form-control bg-light shadow-sm" placeholder="Proveedor a buscar">
                                    <button v-on:click.prevent="getProviders(1, search)" type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>RUC</th>
                                    <th>Dirección</th>
                                    <th>Telefono</th>
                                    <th>Email</th>
                                    <th colspan="2">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="provider in providers">
                                    <td>{{ provider.name }}</td>
                                    <td>{{ provider.ruc }}</td>
                                    <td>{{ provider.address }}</td>
                                    <td>{{ provider.phone }}</td>
                                    <td>{{ provider.email }}</td>
                                    <td width="10px">
                                        <a v-on:click.prevent="openModal('edit', provider)" href="#" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                    </td>
                                    <td width="10px">
                                        <a v-on:click.prevent="deleteProvider(provider)" href="#" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>
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
                </div>
                <div>
                    <form method="POST">
                        <div class="modal fade" id="modalProvider">
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
                                            <label for="name">Nombre</label>
                                            <input v-model="name" placeholder="Nombre de proveedor ...." type="text" name="name" class="form-control bg-light shadow-sm" :class="{ 'is-invalid': hasErrorName}">
                                            <span class="text-danger" ole="alert">{{ errorName }}</span>
                                        </div>
                                        <div class="form-group">
                                            <label for="ruc">RUC</label>
                                            <input v-model="ruc" placeholder="Número de RUC ...." type="text" name="ruc" class="form-control bg-light shadow-sm">
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
                                        <button v-if="typeAction==1" type="button" class="btn btn-primary" value="Guardar" v-on:click.prevent="createProvider">Guardar</button>
                                        <button v-if="typeAction==2" type="button" class="btn btn-primary" value="Guardar" v-on:click.prevent="updateProvider(id)">Actualizar</button>
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
import Swal from 'sweetalert2/dist/sweetalert2.js'
import 'sweetalert2/src/sweetalert2.scss'

export default {
    data(){
        return {
            providers: [],
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
            ruc: '',
            address: '',
            phone: '',
            email: '',
            errorName: '',
            errorAddress: '',
            errorPhone: '',
            errorEmail: '',
            modalTitle: '',
            typeAction: 0,
            offset: 3,
            search: '',
            hasErrorName: false,
            hasErrorAddress: false,
            hasErrorPhone: false,
            hasErrorEmail: false,
        }
    },

    created: function(){
        this.getProviders(1, '');
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
        getProviders: function(page, search){
            var url = 'providers?page=' + page + '&search=' + search;
            axios.get(url).then(response => {
                this.providers = response.data.providers.data;
                this.pagination = response.data.pagination;
            });
        },

        //Elimnar una categoria
        deleteProvider: function(provider){
            var url = 'providers/' + provider.id;
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
                        this.getProviders(1, '');
                    });
                    //Asta qui
                    Swal.fire(
                        'Eliminado!',
                        'Proveedor eliminado correctamente.',
                        'success'
                        )
                }
            })
        },

        //crear producto
        createProvider: function(){
            var url = 'providers';
            axios.post(url, {
                'name': this.name,
                'ruc': this.ruc,
                'address': this.address,
                'phone': this.phone,
                'email': this.email
            }).then(response => {
                this.getProviders(1, '');
                this.clear();
                $('#modalProvider').modal('hide');
                toastr.success('Nuevo producto creada con exito', 'Producto', {
                    "closeButton": true,
                    "positionClass": "toast-bottom-right"
                });
            }).catch(error => {
                if (typeof error.response.data.errors.name != 'undefined') {
                    this.errorName = error.response.data.errors.name[0];
                    this.hasErrorName = true;
                }
                if (typeof error.response.data.errors.address != 'undefined') {
                    this.errorAddress = error.response.data.errors.address[0];
                    this.hasErrorAddress = true;
                }
                if (typeof error.response.data.errors.phone != 'undefined') {
                    this.errorPhone = error.response.data.errors.phone[0];
                    this.hasErrorPhone = true;
                }
                if (typeof error.response.data.errors.email != 'undefined') {
                    this.errorEmail = error.response.data.errors.email[0];
                    this.hasErrorEmail = true;
                }
            });
        },

        //actualizar producto
        updateProvider: function(id){
            var url = 'providers/' + id;
            axios.put(url, {
                'id': this.id,
                'name': this.name,
                'ruc': this.ruc,
                'address': this.address,
                'phone': this.phone,
                'email': this.email
            }).then(response => {
                this.getProviders(1, '');
                $('#modalProvider').modal('hide');
                toastr.success('Proveedor actualizado con exito', 'Proveedor', {
                    "closeButton": true,
                    "positionClass": "toast-bottom-right"
                });
            }).catch(error => {
                 if (typeof error.response.data.errors.name != 'undefined') {
                    this.errorName = error.response.data.errors.name[0];
                    this.hasErrorName = true;
                }
                if (typeof error.response.data.errors.address != 'undefined') {
                    this.errorAddress = error.response.data.errors.address[0];
                    this.hasErrorAddress = true;
                }
                if (typeof error.response.data.errors.phone != 'undefined') {
                    this.errorPhone = error.response.data.errors.phone[0];
                    this.hasErrorPhone = true;
                }
                if (typeof error.response.data.errors.email != 'undefined') {
                    this.errorEmail = error.response.data.errors.email[0];
                    this.hasErrorEmail = true;
                }
            });
        },

        openModal: function(modal, provider){
            if (modal == 'create'){
                this.typeAction = 1;
                this.modalTitle = 'Nuevo Proveedor';
                this.clear();
                $('#modalProvider').modal('show');
                this.hasErrorFalse();
            }
            else if(modal == 'edit'){
                this.hasErrorFalse();
                this.typeAction = 2;
                this.modalTitle = 'Actualizar Proveedor';
                this.id = provider.id;
                this.name = provider.name;
                this.ruc = provider.ruc;
                this.address = provider.address;
                this.phone = provider.phone;
                this.email = provider.email;
                $('#modalProvider').modal('show');
            }
        },

        clear: function(){
            this.id = 0;
            this.name = '';
            this.ruc = '';
            this.address = '';
            this.phone = '';
            this.email = '';
            this.hasErrorFalse();
        },

        hasErrorFalse: function(){
            this.errorName = '';
            this.hasErrorName = false;
            this.errorPhone = '';
            this.hasErrorAddress = false;
            this.errorAddress = '';
            this.hasErrorPhone = false;
            this.errorEmail = '';
            this.hasErrorEmail = false;
        },

        //Para el cambio de pagina
        changePage: function(page, search){
            this.pagination.current_page = page;
            this.getProviders(page, search);
        }
    }
}
</script>
