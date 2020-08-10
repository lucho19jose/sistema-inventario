<template>
    <div class="container-fluid px-3 py-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            <i class="fa fa-align-justify"></i>
                            Productos
                            <a v-on:click.prevent="openModal('create')" href="#" class="btn btn-primary float-right">
                                <i class="fas fa-plus-circle"></i> Nuevo
                            </a>
                        </h4>
                    </div>

                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input v-model="search" v-on:keyup.enter="getProducts(1, search)" type="text" class="form-control bg-light shadow-sm" placeholder="Producto a buscar">
                                    <button v-on:click.prevent="getProducts(1, search)" type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Categoría</th>
                                    <th>Descripcion</th>
                                    <th>Stock</th>
                                    <th colspan="2">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="product in products">
                                    <td>{{ product.name }}</td>
                                    <td>{{ product.category_name }}</td>
                                    <td>{{ product.description }}</td>
                                    <td>{{ product.stock }}</td>
                                    <td width="10px">
                                        <a v-on:click.prevent="openModal('edit', product)" href="#" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                    </td>
                                    <td width="10px">
                                        <a v-on:click.prevent="deleteProduct(product)" href="#" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>
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
                        <div class="modal fade" id="modalProduct">
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
                                            <label for="category">Categoría</label>
                                            <select v-model="category_id" class="form-control bg-light shadow-sm" :class="{ 'is-invalid': hasErrorCategory}">
                                                <option value="a" disabled>Seleccione</option>
                                                <option v-for="category in categories" :key="category.id" :value="category.id" v-text="category.name">
                                                </option>
                                            </select>
                                            <span class="text-danger" ole="alert">{{ errorCategory }}</span>
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Nombre</label>
                                            <input v-model="name" placeholder="Nombre categoria ...." type="text" name="name" class="form-control bg-light shadow-sm" :class="{ 'is-invalid': hasErrorName}">
                                            <span class="text-danger" ole="alert">{{ errorName }}</span>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Descripcion</label>
                                            <textarea v-model="description" class="form-control bg-light shadow-sm"
                                            name="description" placeholder="Ingrese descripcion ...."></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="stock">Stock</label>
                                            <input v-model="stock" type="number" name="stock" class="form-control bg-light shadow-sm" :class="{ 'is-invalid': hasErrorStock}">
                                            <span class="text-danger" ole="alert">{{ errorStock }}</span>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        <button v-if="typeAction==1" type="button" class="btn btn-primary" value="Guardar" v-on:click.prevent="createProduct">Guardar</button>
                                        <button v-if="typeAction==2" type="button" class="btn btn-primary" value="Guardar" v-on:click.prevent="updateProduct(id)">Actulizar</button>
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
            products: [],
            categories: [],
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
            description: '',
            stock: 0,
            category_id: 'a',
            errorName: '',
            errorCategory: '',
            errorStock: '',
            modalTitle: '',
            typeAction: 0,
            offset: 3,
            search: '',
            hasErrorName: false,
            hasErrorCategory: false,
            hasErrorStock: false,
        }
    },

    created: function(){
        this.getProducts(1, '');
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
        getProducts: function(page, search){
            var urlProduct = 'products?page=' + page + '&search=' + search;
            axios.get(urlProduct).then(response => {
                this.products = response.data.products.data;
                this.pagination = response.data.pagination;
            });
        },

        //Elimnar una categoria
        deleteProduct: function(product){
            var url = 'products/' + product.id;
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
                        this.getProducts(1, '');
                    });
                    //Asta qui
                    Swal.fire(
                        'Eliminado!',
                        'Producto eliminado correctamente.',
                        'success'
                        )
                }
            })
        },

        //Obtener categorias

        getCategories: function(){
            var url = 'products/create';
            axios.get(url).then(response => {
                this.categories = response.data.categories;
            });
        },

        //crear producto
        createProduct: function(){
            var url = 'products';
            axios.post(url, {
                'category_id': this.category_id,
                'name': this.name,
                'description': this.description,
                'stock': this.stock
            }).then(response => {
                this.getProducts(1, '');
                this.clear();
                $('#modalProduct').modal('hide');
                toastr.success('Nueva producto creada con exito', 'Producto', {
                    "closeButton": true,
                    "positionClass": "toast-bottom-right"
                });
            }).catch(error => {
                if (typeof error.response.data.errors.name != 'undefined') {
                    this.errorName = error.response.data.errors.name[0];
                    this.hasErrorName = true;
                }
                if (typeof error.response.data.errors.category_id != 'undefined') {
                    this.errorCategory = error.response.data.errors.category_id[0];
                    this.hasErrorCategory = true;
                }
                if (typeof error.response.data.errors.stock != 'undefined') {
                    this.errorStock = error.response.data.errors.stock[0];
                    this.hasErrorStock = true;
                }
            });
        },

        //actualizar producto
        updateProduct: function(id){
            var url = 'products/' + id;
            axios.put(url, {
                'ud': this.id,
                'category_id': this.category_id,
                'name': this.name,
                'description': this.description,
                'stock': this.stock
            }).then(response => {
                this.getProducts(1, '');
                this.clear();
                $('#modalProduct').modal('hide');
                toastr.success('Producto actualizado con exito', 'Producto', {
                    "closeButton": true,
                    "positionClass": "toast-bottom-right"
                });
            });
        },

        openModal: function(modal, product){
            if (modal == 'create'){
                this.typeAction = 1;
                this.modalTitle = 'Nueva Producto';
                this.clear();
                $('#modalProduct').modal('show');
                this.hasErrorFalse();
            }
            else if(modal == 'edit'){
                this.hasErrorFalse();
                this.typeAction = 2;
                this.modalTitle = 'Actulizar Producto';
                this.id = product.id;
                this.category_id = product.category_id;
                this.name = product.name;
                this.description = product.description;
                this.stock = product.stock;
                $('#modalProduct').modal('show');
            }
            this.getCategories();
        },

        clear: function(){
            this.id = 0;
            this.name = '';
            this.category_id = 'a';
            this.description = '';
            this.stock = 0;
            this.hasErrorFalse();
        },

        hasErrorFalse: function(){
            this.errorName = '';
            this.errorCategory = '';
            this.errorStock = '';
            this.hasErrorName = false;
            this.hasErrorCategory = false;
            this.hasErrorStock = false;
        },

        //Para el cambio de pagina
        changePage: function(page, search){
            this.pagination.current_page = page;
            this.getProducts(page, search);
        }
    }
}
</script>
