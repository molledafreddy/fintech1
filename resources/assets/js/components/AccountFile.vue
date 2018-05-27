<template>
    <div class="container">
        <div class="margen-components">
            <div class="panel panel-default"  style="margin-right:42px;">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h3 style="padding-left:28px;">Empleados</h3>
                        <div class="col-md-12">
                            <div class="form-group col-md-5" style="padding-right:2px; !important;">
                                <input class="form-control" type="text" v-model="target" placeholder="N cuenta ó clabe">
                            </div>                                
                            <div class="form-group col-md-5" style="padding-left:2px; padding-right:2px; !important;">
                                 <select class="form-control" v-model="customer_id">
                                    <option value="">Seleccione una empresa</option>
                                    <option v-for="customer in customers" :value="customer.id">
                                            {{customer.name}}
                                    </option>
                                </select>
                            </div>
                            <div class="form-group col-md-9 " style="padding-left:10px; !important;">
                                <select class="form-control" v-model="selected_file">
                                    <option disabled value="">Seleccione un archivo</option>
                                    <option v-for="(file, index) in files" :value="file.id" >{{file.name}}</option>
                                </select>
                            </div>
                                <div class="col-md-1 " style="padding-left:0px !important;">
                                      <button  @click="search" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                </div>
                        </div>
                    </div>
                    <div class="pull-right">
                         <button class="btn btn-primary" @click="showModalImport = true"> <i class="fa fa-file" aria-hidden="true"></i> Dar de alta</button>
                        <button class="btn btn-primary" @click="showModalFile = true"> <i class="fa fa-plus-square-o" aria-hidden="true"></i> Generar</button>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-body">
                    <div slot="body">
                        <div class="table-responsive">
                     <table class="table table-striped table-hover datatable-asc">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>N° Cuenta</th>
                                <th>N° clabe</th>
                                <th>Banco</th>
                                <th>Empresa</th>
                                <th>Estatus</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(user, index) in users">
                                <td>{{user.user.name}}</td>
                                <td>{{ user != null ? user.acconunt_number : ''}}</td> 
                                <td>{{ user != null ? user.acconunt_clabe : ''}}</td> 
                                <td>{{ user.bank.name }}</td>
                                <td>{{ user.customer.name }}</td>
                                <td>
                                    {{ user != null ? user.status : ''}}
                                    <!--<input type="checkbox" id="cbox2" value="second_checkbox"> -->
                                </td>
                                <td>
                                    <button class="btn btn-primary btn-xs" @click="edit(index)">
                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3 text-center">
                                    <paginate  v-if="ready" @go="go" @next="next" @prev="prev" :current="current" :last="last"></paginate>
                            </div>
                        </div>
                        <!-- Carga modal de archivo del usuario -->
                        <modal v-if="showModalFile" @close="showModalFile = false">
                            <h3 slot="header">Generar Archivo</h3>
                            <div slot="body">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Seleccione la cuenta de origen</label>
                                        <select class="form-control" v-model="value.account_id">
                                            <option disabled value="">Seleccione una cuenta</option>
                                            <option v-for="(account, index) in accounts" :value="account.bank_id" >{{account.bank.name}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Seleccione el banco Destino</label>
                                        <select class="form-control" v-model="value.bank_id">
                                            <option disabled value="">Seleccione un banco</option>
                                            <option value="all">Todos los bancos</option>
                                            <option v-for="(bank, index) in banks" :value="bank.id" >{{bank.name}}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 " style="padding-left:2px; padding-right:4px; padding-top: 19px;  !important;">
                                          <button @click="generate" class="btn btn-primary">Generar</button>
                                    </div>
                                    <div class="col-md-2 " style="padding-right:2px; padding-top: 19px;  !important;">
                                        <a class="btn btn-primary " download :href="'storage/users/'+fileName">Descargar</a>
                                    </div>
                                </div>                                    
                            </div>
                            <div slot="footer">
                                <button class="modal-default-button btn btn-warning" @click="showModalFile = false" >
                                  <i class="fa fa-times-circle" aria-hidden="true"></i>
                                    Atrás
                                </button>                            
                            </div>   
                        </modal>
                        <!--  END Carga de archivo del usuario-->
                        <modal v-if="showModal" @close="showModal = false" @save="save">
                        <h3 slot="header">Actualizar estatus</h3>
                        <div slot="body">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Estatus</label>
                                    <select class="form-control" v-model="draft.status">
                                        <option disabled value="">Seleccione un estatus</option>
                                        <option value="pending">Pending</option>
                                        <option value="verifying">Verifying</option>
                                        <option value="ready">Ready</option>
                                        <option value="error">Error</option>
                                    </select>
                                </div>
                            </div>
                        </div>                         
                    </modal>
                    <!-- Carga modal de archivo dcon log del banco -->
                    <modalstop v-if="showModalImport" @close="showModalImport = false" @save="loading">
                        <h3 slot="header">Cargar archivo</h3>
                        <div slot="body">
                            <div class="form-group">
                                <label for="">Seleccione un archivo</label>
                                <select class="form-control" v-model="selected_file">
                                    <option disabled value="">Seleccione un archivo</option>
                                    <option v-for="(file, index) in files" :value="file.id" >{{file.name}}</option>
                                </select>
                            </div>
                        </div>
                    </modalstop>
                    <!--  END Carga de archivo del usuario-->
                    <!-- modal que indica que se esta procesando la solicitud -->
                    <modal v-if="showModalGit" @close="showModalGit = false">
                        <h3 align="center" slot="header">Procesando</h3>
                        <div slot="body">
                            <div align="center">
                                <img class="img-admin" src="images/loading-circle.gif" alt="gif processing">
                            </div>
                        </div>
                         <div slot="footer"></div>    
                    </modal>
                    <!-- modal que indica que se esta procesando la solicitud -->
                    </div> 
                </div>
            </div>
         </div>
    </div>
</template>

<script>
import toastr from 'toastr';
    export default {
        data(){
            return{
                fileGenerate: {
                    type:''
                },
                import: {
                    file:'',
                    loadFileId:'',
                },
                selected_file:'',
                showModalFile: false,
                showModalGit: false,
                showModalImport: false,
                showModal: false, // crear un empleado
                currentIndex: null,
                currentId: null,
                users: [],
                accounts: [],
                banks: [],
                customers: [],
                files: [],
                customer_users: [],
                target: '',
                current: '',
                ready: false,
                customer_id: '',  
                last: '',
                fileName:'',
                draft: {},
                value:{
                    bank_id:'',
                    account_id:'',
                },
                id:'',
                status:''
                
            }
        },
        created() {
            this.getUsers(1);
        },

        methods:{
            search(target){
                this.current = 1;
                this.getUsers(this.current);
            },
            go(page){
                this.getUsers(page);
            },
            next(){
                this.current += 1;
                this.getUsers(this.current);
            },
            prev(){
                this.current -= 1;
                this.getUsers(this.current);
            },
            getUsers(page){
                var self = this;
                console.log(this.customer_id);
                axios.get('/api/v1/userFile?search=' + this.target + '&page=' + page + '&customer_id=' + this.customer_id + '&file=' +  this.selected_file)
                    .then(response => {
                        console.log(response.data.accounts);
                        self.users = response.data.users.data;
                        self.banks = response.data.banks;
                        self.accounts = response.data.accounts;
                        self.customers = response.data.customers;
                        self.files = response.data.files;
                        self.current = response.data.users.current_page;
                        self.last = response.data.users.last_page;
                        self.ready = response.data.users.last_page > 1;
                        this.target = '';
                        this.customer_id = '';
                    })
                    .catch(error => {
                         console.log(error);
                         toastr.error(error);
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
            update(){
                var self = this;    
                axios.put('/api/v1/file-validate-account/' + this.currentId, this.draft)
                    .then( function(response){  
                    console.log(response);                      
                        self.users[self.currentIndex] = self.draft;
                        self.draft = {};  
                        self.getUsers(1);
                            toastr.success(response.data.message);
                        
                    })
                    .catch(error => {
                        alert(error.data.message);
                        toastr.error(error);
                    })                  
            },
            edit(index){
               
                this.draft = Object.assign({}, this.users[index]);
                this.draft.status = this.users[index].status;
                this.currentIndex = index;
                this.currentId = this.users[index].id;
                this.showModal = true;
                this.editing = true;
            },
            generate(event){
                var self = this;
                this.showModalGit = true;
                this.showModalFile = false;
                if ((self.value.account_id != '') && (self.value.bank_id != '')) {
                    axios.post('/api/v1/file-validate-account', this.value)
                    .then(response => {                 
                        self.message = response.data.message;
                        self.fileName = response.data.fileName;
                        this.getUsers(1);
                        this.showModalGit = false;
                        this.showModalFile = true;
                        if (response.data.status==1) {
                            toastr.success(response.data.message);
                        }else{
                            toastr.error(response.data.message);
                        } 
                    })
                    .catch(error => {
                        //console.log(error);
                        toastr.error(error); 
                    })   
                }else{
                    toastr.warning("Debe seleccionar la cuenta de origen y banco de destino");

                }
                   
            },
            // cargando el archivo de empleados
            load(){
                this.showModalImport = true;
                this.showModalFile = true;
            },
            loading(event){

                var self = this;
                this.import.loadFileId = this.selected_file;
                this.showModalGit = true;
                this.showModalImport = false;
                console.log(this.import);
                    axios.post('/api/v1/file-validate-account/import', this.import)
                    .then(response => {                 
                        this.getUsers(1);
                        if (response.data.status==1) {
                            toastr.success(response.data.message);
                        }else{
                            toastr.warning(response.data.message);
                        }
                        this.showModalGit = false;
                        this.showModalResult = true;
                    })
                    .catch(error => {
                        console.log(error);
                        toastr.error(error);
                    })
                      
            },
            
        }
    }
</script>
