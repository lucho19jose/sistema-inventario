<template>
    <div class="container-fluid px-3 py-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            <i class="fa fa-align-justify"></i>
                            Entradas de Productos
                            <a v-on:click.prevent="showDetail()" href="#" class="btn btn-primary float-right">
                                <i class="fas fa-plus-circle"></i> Nuevo
                            </a>
                        </h4>
                    </div>
                    <!-- Listado de ingresos -->
                    <template v-if="list==1">
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <select v-model="criterion" class="form-control col-md-4">
                                            <option value="voucher_type" autofocus>Tipo de comprobante</option>
                                            <option value="voucher_number">Número de comprobante</option>
                                        </select>
                                        <input v-model="search" v-on:keyup.enter="getInputs(1, search, criterion)" type="text" class="form-control bg-light shadow-sm" placeholder="Entrada de inputo a buscar">
                                        <button v-on:click.prevent="getInputs(1, search, criterion)" type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Usuario</th>
                                        <th>Proveedor</th>
                                        <th>Tipo de Comprobante</th>
                                        <th>Número de Comprobante</th>
                                        <th>Observacion</th>
                                        <th>Fecha</th>
                                        <th>Total</th>
                                        <th colspan="2">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="input in inputs">
                                        <td>{{ input.user_email }}</td>
                                        <td>{{ input.provider_name }}</td>
                                        <td>{{ input.voucher_type }}</td>
                                        <td>{{ input.voucher_number }}</td>
                                        <td>{{ input.observation }}</td>
                                        <td>{{ date(input.created_at) }}</td>
                                        <td>{{ input.total }}</td>
                                        <td width="10px">
                                            <a v-on:click.prevent="" href="#" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                        </td>
                                        <td width="10px">
                                            <a v-on:click.prevent="showInput(input)" href="#" class="btn btn-success btn-sm"><i class="fas fa-eye"></i></a>
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
                    </template>
                    <!--  Fin de listado de ingresos -->
                    <!-- Registrar nuevo ingresos -->
                    <template v-else-if="list==0">
                        <div class="card-body">
                            <div class="form-group row border">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Proveedor(*)</label>
                                        <v-select
                                        class="bg-light shadow-sm"
                                        @search="selectProvider"
                                        label="name"
                                        :options="providers"
                                        placeholder="Buscar proveedor ...."
                                        @input="getProvider"
                                        >
                                    </v-select>
                                    <span class="text-danger" ole="alert">{{ errorProvider }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo de Comprobante(*)</label>
                                    <select v-model="voucher_type" class="form-control bg-light shadow-sm" :class="{ 'is-invalid': hasErrorVoucherType}">
                                        <option value="0">Seleccione</option>
                                        <option value="Boleta">Boleta</option>
                                        <option value="Factura">Factura</option>
                                        <option value="Ticket">Ticket</option>
                                    </select>
                                    <span class="text-danger" ole="alert">{{ errorVoucherType }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Serie Comprobante(*)</label>
                                    <input v-model="voucher_serie" type="text" class="form-control bg-light shadow-sm" :class="{ 'is-invalid': hasErrorVoucherSerie}" placeholder="000x">
                                    <span class="text-danger" ole="alert">{{ errorVoucherSerie }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Número Comprobante(*)</label>
                                    <input v-model="voucher_number" type="text" class="form-control bg-light shadow-sm" :class="{ 'is-invalid': hasErrorVoucherNumber}" placeholder="000xx">
                                    <span class="text-danger" ole="alert">{{ errorVoucherNumber }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Producto</label>
                                    <div class="form-inline row px-3">
                                        <input type="text" class="form-control bg-light shadow-sm col-md-5 col-sm-12" :class="{ 'is-invalid': hasErrorProductId }" v-model="code" v-on:keyup.enter="searchProduct()" placeholder="Codigo de producto">
                                        <button v-on:click.prevent="searchProduct()" class="btn btn-primary col-md-2" type="button">
                                            <i class="fa fa-search"></i>
                                        </button>
                                        <input type="text" readonly class="form-control col-md-5 col-sm-12" v-model="product">
                                    </div>
                                    <span class="text-danger" ole="alert">{{ errorProductId }}</span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Busqueda avanzada</label>
                                    <button v-on:click.prevent="openModal()" class="btn btn-primary form-control" type="button">
                                        <i class="fa fa-search"></i> Buscar
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Precio</label>
                                    <input v-model="price" type="number" value="0" step="any" class="form-control bg-light shadow-sm" :class="{ 'is-invalid': hasErrorPrice}">
                                    <span class="text-danger" ole="alert">{{ errorPrice }}</span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Cantidad</label>
                                    <input v-model="quantity" type="number" value="0" class="form-control bg-light" :class="{ 'is-invalid': hasErrorQuantity}">
                                    <span class="text-danger" ole="alert">{{ errorQuantity }}</span>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>Agregar</label>
                                    <button v-on:click.prevent="addDetail()" type="button" class="btn btn-success btn-sm form-control"><i class="fas fa-plus-circle"></i> </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="observation">Observacion</label>
                                    <textarea v-model="observation" class="form-control bg-light shadow-sm"
                                    name="description" placeholder="Ingrese observacion sobre los productos ...."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Producto</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="detailProducts.length">
                                        <tr v-for="(detail,index) in detailProducts">
                                            <td class="text-center">
                                                <button v-on:click.prevent="deleteDetail(index)" type="button" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </td>
                                            <td>{{ detail.product }}</td>
                                            <td>
                                                <input v-model="detail.price" type="number" value="3" class="form-control">
                                            </td>
                                            <td>
                                                <input v-model="detail.quantity" type="number" value="2" class="form-control">
                                            </td>
                                            <td>
                                                {{ detail.price*detail.quantity }}
                                            </td>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="4" align="right">
                                                    <strong>Total:</strong>
                                                </td>
                                                <td>S/. {{ total=calculateTotal() }}</td>
                                            </tr>
                                        </tbody>
                                        <tbody v-else>
                                            <tr>
                                                <td colspan="5">
                                                    No hay productos agregados
                                                </td>
                                            </tr>
                                        </tbody>
                                        <span class="text-danger" ole="alert">{{ errorTotal }}</span>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button type="button" v-on:click.prevent="hideDetail()" class="btn btn-secondary">Cerrar</button>
                                    <button type="button" class="btn btn-primary" v-on:click.prevent="createInput()">Registrar Ingreso</button>
                                </div>
                            </div>
                        </div>
                    </template>
                    <!-- Fin de registrar nuevo ingreso ingresos -->
                    <!-- Ver el ingreso a detail como un recibo -->
                    <template v-else-if="list==2">
                        <div class="card-body">
                            <div class="form-group row border">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="">Proveedor</label>
                                        <p>{{ provider }}</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Fecha</label>
                                        <p>{{ date(created_at) }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tipo Comprobante</label>
                                        <p>{{ voucher_type }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Serie Comprobante</label>
                                        <p>{{ voucher_serie }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Número Comprobante</label>
                                        <p>{{ voucher_number }}</p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Observacion</label>
                                        <p>{{ observation }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row border">
                                <div class="table-responsive col-md-12">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Producto</th>
                                                <th>Precio</th>
                                                <th>Cantidad</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="detailProducts.length">
                                            <tr v-for="detail in detailProducts">
                                                <td v-text="detail.product">
                                                </td>
                                                <td v-text="detail.price">
                                                </td>
                                                <td v-text="detail.quantity">
                                                </td>
                                                <td>
                                                    {{ detail.price*detail.quantity }}
                                                </td>
                                                <tr style="background-color: #CEECF5;">
                                                    <td colspan="3" align="right"><strong>Total:</strong></td>
                                                    <td>S/. {{ total }}</td>
                                                </tr>
                                            </tbody>
                                            <tbody v-else>
                                                <tr>
                                                    <td colspan="4">
                                                        No hay articulos agregados
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <button type="button" v-on:click.prevent="hideDetail()" class="btn btn-secondary">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                <!-- Modal para buscar producto -->
                <div>
                    <form method="POST">
                        <div class="modal fade" id="modalInput">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h3 class="modal-title">{{ modalTitle }}</h3>
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <select class="form-control col-md-4" v-model="criterionModal">
                                                        <option value="name">Nombre</option>
                                                        <option value="description">Descripción</option>
                                                        <option value="code">Código</option>
                                                    </select>
                                                    <input type="text" v-model="searchP" v-on:keyup.enter="getProduct(searchP, criterionModal)" class="form-control bg-light shadow-sm" placeholder="Producto a buscar">
                                                    <button type="submit" v-on:click.prevent="getProduct(searchP, criterionModal)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-sm">
                                             <thead>
                                                <tr>
                                                    <th>Código</th>
                                                    <th>Nombre</th>
                                                    <th>Categoría</th>
                                                    <th>Stock</th>
                                                    <th>Opciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="product in products">
                                                    <td>{{ product.code }}</td>
                                                    <td>{{ product.name }}</td>
                                                    <td>{{ product.category_name }}</td>
                                                    <td>{{ product.stock }}</td>
                                                    <td class="text-center">
                                                        <button type="button" v-on:click.prevent="addDetailModal(product)" class="btn btn-success btn-sm"><i class="fas fa-check-square"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- fin de modal para buscar producto -->
        </div>
    </div>
</template>

<script>
import moment from 'moment'
import vSelect from "vue-select"
Vue.component('v-select', vSelect)
import 'vue-select/dist/vue-select.css'
import Swal from 'sweetalert2/dist/sweetalert2.js'
import 'sweetalert2/src/sweetalert2.scss'

export default {
    data(){
        return {
            inputs: [],
            providers: [],
            products: [],
            detailProducts: [],
            name: '',
            product: '',
            product_id: 0,
            searchP: '',
            pagination: {
                'total': 0,
                'current_page': 0,
                'per_page': 0,
                'last_page': 0,
                'from': 0,
                'to': 0
            },
            id: 0,
            provider_id: 0,
            provider: '',
            user_id: 0,
            voucher_type: 'voucher_type',
            voucher_serie: '',
            voucher_number: '',
            observation: '',
            total: 0.0,
            created_at: '',
            errorProvider: '',
            errorUser: '',
            errorVoucherType: '',
            errorVoucherSerie: '',
            errorVoucherNumber: '',
            errorProductId: '',
            errorPrice: '',
            errorQuantity: '',
            errorTotal: '',
            list: 1,
            modalTitle: '',
            typeAction: 0,
            offset: 3,
            search: '',
            criterion: 'voucher_number',
            criterionModal: 'name',
            hasErrorProvider: false,
            hasErrorUser: false,
            hasErrorVoucherType: false,
            hasErrorVoucherSerie: false,
            hasErrorVoucherNumber: false,
            hasErrorProductId: false,
            hasErrorPrice: false,
            hasErrorQuantity: false,
            code: '',
            price: 0,
            quantity: 0,
        }
    },

    created: function(){
        this.getInputs(1, '', '');
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
            return moment(d).subtract(10, 'days').calendar();
        },

        //Obtner entradas
        getInputs: function(page, search, criterion){
            var url = 'inputs?page=' + page + '&search=' + search + '&criterion=' + criterion;
            axios.get(url).then(response => {
                this.inputs = response.data.inputs.data;
                this.pagination = response.data.pagination;
            });
        },

        showDetail: function(){
            this.list = 0;
            this.provider_id = 0;
            this.clear();
        },

        //Seleccionar al proveedor
        selectProvider: function(search, loading){
            loading(true)
            var url = 'providers/selectProvider?filter=' + search;
            axios.get(url).then(response => {
                q: search
                this.providers = response.data.providers;
                loading(false)
            }).catch(error => {
                console.log(error);
            });
        },

        //obtener provedor
        getProvider: function(val1){
            this.loading = true;
            this.provider_id = val1.id;
        },

        //Buscar productos
        searchProduct: function(){
            var url = 'products/searchProduct?filter=' + this.code;
            axios.get(url).then(response => {
                this.products = response.data.products;
                if (this.products.length > 0) {
                    this.product = this.products[0]['name'];
                    this.product_id = this.products[0]['id'];
                }
                else{
                    this.product = 'No existe este producto.';
                    this.product_id = 0;
                }
            });
        },

        //Obtener y listrar productos
        getProduct: function(search, criterionModal){
            var url = 'products/getProduct?search=' + search + '&criterion=' + criterionModal;
            axios.get(url).then(response => {
                this.products = response.data.products.data;
            });
        },

        //Agregar el detail del producto a la lista
        addDetail: function(){
            if (this.product_id == 0){
                this.errorProductId = 'Ingrese codigo de producto';
                this.hasErrorProductId = true;
            }
            if(this.quantity == 0){
                this.errorQuantity = 'Ingrese la cantidad';
                this.hasErrorQuantity = true;
            }
            if(this.price == 0) {
                this.errorPrice= 'Ingrese el precio';
                this.hasErrorPrice = true;
            }
            if(this.product_id == 0 || this.quantity == 0 || this.price == 0){

            }
            else{
                if (this.searchId(this.product_id)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Este producto ya se encuentra agregado!'
                    });
                }
                else{
                    this.detailProducts.push({
                        product_id: this.product_id,
                        product: this.product,
                        quantity: this.quantity,
                        price: this.price,
                    });
                    this.code = '';
                    this.product_id = 0;
                    this.product = '';
                    this.quantity = 0;
                    this.price = 0;
                    this.hasErrorProductId = false;
                    this.errorProductId = '';
                    this.hasErrorPrice = false;
                    this.errorPrice = '';
                    this.hasErrorQuantity = false;
                    this.errorQuantity = '';
                    toastr.success('Producto agregadp con exito', 'Producto', {
                        "closeButton": true,
                        "positionClass": "toast-bottom-right"
                    });
                }
            }
        },

        searchId(id){
            var sw = 0;
            for(var i = 0; i < this.detailProducts.length; i ++){
                if (this.detailProducts[i].product_id == id) {
                    sw = true;
                }
            }
            return sw;
        },

        //Agregar producto a la lista
        addDetailModal: function(data = []){
            if (this.searchId(data['id'])) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error...',
                    text: 'Este producto ya se encuentra agregado!'
                });
            }
            else{
                this.detailProducts.push({
                    product_id: data['id'],
                    product: data['name'],
                    quantity: 1,
                    price: 1,
                });
                toastr.success('Producto agregadp con exito', 'Producto', {
                    "closeButton": true,
                    "positionClass": "toast-bottom-right"
                });
            }
        },

        //Eliminar el producto agregado a la lista
        deleteDetail: function(index){
            console.log('Delete');
            this.detailProducts.splice(index, 1);
        },

        calculateTotal: function(){
            var result = 0.0;
            for(var i = 0; i < this.detailProducts.length; i ++){
                result=  result + (this.detailProducts[i].price * this.detailProducts[i].quantity);
            }
            return result;
        },

        createInput: function(){
            var url = 'inputs';
            axios.post(url, {
                'user_id': this.inputs[0]['user_id'],
                'provider_id': this.provider_id,
                'voucher_type': this.voucher_type,
                'voucher_serie': this.voucher_serie,
                'voucher_number': this.voucher_number,
                'observation': this.observation,
                'total': this.total,
                'data': this.detailProducts
            }).then(response => {
                this.getInputs(1, '', '');
                this.list = 1;
                this.clear();
                toastr.success('Nueva entrada de productos registrada con exito', 'Productos', {
                    "closeButton": true,
                    "positionClass": "toast-bottom-right"
                });
            }).catch(error => {
                if (typeof error.response.data.errors.provider_id != 'undefined') {
                    this.errorProvider = error.response.data.errors.provider_id[0];
                    this.hasErrorProvider = true;
                }
                if (typeof error.response.data.errors.voucher_type != 'undefined') {
                    this.errorVoucherType = error.response.data.errors.voucher_type[0];
                    this.hasErrorVoucherType = true;
                }
                if (typeof error.response.data.errors.voucher_serie != 'undefined') {
                    this.errorVoucherSerie = error.response.data.errors.voucher_serie[0];
                    this.hasErrorVoucherSerie = true;
                }
                if (typeof error.response.data.errors.voucher_number != 'undefined') {
                    this.errorVoucherNumber = error.response.data.errors.voucher_number[0];
                    this.hasErrorVoucherNumber = true;
                }
                if (typeof error.response.data.errors.total != 'undefined') {
                    this.errorTotal = error.response.data.errors.total[0];
                }
            });
        },

        hideDetail: function(){
            this.list = 1;
        },

        showInput: function(input){
            this.list = 2;
            var url = 'inputs/showInput?id=' + input.id;
            var inputProducts = [];
            axios.get(url).then(response => {
                var inputProducts = response.data.inputs;
                this.provider = inputProducts[0]['provider_name'];
                this.voucher_type = inputProducts[0]['voucher_type'];
                this.voucher_serie = inputProducts[0]['voucher_serie'];
                this.voucher_number= inputProducts[0]['voucher_number'];
                this.observation= inputProducts[0]['observation'];
                this.total = inputProducts[0]['total'];
                this.created_at = inputProducts[0]['created_at'];
                this.detailProducts = response.data.detailProducts;
            });
        },

        openModal: function(){
            this.products = [];
            $('#modalInput').modal('show');
            this.modalTitle = 'Seleccione los productos que desee';
        },

        clear: function(){
            this.id = 0;
            this.user_id = 0;
            this.provider_id = 0;
            this.voucher_type = 'Boleta';
            this.voucher_serie = '';
            this.voucher_number = '';
            this.observation = '';
            this.product = '';
            this.quantity = 0;
            this.price = 0;
            this.total = 0.0;
            this.detailProducts= [],
            this.hasErrorFalse();
        },

        hasErrorFalse: function(){
            this.errorUser= '',
            this.errorProvider = '';
            this.errorVoucherType = '';
            this.errorVoucherSerie = '';
            this.errorVoucherNumber = '';
            this.errorTotal = '';
            this.hasErrorUser = false;
            this.hasErrorProvider = false;
            this.hasErrorVoucherType = false;
            this.hasErrorVoucherSerie = false;
            this.hasErrorVoucherNumber = false;
        },

        //Para el cambio de pagina
        changePage: function(page, search, criterion){
            this.pagination.current_page = page;
            this.getInputs(page, search, criterion);
        }
    }
}
</script>
