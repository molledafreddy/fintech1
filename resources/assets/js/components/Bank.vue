<template>
    <div class="container">
        <div class="margen-components">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h3>Bancos</h3>
                            <search @search="search"></search>  
                        </div>
                        <div class="pull-right">
                            <button class="btn btn-primary" @click="showModal = true">Nuevo</button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover datatable-asc">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Número</th>
                                        <th>Clave transferencia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(bank, index) in banks">
                                        <td><a href="#" @click="show(bank.id)">{{bank.name}}</a></td>
                                        <td>{{bank.number}}</td>
                                        <td>{{bank.transfer_key}}</td>
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
                            <h3 slot="header">Crear Banco</h3>
                            <div slot="body">
                                <div class="form-group" :class="! validName ? 'has-error' : ''">
                                    <label for="">Nombre</label>
                                    <input type="text" name="name" class="form-control" v-model="draft.name">
                                </div>
                                <div class="form-group" :class="! validNumber ? 'has-error' : ''">
                                    <label for="">Número</label>
                                    <input type="text" name="number" class="form-control" v-model="draft.number">
                                </div>
                                <div class="form-group" :class="! validTransferKey ? 'has-error' : ''">
                                    <label for="">Clave transferencias</label>
                                    <input type="text" name="transfer_key" class="form-control" v-model="draft.transfer_key">
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
                banks: [],
                draft: {
                    name:'',
                    number:'',
                    transfer_key:''
                },
                currentIndex: null,
                currentId: null,
                current: null,
                ready: false,
                last: '',
                target: '',

            }
        },
        mounted() {
            this.getBanks(1);
        },

        methods:{
            search(target){
                this.target = target;
                this.current = 1;
                this.getBanks(this.current);
            },
            go(page){
                console.log(page);
               this.getBanks(page);
           },
           next(){
               this.current +=1;
               this.getBanks(this.current);
           },
           prev(){
               this.current -= 1;
               this.getBanks(this.current);
           },
            getBanks(page){
                var self = this;
                axios.get('/api/v1/bank?search=' + this.target + '&page=' + page)
                    .then(function(response) {
                        console.log(response);
                        self.banks = response.data.data;
                        self.current = response.data.current_page;
                        self.last = response.data.last_page;
                        self.ready = response.data.last_page > 1;
                    })
                    .catch(error => {
                       // console.log(error);
                       // toastr.error(error);
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
                axios.post('/api/v1/bank', this.draft)
                    .then(response => { 
                        self.banks.push(self.draft);
                        if (response.data.status ==1) {
                            toastr.success(response.data.message);
                            this.getBanks();
                            self.name='';
                            self.number='';
                            self.transfer_key='';
                            
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
                axios.put('/api/v1/bank/' + this.currentId, this.draft)
                    .then( function(response){
                       if (response.data.status ==1) {
                            toastr.success(response.data.message);
                            this.getBanks(1);
                            self.name='';
                            self.number='';
                            self.transfer_key='';
                                                    
                        }else{
                            toastr.warning(response.data.message);
                        }
                    })
                    .catch(error => {
                    })                  
            },
            edit(index){
                this.draft = Object.assign({}, this.banks[index]);
                this.currentIndex = index;
                this.currentId = this.banks[index].id;
                this.showModal = true;
                this.editing = true;
            },
            destroy(id){
               var self = this;
                axios.get('/api/v1/bank/delete?id=' + id)
                    .then(function(response) {
                        this.getBanks();
                        console.log(response);
                       // alert(response.data.message);
                       toastr.success(response.data.message);
                        self.getbanks();
                    })
                    .catch(error => {
                        //alert(error.data.message);
                        toastr.error(error);
                    }) 
            },
            clean(){
                var self = this;

                self.name='';
                self.number='';
                self.transfer_key='';
            },
        },

        computed:{
          validForm(){
              return  this.validName &&  this.validNumber && this.validTransferKey;
          },

          validName(){
              return this.draft.name.length > 3;
          },

          
            validNumber(){
              return this.draft.number.length == 5;
          },
            validTransferKey(){
              return this.draft.transfer_key.length == 5;
          },
      },

    }
</script>
