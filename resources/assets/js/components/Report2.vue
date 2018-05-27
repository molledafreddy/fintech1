<template>
    <div class="container">
        <div class="margen-components">
            <div class="panel panel-default">
                <table class="table table-bordered table-active">
                    <div class="pull-left">
                        <h3 style="padding-center:20px;">Reportes</h3>
                        <br>
                        <div class="col-md-12" style="padding-left:2px !important;">
                        
                Desde <input type="date"  v-model="from"/>
                Hasta <input type="date"  v-model="to"/>
                         
                <button type="button" class="btn btn-primary" @click="getCustomer()">Buscar</button>
            </div>
        </div>
    </table>
            


            </div>
                <div class="pull-left">
                    <br>
                    <div class="panel-body row">
                        <table class="table table-bordered table-active">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Empresa</th>
                                    <th scope="col">Transacciones</th>
                                    <th scope="col">Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="customer in customers">
                                    <th scope="row">{{customer.customer}}</th>
                                    <th scope="row">{{customer.transactions}}</th>
                                    <th scope="row">{{customer.amount}}</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3 text-center">
                            <paginate  v-if="ready" @go="go" @next="next" @prev="prev" :current="current" :last="last"></paginate>
                        </div>
                </div>
              </div>
        </div>
    </div>
</div>
</div>
</template>


<script>
// toastr
    import toastr from 'toastr';
// end toastr
    export default {
        data(){
            return{
                customers: [],
                from: null,
                to: null,
            }
        },
        mounted() {
            this.getCustomer()
        },
        methods:{
            getCustomer(){
                axios.get('api/v1/customer-report?from='+this.from+'&to='+this.to)
                    .then(response=>{
                        this.customers = response.data
                    })
                    .catch(error=>{
                        console.log(error.message)
                    })
            }
        },

    }
</script>