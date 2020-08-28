<template>
    <div class="container-fluid px-3 py-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            <i class="fa fa-align-justify"></i>
                            Categorías
                            <a v-on:click.prevent="openModal('create')" href="#" class="btn btn-primary float-right">
                                <i class="fas fa-plus-circle"></i> Nuevo
                            </a>
                        </h4>
                    </div>

                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input v-model="search" v-on:keyup.enter="getCategories(1, search)" type="text" class="form-control bg-light shadow-sm" placeholder="Categorías a buscar">
                                    <button v-on:click.prevent="getCategories(1, search)" type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripcion</th>
                                    <th colspan="2">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="category in categories">
                                    <td>{{ category.name }}</td>
                                    <td>{{ category.description }}</td>
                                    <td width="10px">
                                        <a v-on:click.prevent="openModal('edit', category)" href="#" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                    </td>
                                    <td width="10px">
                                        <a v-on:click.prevent="deleteCategory(category)" href="#" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>
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
                        <div class="modal fade" id="modalCategory">
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
                                            <input v-model="name" placeholder="Nombre de la categoria ...." type="text" name="name" class="form-control bg-light shadow-sm" :class="{ 'is-invalid': hasError}">
                                            <span class="text-danger" ole="alert">{{ errors }}</span>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Descripcion</label>
                                            <textarea v-model="description" class="form-control bg-light shadow-sm"
                                            name="description" placeholder="Ingrese descripcion ...."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        <button v-if="typeAction==1" type="button" class="btn btn-primary" value="Guardar" v-on:click.prevent="createCategory">Guardar</button>
                                        <button v-if="typeAction==2" type="button" class="btn btn-primary" value="Guardar" v-on:click.prevent="updateCategory(id)">Actulizar</button>
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
            errors: '',
            modalTitle: '',
            typeAction: 0,
            offset: 3,
            search: '',
            hasError: false,
        }
    },

    created: function(){
        this.getCategories(1, '');
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
        getCategories: function(page, search){
            var urlCategory = 'categories?page=' + page + '&search=' + search;
            axios.get(urlCategory).then(response => {
                this.categories = response.data.categories.data;
                this.pagination = response.data.pagination;
            });
        },

        //Elimnar una categoria
        deleteCategory: function(category){
            var url = 'categories/' + category.id;
            //Mensaje de confirmacion
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
                        this.getCategories(1, '');
                    });
                    //Asta qui
                    Swal.fire(
                        'Eliminado!',
                        'Categoría eliminado correctamente.',
                        'success'
                        )
                }
            })
        },
        //crear
        createCategory: function(){
            var url = 'categories';
            axios.post(url, {
                'name': this.name,
                'description': this.description
            }).then(response => {
                this.getCategories(1, '');
                this.clear();
                $('#modalCategory').modal('hide');
                toastr.success('Nueva categoría creada con exito', 'Categoría', {
                    "closeButton": true,
                    "positionClass": "toast-bottom-right"
                });
            }).catch(error => {
                this.errors = error.response.data.errors.name[0];
                this.hasError = true;
            });
        },

        //Actulizar categoria
        updateCategory: function(id){
            var url = 'categories/' + id;
            axios.put(url, {
                'id': this.id,
                'name': this.name,
                'description': this.description
            }).then(response => {
                this.getCategories(1, '');
                this.id = 0;
                this.clear();
                $('#modalCategory').modal('hide');
                toastr.success('Categoría actualizada con exito', 'Categoría', {
                    "closeButton": true,
                    "positionClass": "toast-bottom-right"
                });
            }).catch(error => {
                this.errors = error.response.data.errors.name[0];
                this.hasError = true;
            });
        },

        openModal: function(modal, category){
            if (modal == 'create'){
                this.typeAction = 1;
                this.modalTitle = 'Nueva Categoría';
                this.clear();
                $('#modalCategory').modal('show');
                if (this.name != ''){
                    this.hasError = false;
                }
            }
            else if(modal == 'edit'){
                this.hasError = false;
                this.errors = '';
                this.typeAction = 2;
                this.modalTitle = 'Actulizar Categoría';
                this.id = category.id;
                this.name = category.name;
                this.description = category.description;
                $('#modalCategory').modal('show');
            }
        },

        clear: function(){
            this.name = '';
            this.description = '';
            this.errors = '';
            this.hasError = false;
        },

        //Para el cambio de pagina
        changePage: function(page, search){
            this.pagination.current_page = page;
            this.getCategories(page, search);
        }
    }
}
</script>
