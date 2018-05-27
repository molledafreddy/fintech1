<template>
    <div class="container">
        <div class="margen-components">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h3>Cuentas</h3> 
                        </div>
                        <div class="pull-right">
                            <button class="btn btn-primary" @click="showModal = true"><i class="fa fa-plus-square-o" aria-hidden="true"></i> Nuevo</button>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Number</th>
                                    <th>Banco</th>
                                    <th>Monto dario</th>
                                    <th>Estatus</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(account, index) in accounts">
                                    <td><a href="#" @click="show(account.id)">{{account.number}}</a></td>
                                    <td>{{account.bank.name}}</td>
                                    <td>{{account.daily_amount}}</td>
                                    <td>
                                        
                                        <template v-if="account.status=='available'">
                                            <span  class="label label-success" value="available">Habilitada</span>
                                        </template>
                                        <template v-else>
                                            <span class="label label-warning" value="disable">desabilidata</span>
                                        </template>
                                    </td>
                                    <td>
                                        <button class="btn btn-primary btn-xs" @click="edit(index)">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </button>
                                    </td>
                                    <!-- <td>
                                        <button class="btn btn-danger btn-xs" @click="destroy(bank.id)">
                                            Eliminar
                                        </button>
                                    </td> -->
                                </tr>
                            </tbody>                               
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3 text-center">
                            <paginate  v-if="ready" @go="go" @next="next" @prev="prev" :current="current" :last="last"></paginate>
                        </div>
                    </div>                        
                    <modal v-if="showModal" @close="showModal = false" @save="save">
                        <h3 slot="header">Registrar cuenta</h3>
                        <div slot="body">
                            <div class="form-group" :class="! validNumber ? 'has-error' : ''">
                                <label for="">Numero</label>
                                <input type="text" name="number" class="form-control" v-model="draft.number">
                            </div>
                            <div class="form-group" :class="! validAmount ? 'has-error' : ''">
                                <label for="">Monto de transferencia diario</label>
                                <input type="text" name="daily_amount" class="form-control" v-model="draft.daily_amount">
                            </div>
                            <div class="form-group">
                                <label for="">Estatus</label>
                                <select class="form-control" v-model="draft.status">
                                    <option disabled value="">Seleccione un Estatus</option>
                                    <option :value="t1">Habilitada</option>
                                    <option :value="t2">Desabilitada</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Bancos</label>
                                <select class="form-control" v-model="draft.bank_id">
                                    <option disabled value="">Seleccione un banco</option>
                                    <option v-for="(bank, index) in banks" :value="bank.id" >{{bank.name}}</option>
                                </select>
                            </div>
                        </div>
                    </modal>                           
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
                editing: false,
                showModal: false,
                accounts: [],
                banks: [],
                draft: {
                     number:'',
                     daily_amount:'',
                },
                currentIndex: null,
                currentId: null,
                current: null,
                ready: false,
                last: '',
                target: '',
                t1:'available',
                t2:'disable',


            }
        },
        created() {
            this.getAccounts(1);
        },

        methods:{
            go(page){
                console.log(page);
               this.getAccounts(page);
           },
           next(){
               this.current +=1;
               this.getAccounts(this.current);
           },
           prev(){
               this.current -= 1;
               this.getAccounts(this.current);
           },
            getAccounts(page){
                var self = this;
                axios.get('/api/v1/account?page=' + page)
                    .then(function(response) {
                        self.accounts = response.data.accounts.data;
                        self.banks = response.data.banks;
                        self.current = response.data.accounts.current_page;
                        self.last = response.data.accounts.last_page;
                        self.ready = response.data.accounts.last_page > 1;
                    })
                    .catch(error => {
                        toastr.error(error);
                        console.log(error);
                    })
            },
            save(){
                if (this.editing) {
                    this.update();
                }
                else{
                    this.store();
                }
                this.editing = false;
                this.showModal = false;
            },
            store(){
                var self = this;
                axios.post('/api/v1/account', this.draft)
                    .then(response => { 
                        console.log(response);
                        // this.getAccounts();                       
                        // self.draft = {};               
                        if (response.data.status ==1) {
                            toastr.success(response.data.message);
                            self.accounts.push(self.draft);
                        }else{
                            toastr.warning(response.data.message);
                        }
                    })
                    .catch(error => {
                       // alert(error.data.message);
                       toastr.error(error);
                    })                
            },
            update(){
                var self = this;
                axios.put('/api/v1/account/' + this.currentId, this.draft)
                    .then( function(response){
                       if (response.data.status ==1) {
                            self.accounts[self.currentIndex] = self.draft;
                            self.draft = {number:'', daily_amount:''};  
                            toastr.success(response.data.message);
                            self.getAccounts();
                        }else{
                            toastr.warning(response.data.message);
                        }
                    })
                    .catch(error => {
                        toastr.error(error);
                    })                  
            },
            edit(index){
                this.draft = Object.assign({}, this.accounts[index]);
                this.draft.number = this.accounts[index].number;
                this.draft.status = this.accounts[index].status;
                this.draft.daily_amount = this.accounts[index].daily_amount;
                this.draft.bank_id = this.accounts[index].bank_id;
                this.currentIndex = index;
                this.currentId = this.accounts[index].id;
                this.showModal = true;
                this.editing = true;
            },
            destroy(id){
               var self = this;
                axios.get('/api/v1/account/delete?id=' + id)
                    .then(function(response) {
                        this.getAccounts();
                        console.log(response);
                       // alert(response.data.message);
                       toastr.success(response.data.message);

                        self.getAccounts();
                    })
                    .catch(error => {
                        //alert(error.data.message);
                        toastr.error(error);
                    }) 
            },
        },
        computed:{
            validForm(){
                return this.validNumber;
            },
            validNumber(){
                return this.draft.number.length == 11;
            },
            validAmount(){
                return this.draft.daily_amount.length >3;
            },
        }
    }
</script>
