<template>
    <div class="container-fluid px-3 py-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            <i class="fa fa-align-justify"></i>
                            Salida de Productos del Almacén
                        </h4>
                    </div>
                    <!-- Listado de ingresos -->
                    <template v-if="list==1">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="criterionVoucher">Comprobante</label>
                                    <select v-model="criterionVoucher" class="form-control">
                                       <option value="" disabled>Seleccione</option>
                                       <option value="Boleta" autofocus>Boleta</option>
                                       <option value="Factura">Factura</option>
                                       <option value="Ticket">Ticket</option>
                                   </select>
                               </div>
                               <div class="form-group col-md-3">
                                <label for="initialDate">Fecha Inical</label>
                                <input v-model="initialDate" type="date" name="initialDate" class="form-control bg-light shadow-sm">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="finalDate">Fecha Final</label>
                                <input v-model="finalDate" type="date" name="finalDate" class="form-control bg-light shadow-sm">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="">Filtrar</label>
                                <button v-on:click.prevent="getOutputs(1, criterionVoucher, initialDate, finalDate)" type="submit" class="btn btn-primary form-control"><i class="fa fa-search"></i> Filtrar</button>
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
                                        <a v-on:click.prevent="showOutput(output)" href="#" class="btn btn-success btn-sm"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <nav class="mt-3">
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a href="#" class="page-link" v-on:click.prevent="changePage(pagination.current_page  - 1, criterionVoucher, initialDate, finalDate)">
                                        <span>Atras</span>
                                    </a>
                                </li>

                                <li class="page-item" v-for="page in pagesNumber" v-bind:class="[page == isActived ? 'active' : '']">
                                    <a href="#" class="page-link" v-on:click.prevent="changePage(page, criterionVoucher, initialDate, finalDate)">{{ page }}</a>
                                </li>

                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a href="#" class="page-link" v-on:click.prevent="changePage(pagination.current_page  + 1, criterionVoucher, initialDate, finalDate)">
                                        <span>Siguiente</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </template>
                <!--  Fin de listado de ingresos -->
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
            outputs: [],
            products: [],
            detailProducts: [],
            name: '',
            product: '',
            pagination: {
                'total': 0,
                'current_page': 0,
                'per_page': 0,
                'last_page': 0,
                'from': 0,
                'to': 0
            },
            id: 0,
            staff: '',
            branch: '',
            voucher_type: 'voucher_type',
            voucher_serie: '',
            voucher_number: '',
            observation: '',
            created_at: '',
            list: 1,
            modalTitle: '',
            offset: 3,
            criterionVoucher: '',
            initialDate: '',
            finalDate: '',
            criterionModal: 'name',
            code: '',
            quantity: 0,
        }
    },

    created: function(){
        this.getOutputs(1, '', '', '');
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
        getOutputs: function(page, criterionVoucher, initialDate, finalDate){
            var url = 'outputs/outputReport?page=' + page + '&criterionVoucher=' + criterionVoucher + '&initialDate=' + initialDate + '&finalDate=' + finalDate;
            axios.get(url).then(response => {
                this.outputs = response.data.outputs.data;
                this.pagination = response.data.pagination;
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
            this.initialDate = '';
            this.finalDate = '';
            this.criterionVoucher = '';
            this.page = 1;
            this.getOutputs(1, '', '', '');
        },

        //Para el cambio de pagina
        changePage: function(page, criterionVoucher, initialDate, finalDate){
            this.pagination.current_page = page;
            this.getOutputs(page, criterionVoucher, initialDate, finalDate);
        }
    }
}
</script>
