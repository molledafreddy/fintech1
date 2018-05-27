<template>
    <div class="container">
        <div class="margen-components">
            <div class="row">
                <div class="panel panel-default" style="margin-right:20px;">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h3 style="padding-left:28px;">Reportes</h3>
                            <div class="col-md-12">
                            </div>
                            <div class="pull-right">
                                Desde
                                <input type="date" v-model="from" /> Hasta
                                <input type="date" v-model="to" />

                                <button type="button" class="btn btn-primary" @click="getCustomer()">Buscar</button>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover datatable-asc">
                                <thead>
                                    <tr>
                                        <th>Empresa</th>
                                        <th>Transacciones</th>
                                        <th>Monto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="customer in customers">
                                        <th>{{customer.customer}}</th>
                                        <th>{{customer.transactions}}</th>
                                        <th>{{customer.amount}}</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<<script>
    // toastr
    import toastr from 'toastr';
    // end toastr
    export default {
        data() {
                return {
                    customers: [],
                    from: null,
                    to: null,
                }
            },
            mounted() {
                this.getCustomer()
            },
            methods: {
                getCustomer() {
                    axios.get('api/v1/customer-report?from=' + this.from + '&to=' + this.to)
                        .then(response => {
                            this.customers = response.data
                        })
                        .catch(error => {
                            console.log(error.message)
                        })
                }
            },

    }
</script>


