<template>
    <div class="container-fluid px-3 py-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            <i class="fa fa-align-justify"></i>
                            Salida de Productos
                            <a v-if="$can('output.create')" v-on:click.prevent="showDetail()" href="#" class="btn btn-primary float-right">
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
                                        <input v-model="search" v-on:keyup.enter="getOutputs(1, search, criterion)" type="text" class="form-control bg-light shadow-sm" placeholder="Salida de producto a buscar">
                                        <button v-on:click.prevent="getOutputs(1, search, criterion)" type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Usuario</th>
                                        <th>Personal</th>
                                        <th>Sucursal</th>
                                        <th>Tipo de Comprobante</th>
                                        <th>Número de Comprobante</th>
                                        <th>Observacion</th>
                                        <th>Fecha</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="output in outputs">
                                        <td>{{ output.user_email }}</td>
                                        <td>{{ output.staff_name }}</td>
                                        <td>{{ output.branch_name }}</td>
                                        <td>{{ output.voucher_type }}</td>
                                        <td>{{ output.voucher_number }}</td>
                                        <td>{{ output.observation }}</td>
                                        <td>{{ date(output.created_at) }}</td>
                                        <td class="text-center" width="10px">
                                            <a v-if="$can('output.show')" v-on:click.prevent="showOutput(output)" href="#" class="btn btn-success btn-sm"><i class="fas fa-eye"></i></a>
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Personal(*)</label>
                                        <v-select
                                        class="bg-light shadow-sm"
                                        @search="selectStaff"
                                        label="staff_name"
                                        :options="staffs"
                                        placeholder="Buscar personal ...."
                                        @input="getStaff"
                                        >
                                    </v-select>
                                    <span class="text-danger" ole="alert">{{ errorStaff }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Sucursal(*)</label>
                                        <v-select
                                        class="bg-light shadow-sm"
                                        @search="selectBranch"
                                        label="name"
                                        :options="branches"
                                        placeholder="Buscar sucursal ...."
                                        @input="getBranch"
                                        >
                                    </v-select>
                                    <span class="text-danger" ole="alert">{{ errorBranch }}</span>
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
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label>Producto</label>
                                    <div class="form-inline row px-3">
                                        <input type="text" class="form-control bg-light shadow-sm col-md-5 col-sm-12" :class="{ 'is-invalid': hasErrorProductId }" v-model="code" v-on:keyup.enter="searchProduct()" placeholder="Codigo de producto">
                                        <button v-on:click.prevent="searchProduct()" class="btn btn-primary col-md-1" type="button">
                                            <i class="fa fa-search"></i>
                                        </button>
                                        <input type="text" readonly class="form-control col-md-6 col-sm-12" v-model="product">
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
                                            <th>Cantidad</th>
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
                                                <input v-model="detail.quantity" type="number" value="2" class="form-control">
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="5">
                                                No hay productos agregados
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" v-on:click.prevent="hideDetail()" class="btn btn-secondary">Cerrar</button>
                                <button type="button" class="btn btn-primary" v-on:click.prevent="createOutput()">Registrar Salida</button>
                            </div>
                        </div>
                    </div>
                </template>
                <!-- Fin de registrar nuevo ingreso ingresos -->
                <!-- Ver el ingreso a detail como un recibo -->
                <template v-else-if="list==2">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="">Personal</label>
                                    <p>{{ staff }}</p>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="">Sucursal</label>
                                    <p>{{ branch }}</p>
                                </div>
                            </div>
                            <div class="col-md-2">
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
                                            <th>Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="detailProducts.length">
                                        <tr v-for="detail in detailProducts">
                                            <td v-text="detail.product">
                                            </td>
                                            <td v-text="detail.quantity">
                                            </td>
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
                <div class="modal fade" id="modalOutput">
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
//Cambiar el formato de las fechas al español
moment.locale('es');
export default {
    data(){
        return {
            outputs: [],
            staffs: [],
            products: [],
            detailProducts: [],
            branches: [],
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
            staff_id: 0,
            staff: '',
            branch_id: 0,
            branch: '',
            user_id: 0,
            user_id_log: '',
            voucher_type: 'voucher_type',
            voucher_serie: '',
            voucher_number: '',
            observation: '',
            created_at: '',
            errorStaff: '',
            errorUser: '',
            errorVoucherType: '',
            errorVoucherSerie: '',
            errorVoucherNumber: '',
            errorProductId: '',
            errorQuantity: '',
            errorBranch: '',
            list: 1,
            modalTitle: '',
            typeAction: 0,
            offset: 3,
            search: '',
            criterion: 'voucher_number',
            criterionModal: 'name',
            hasErrorStaff: false,
            hasErrorUser: false,
            hasErrorVoucherType: false,
            hasErrorVoucherSerie: false,
            hasErrorVoucherNumber: false,
            hasErrorProductId: false,
            hasErrorQuantity: false,
            hasErrorBranch: false,
            code: '',
            quantity: 0,
        }
    },

    created: function(){
        this.getOutputs(1, '', '');
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

        //Obtner Ingresos
        getOutputs: function(page, search, criterion){
            var url = 'outputs?page=' + page + '&search=' + search + '&criterion=' + criterion;
            axios.get(url).then(response => {
                this.outputs = response.data.outputs.data;
                this.pagination = response.data.pagination;
                this.user_id_log = response.data.user_id_log;
            });
        },

        showDetail: function(){
            this.list = 0;
            this.staff_id = 0;
            this.branch_id = 0;
            this.clear();
        },

        //Seleccionar al proveedor
        selectStaff: function(search, loading){
            loading(true)
            var url = 'staffs/selectStaff?filter=' + search;
            axios.get(url).then(response => {
                q: search
                this.staffs = response.data.staffs;
                loading(false)
            }).catch(error => {
                console.log(error);
            });
        },

        //obtener provedor
        getStaff: function(val1){
            this.loading = true;
            this.staff_id = val1.id;
        },

        //Seleccionar la sucursal
        selectBranch: function(search, loading){
            loading(true)
            var url = 'branches/selectBranch?filter=' + search;
            axios.get(url).then(response => {
                q: search
                this.branches = response.data.branches;
                loading(false)
            }).catch(error => {
                console.log(error);
            });
        },

        //obtener sucursal
        getBranch: function(val1){
            this.loading = true;
            this.branch_id = val1.id;
        },

        //Buscar productos
        searchProduct: function(){
            var url = 'products/searchProduct?filter=' + this.code;
            axios.get(url).then(response => {
                this.products = response.data.products;
                if (this.products.length > 0) {
                    this.product = this.products[0]['name'];
                    this.product_id = this.products[0]['id'];
                    this.errorProductId = '';
                    this.hasErrorProductId = false;
                }
                else{
                    this.product = 'No existe este producto.';
                    this.hasErrorProductId = true;
                    this.errorProductId = 'No existe este producto.';
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
            if(this.product_id == 0 || this.quantity == 0){

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
                    });
                    this.code = '';
                    this.product_id = 0;
                    this.product = '';
                    this.quantity = 0;
                    this.hasErrorProductId = false;
                    this.errorProductId = '';
                    this.hasErrorQuantity = false;
                    this.errorQuantity = '';
                    toastr.success('Producto agregado con exito', 'Producto', {
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
                });
                toastr.success('Producto agregado con exito', 'Producto', {
                    "closeButton": true,
                    "positionClass": "toast-bottom-right"
                });
            }
        },

        //Eliminar el producto agregado a la lista
        deleteDetail: function(index){
            this.detailProducts.splice(index, 1);
        },

        createOutput: function(){
            var url = 'outputs';
            axios.post(url, {
                'user_id': this.user_id_log,
                'staff_id': this.staff_id,
                'branch_id': this.branch_id,
                'voucher_type': this.voucher_type,
                'voucher_serie': this.voucher_serie,
                'voucher_number': this.voucher_number,
                'observation': this.observation,
                'data': this.detailProducts
            }).then(response => {
                this.getOutputs(1, '', '');
                this.list = 1;
                this.clear();
                toastr.success('Nueva salida de productos registrada con exito', 'Productos', {
                    "closeButton": true,
                    "positionClass": "toast-bottom-right"
                });
            }).catch(error => {
                if (typeof error.response.data.errors.staff_id != 'undefined') {
                    this.errorStaff = error.response.data.errors.staff_id[0];
                    this.hasErrorStaff = true;
                }
                if (typeof error.response.data.errors.branch_id != 'undefined') {
                    this.errorBranch = error.response.data.errors.branch_id[0];
                    this.hasErrorBranch = true;
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
            });
        },

        hideDetail: function(){
            this.list = 1;
        },

        showOutput: function(output){
            this.list = 2;
            var url = 'outputs/showOutput?id=' + output.id;
            var outputProducts = [];
            axios.get(url).then(response => {
                var outputProducts = response.data.outputs;
                this.staff = outputProducts[0]['staff_name'];
                this.branch = outputProducts[0]['branch_name'];
                this.voucher_type = outputProducts[0]['voucher_type'];
                this.voucher_serie = outputProducts[0]['voucher_serie'];
                this.voucher_number= outputProducts[0]['voucher_number'];
                this.observation= outputProducts[0]['observation'];
                this.created_at = outputProducts[0]['created_at'];
                this.detailProducts = response.data.detailProducts;
            });
        },

        openModal: function(){
            this.products = [];
            $('#modalOutput').modal('show');
            this.modalTitle = 'Seleccione los productos que desee';
        },

        clear: function(){
            this.id = 0;
            this.user_id = 0;
            this.staff_id = 0;
            this.branch_id = 0;
            this.voucher_type = 'Boleta';
            this.voucher_serie = '';
            this.voucher_number = '';
            this.observation = '';
            this.product = '';
            this.quantity = 0;
            this.detailProducts= [];
            this.hasErrorFalse();
        },

        hasErrorFalse: function(){
            this.errorUser= '',
            this.errorStaff = '';
            this.errorVoucherType = '';
            this.errorVoucherSerie = '';
            this.errorVoucherNumber = '';
            this.errorBranch = '';
            this.hasErrorUser = false;
            this.hasErrorStaff = false;
            this.hasErrorVoucherType = false;
            this.hasErrorVoucherSerie = false;
            this.hasErrorVoucherNumber = false;
            this.hasErrorBranch = false;
        },

        //Para el cambio de pagina
        changePage: function(page, search, criterion){
            this.pagination.current_page = page;
            this.getOutputs(page, search, criterion);
        }
    }
}
</script>
