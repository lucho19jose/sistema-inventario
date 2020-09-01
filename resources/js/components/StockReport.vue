<template>
    <div class="container-fluid px-3 py-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            <i class="fa fa-align-justify"></i>
                            Stock Disponible de Productos
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-7">
                                <label for="">Buscar</label>
                                <div class="input-group">
                                    <select v-model="criterion" class="form-control col-md-3">
                                        <option value="name" autofocus>Nombre</option>
                                        <option value="description">Descripcion</option>
                                        <option value="code">Codigo</option>
                                    </select>
                                    <input v-model="search" v-on:keyup.enter="getProducts(1, search, criterion, order)" type="text" class="form-control bg-light shadow-sm" placeholder="Producto a buscar">
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="">Ordenar</label>
                                <select v-model="order" class="form-control">
                                    <option value="" disabled>Seleccione</option>
                                    <option value="ASC">Acendente</option>
                                    <option value="DESC">Descendente</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="">Buscar</label>
                                <button v-on:click.prevent="getProducts(1, search, criterion, order)" type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Codigo</th>
                                    <th>Nombre</th>
                                    <th>Categoría</th>
                                    <th>Descripcion</th>
                                    <th>Stock</th>
                                    <th>Ultima modificacion</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="product in products">
                                    <td>{{ product.code }}</td>
                                    <td>{{ product.name }}</td>
                                    <td>{{ product.category_name }}</td>
                                    <td>{{ product.description }}</td>
                                    <td>{{ product.stock }}</td>
                                    <td>{{ date(product.updated_at) }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <nav class="mt-3">
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a href="#" class="page-link" v-on:click.prevent="changePage(pagination.current_page  - 1, search, criterion, order)">
                                        <span>Atras</span>
                                    </a>
                                </li>

                                <li class="page-item" v-for="page in pagesNumber" v-bind:class="[page == isActived ? 'active' : '']">
                                    <a href="#" class="page-link" v-on:click.prevent="changePage(page, search, criterion, order)">{{ page }}</a>
                                </li>

                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a href="#" class="page-link" v-on:click.prevent="changePage(pagination.current_page  + 1, search, criterion, order)">
                                        <span>Siguiente</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment'
//Cambiar el formato de las fechas al español
moment.locale('es');
export default {
    data(){
        return {
            products: [],
            pagination: {
                'total': 0,
                'current_page': 0,
                'per_page': 0,
                'last_page': 0,
                'from': 0,
                'to': 0
            },
            id: 0,
            code: '',
            name: '',
            description: '',
            stock: 0,
            category_id: 'a',
            modalTitle: '',
            typeAction: 0,
            offset: 3,
            search: '',
            criterion: 'name',
            order: 'DESC',
        }
    },

    created: function(){
        this.getProducts(1, '', '', 'DESC');
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

        //Obtner productos
        getProducts: function(page, search, criterion, order){
            var urlProduct = 'products/stockReport?page=' + page + '&search=' + search + '&criterion=' + criterion + '&order=' + order;
            axios.get(urlProduct).then(response => {
                this.products = response.data.products.data;
                this.pagination = response.data.pagination;
            });
            console.log(order);
        },


        //Para el cambio de pagina
        changePage: function(page, search, criterion, order){
            this.pagination.current_page = page;
            this.getProducts(page, search, criterion, order);
        }
    }
}
</script>
