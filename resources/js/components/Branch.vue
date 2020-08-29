<template>
    <div class="container-fluid px-3 py-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            <i class="fa fa-align-justify"></i>
                            Sucursal
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
                                        <option value="name" autofocus>Nombre</option>
                                        <option value="address">Dirección</option>
                                        <option value="city">Ciudad</option>
                                    </select>
                                    <input v-model="search" v-on:keyup.enter="getBranches(1, search, criterion)" type="text" class="form-control bg-light shadow-sm" placeholder="Sucursal a buscar">
                                    <button v-on:click.prevent="getBranches(1, search, criterion)" type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Nombres</th>
                                    <th>Dirección</th>
                                    <th>Ciudad</th>
                                    <th colspan="2">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="branch in branches">
                                    <td>{{ branch.name }}</td>
                                    <td>{{ branch.address }}</td>
                                    <td>{{ branch.city }}</td>
                                    <td width="10px">
                                        <a v-on:click.prevent="openModal('edit', branch)" href="#" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                    </td>
                                    <td width="10px">
                                        <a v-on:click.prevent="deleteBranch(branch)" href="#" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>
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
                        <div class="modal fade" id="modalBranch">
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
                                            <input v-model="name" placeholder="Nombre de sucursal ...." type="text" name="name" class="form-control bg-light shadow-sm" :class="{ 'is-invalid': hasErrorName}">
                                            <span class="text-danger" ole="alert">{{ errorName }}</span>
                                        </div>
                                        <div class="form-group">
                                            <label for="address">Dirección</label>
                                            <input v-model="address" placeholder="Dirección de la sucursal ...." type="text" name="address" class="form-control bg-light shadow-sm" :class="{ 'is-invalid': hasErrorAddress}">
                                            <span class="text-danger" ole="alert">{{ errorAddress }}</span>
                                        </div>
                                        <div class="form-group">
                                            <label for="city">Ciudad</label>
                                            <input v-model="city" placeholder="Ciudad ...." type="text" name="city" class="form-control bg-light shadow-sm" :class="{ 'is-invalid': hasErrorCity}">
                                            <span class="text-danger" ole="alert">{{ errorCity }}</span>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        <button v-if="typeAction==1" type="button" class="btn btn-primary" value="Guardar" v-on:click.prevent="createBranch">Guardar</button>
                                        <button v-if="typeAction==2" type="button" class="btn btn-primary" value="Guardar" v-on:click.prevent="updateBranch(id)">Actulizar</button>
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
            branches: [],
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
            address: '',
            city: '',
            errorName: '',
            errorAddress: '',
            errorCity: '',
            modalTitle: '',
            typeAction: 0,
            offset: 3,
            search: '',
            criterion: 'name',
            hasErrorName: false,
            hasErrorAddress: false,
            hasErrorCity: false,
        }
    },

    created: function(){
        this.getBranches(1, '', '');
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
        getBranches: function(page, search, criterion){
            var url = 'branches?page=' + page + '&search=' + search + '&criterion=' + criterion;
            axios.get(url).then(response => {
                this.branches = response.data.branches.data;
                this.pagination = response.data.pagination;
            });
        },

        //Elimnar una categoria
        deleteBranch: function(branch){
            var url = 'branches/' + branch.id;
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
                        this.getBranches(1, '', '');
                    });
                    //Asta qui
                    Swal.fire(
                        'Eliminado!',
                        'Sucursal eliminado correctamente.',
                        'success'
                        )
                }
            })
        },

        //crear producto
        createBranch: function(){
            var url = 'branches';
            axios.post(url, {
                'name': this.name,
                'address': this.address,
                'city': this.city
            }).then(response => {
                this.getBranches(1, '', '');
                this.clear();
                $('#modalBranch').modal('hide');
                toastr.success('Nueva sucursal creada con exito', 'Sucursal', {
                    "closeButton": true,
                    "positionClass": "toast-bottom-right"
                });
            }).catch(error => {
                if(typeof error.response.data.errors.name != 'undefined') {
                    this.errorName = error.response.data.errors.name[0];
                    this.hasErrorName = true;
                }
                if(typeof error.response.data.errors.address != 'undefined') {
                    this.errorAddress = error.response.data.errors.address[0];
                    this.hasErrorAddress = true;
                }
                if(typeof error.response.data.errors.city != 'undefined') {
                    this.errorCity = error.response.data.errors.city[0];
                    this.hasErrorCity = true;
                }
            });
        },

        //actualizar producto
        updateBranch: function(id){
            var url = 'branches/' + id;
            axios.put(url, {
                'id': this.id,
                'name': this.name,
                'address': this.address,
                'city': this.city,
            }).then(response => {
                this.getBranches(1, '', '');
                $('#modalBranch').modal('hide');
                toastr.success('Sucursal actualizado con exito', 'Sucursal', {
                    "closeButton": true,
                    "positionClass": "toast-bottom-right"
                });
            }).catch(error => {
                if(typeof error.response.data.errors.name != 'undefined') {
                    this.errorName = error.response.data.errors.name[0];
                    this.hasErrorName = true;
                }
                if(typeof error.response.data.errors.address != 'undefined') {
                    this.errorAddress = error.response.data.errors.address[0];
                    this.hasErrorAddress = true;
                }
                if(typeof error.response.data.errors.city != 'undefined') {
                    this.errorCity = error.response.data.errors.city[0];
                    this.hasErrorCity = true;
                }
            });
        },

        openModal: function(modal, branch){
            if (modal == 'create'){
                this.typeAction = 1;
                this.modalTitle = 'Nueva Sucursal';
                this.clear();
                $('#modalBranch').modal('show');
                this.hasErrorFalse();
            }
            else if(modal == 'edit'){
                this.hasErrorFalse();
                this.typeAction = 2;
                this.modalTitle = 'Actulizar Sucursal';
                this.id = branch.id;
                this.name = branch.name;
                this.address = branch.address;
                this.city = branch.city;
                $('#modalBranch').modal('show');
            }
        },

        clear: function(){
            this.id = 0;
            this.name = '';
            this.address = '';
            this.city = '';
            this.hasErrorFalse();
        },

        hasErrorFalse: function(){
            this.errorName = '';
            this.hasErrorName = false;
            this.errorAddress = '';
            this.hasErrorAddress = false;
            this.errorCity = '';
            this.hasErrorCity = false;
        },

        //Para el cambio de pagina
        changePage: function(page, search, criterion){
            this.pagination.current_page = page;
            this.getBranches(page, search, criterion);
        }
    }
}
</script>
