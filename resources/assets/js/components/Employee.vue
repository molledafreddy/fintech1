<template>
    <div class="container">
        <div class="margen-components">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h3 style="padding-left:20px;">Empleados</h3>
                        <!-- <search @search="search"></search> -->
                        <div class="col-md-12">
                            <div class="form-group col-md-10" style="padding-left:2px; padding-right:2px; !important;">
                                 <select class="form-control" v-model="customer_search">
                                    <option value="">Seleccione una opción</option>
                                    <option value=" ">Todos</option>
                                    <option v-for="customer in customers" :value="customer.id">
                                            {{customer.name}}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2" style="padding-left:2px !important;">
                                  <button  @click="search" class="btn btn-primary">Buscar</button>
                            </div>
                        </div>
                    </div>
                    <div class="pull-right">
                         <button class="btn btn-primary" @click="showModalImport = true"> <i class="fa fa-file" aria-hidden="true"></i>  Carga de archivo</button>
                         <!-- <a href="users" class="btn btn-primary"> <i class="fa fa-file" aria-hidden="true">Modelo de archivo</i></a> -->
                         <!-- <button  href="users" class="btn btn-primary"> <i class="fa fa-file" aria-hidden="true"></i> Modelo de archivo </button> -->
                        <button class="btn btn-primary" @click="showModal = true"> <i class="fa fa-plus-square-o" aria-hidden="true"></i> Nuevo</button>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-body row">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover datatable-asc">
                            <thead>
                                <tr>
                                    <th>RFC</th>
                                    <th>Nombre</th>
                                    <!--<th>Nacionalidad</th>-->
                                    <!--<th>Email</th>-->
                                    <!--<th>Role</th>-->
                                    <th>Teléfono</th>
                                    <th>Salario</th>
                                    <th>N° Cuenta</th>
                                    <th>N° Cuenta clabe</th>
                                    <th>Banco</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(user, index) in users">
                                    <td>{{user.rfc}}</td>                   
                                    <td>{{user.name}}</td>
                                    <td>{{ user.customer_user != null ? user.customer_user.phone : ''}}</td>
                                    <td>{{ user.customer_user != null ? user.customer_user.biweekly_salary : ''}}</td>
                                    <td>{{ user.customer_user != null ? user.customer_user.acconunt_number : ''}}</td> 
                                    <td>{{ user.customer_user != null ? user.customer_user.acconunt_clabe : ''}}</td> 
                                    <td>{{ user.banks.length > 0 ? user.banks[0].name : '' }}</td>
                                    <td>
                                        <button class="btn btn-primary btn-xs" @click="edit(index)">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </button>
                                        <button class="btn btn-danger btn-xs" @click="destroy(user.id)">
                                            <i class="fa fa-trash-o" aria-hidden="true"></i>
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
                    <modal v-if="showModal" @close="showModal = false" @save="save">
                        <h3 slot="header">Crear empleado</h3>
                        <div slot="body">
                            <div class="col-md-4">
                                 <div class="form-group" :class="! validRfc ? 'has-error' : ''">
                                    <label for="">RFC</label>
                                    <input type="text" class="form-control" v-model="draft.rfc"  maxlength="13">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group" :class="! validName ? 'has-error' : ''">
                                    <label for="">Nombre</label>
                                    <input type="text" class="form-control" v-model="draft.name">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group" :class="! validNacionality ? 'has-error' : ''">
                                    <label for="">Nacionalidad</label>
                                    <input type="text" class="form-control" v-model="draft.nacionality">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group" :class="! validEmail ? 'has-error' : ''">
                                    <label for="">Email</label>
                                    <input type="email" class="form-control" v-model="draft.email">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group" :class="! validPhone ? 'has-error' : ''">
                                    <label for="">Teléfono</label>
                                    <input type="text" class="form-control" v-model="draft.phone">
                                </div>                                
                            </div>
                            <div class="col-md-4" v-if="editing==false">
                                <div class="form-group" :class="! validPassowrd ? 'has-error' : ''">
                                    <label for="">Password</label>
                                    <input type="password" class="form-control" v-model="draft.password">
                                </div>                                    
                            </div>
                            <div class="col-md-4">
                                <div class="form-group" :class="! validBiweekly ? 'has-error' : ''">
                                    <label for="">Salario</label>
                                    <input type="text" class="form-control" v-model="draft.biweekly_salary">
                                </div>                                
                            </div>
                            <div class="col-md-4">
                                <div class="form-group" :class="! validAcconuntNumber ? 'has-error' : ''">
                                    <label for="">N° Cuenta</label>
                                    <input type="text" class="form-control" v-model="draft.acconunt_number">
                                </div>                                
                            </div>
                            <div class="col-md-4">
                                <div class="form-group" :class="! validAcconuntClabe ? 'has-error' : ''">
                                    <label for="">N° clabe</label>
                                    <input type="text" class="form-control" v-model="draft.acconunt_clabe">
                                </div>                                
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Bancos</label>
                                    <select class="form-control" v-model="draft.bank_id">
                                        <option disabled value="">Seleccione un banco</option>
                                        <option v-for="(bank, index) in banks" :value="bank.id" >{{bank.name}}</option>
                                    </select>
                                </div>
                            </div>                           
                        </div>    
                    </modal>
                    <!-- resultados de la carga masiva de usuarios -->
                    <modal v-if="showModalResult" @close="showModalResult = false" @save="closeModalImport">
                        <h3 align="center" slot="header">Resultados del archivo procesado</h3>
                        <div slot="body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>RFC</th>
                                        <th>Estatus</th>
                                        <th>linea</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(user, index) in customer_users">
                                        <td>{{user.rfc}}</td>                   
                                        <td>{{user.status}}</td>
                                        <td>{{user.line+1}}</td>
                                    </tr>
                                </tbody>
                            </table>     
                        </div>    
                    </modal>
                    <!-- resultados de la carga masiva de usuarios -->  
                    <!-- Carga modal de archivo del usuario -->
                    <modal v-if="showModalImport" @close="showModalImport = false" @save="loading">
                        <h3 align="center" slot="header">Cargar archivo</h3>
                        <div slot="body">
                            <div v-show="type==''" class="form-group">
                                <label for="">Empresas</label>
                                <select class="form-control" v-model="cust">
                                    <option disabled value="">Seleccione una empresa</option>
                                    <option v-for="(customer, index) in customers" :value="customer.id" >{{customer.name}}</option>
                                </select>
                            </div>
                            <div >
                                <label class="btn btn-default btn-primary form-control">
                                   <b>Seleccione el archivo</b> <input class="form-control btn-xs" type="file" style="display: none;" @change="loadfile">
                                </label>                                
                            </div>
                        </div>
                    </modal>
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
</template>
<script>

// toastr
    import toastr from 'toastr';
// end toastr
    export default {

        data(){
            return{
                editing: false, // editar un empleado
                showModal: false, // crear un empleado
                showModalImport: false, // Muestra el modal de cargar
                showModalResult: false,// Muestra el modal con los resultados de la carga
                showModalGit: false,
                draft: {
                    customers: [],
                    banks: [],
                    rfc:'',
                    name:'',
                    nacionality: '',
                    email: '',
                    phone: '',
                    password: '',
                    biweekly_salary:'',
                    acconunt_number:'',
                    acconunt_clabe:'',
                    bank_id:'',
                    customer_id:'',
                },
                currentIndex: null,
                currentId: null,
                users: [],
                banks: [],
                file: null, // variable guarda el archivo
                customer_users: [],
                message: '',
                import: {
                    file:'',
                    customer_id:'',
                    type:'',
                },
                //file: null, // variable guarda el archivo  
                target: '',
                current: '',
                ready: false,
                last: '',
                type:'',
                cust:'',
                customer_search: '',
                customers:[],
            }
        },
        mounted() {
            this.getUsers(1);
        },
        methods:{
            search(){
                this.current = 1;
                this.getUsers(this.current);
            },
            go(page){
                this.getUsers(page);
            },
            next(){
                this.current +=1;
                this.getUsers(this.current);
            },
            prev(){
                this.current -= 1;
                this.getUsers(this.current);
            },
            getUsers(page){
                var self = this;
                axios.get('/api/v1/employee?customer_search=' + this.customer_search + '&page=' + page)
                    .then(response => {
                        console.log(response);
                        self.users = response.data.users.data;
                        self.banks = response.data.banks;
                        self.customers = response.data.customers;
                        self.current = response.data.users.current_page;
                        self.last = response.data.users.last_page;
                        self.ready = response.data.users.last_page > 1;
                        self.target = '';
                        self.type = response.data.type;
                    })
                    .catch(error => {
                         console.log(error);
                         toastr.error('Ha ocurrido un error');
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
            // cargando el archivo de empleados
            load(){
                this.showModalImport = true;
            },
            loading(event){
                var self = this;
                    this.showModalImport = false;
                    this.showModalGit = true;
                    this.import.customer_id = this.cust;
                    this.import.type = this.type;
                    
                    axios.post('/api/v1/employee/import', this.import)
                    .then(response => {                 
                    
                        self.message = response.data.message;
                        self.customer_users = response.data.customer_users;
                        this.showModalGit = false;
                        this.showModalResult = true;
                        console.log(response);
                        
                        if(response.data.status==1){
                           toastr.success(response.data.message);
                           this.getUsers();  
                        }else{
                            toastr.warning(response.data.message);
                        }                
                
                    })
                    .catch(error => {
                        console.log(error);
                        toastr.error('Ha ocurrido un error');
                    }) 
                    
            },

            loadfile(e){
               var fileReader = new FileReader();
               fileReader.readAsDataURL(e.target.files[0]); // Guardar el archivo por el metodo change

               fileReader.onload = (e)=>{
                   this.import.file = e.target.result;
               }
            },
            clean(){
                self = this;
                self.draft.rfc='';
                self.draft.name='';
                self.draft.nacionality= '';
                self.draft.email= '';
                self.draft.phone= '';
                self.draft.password= '';
                self.draft.biweekly_salary='';
                self.draft.acconunt_number='';
                self.draft.acconunt_clabe='';
                self.draft.bank_id='';
            },
            // end cargado el archivo de empleados
            store(){
                var self = this;
                axios.post('/api/v1/employee', this.draft)
                    .then(response => {                        
                        
                        if (response.data.status ==1) {
                            toastr.success(response.data.message);
                            self.getUsers(1);
                            self.clean();
                        }else{
                            toastr.warning(response.data.message);
                        }
                    })
                    .catch(error => {
                    console.log(error);
                    toastr.error('Ha ocurrido un error');
                    })                
            },
            update(){
                var self = this;
                axios.put('/api/v1/employee/' + this.currentId, this.draft)
                    .then( function(response){                        
                        self.users[self.currentIndex] = self.draft;
                        if (response.data.status ==1) {
                            toastr.success(response.data.message);
                            self.getUsers(1);
                            self.clean();
                        }else{
                            toastr.warning(response.data.message);
                        }
                    })
                    .catch(error => {
                        console.log(error);
                        toastr.error('Ha ocurrido un error');
                    })                  
            },
            edit(index){
                this.draft = Object.assign({}, this.users[index]);
                this.draft.phone = this.users[index].customer_user.phone;
                this.draft.acconunt_number = this.users[index].customer_user.acconunt_number;
                this.draft.acconunt_clabe = this.users[index].customer_user.acconunt_clabe;
                this.draft.biweekly_salary = this.users[index].customer_user.biweekly_salary;
                this.draft.bank_id = this.users[index].banks[0].id; 
                this.currentIndex = index;
                this.currentId = this.users[index].id;
                this.showModal = true;
                this.editing = true;
            },

            destroy(id){
               var self = this;
                axios.get('/api/v1/employee/delete?id=' + id)
                    .then(function(response) {
                        toastr.success(response.data.message);
                        self.getUsers();
                    })
                    .catch(error => {
                        console.log(error);
                        toastr.error('Ha ocurrido un error');
                    }) 
            },
            checkstatus: function(user) {
                if(user.status== 1){
                    return 'Activo';
                }
                return 'Inactivo';
            },
            closeModalImport(){
                this.showModalResult = false;
                this.getUsers(1);
            }
        },

        computed:{
          validForm(){
              return this.validRfc && this.validName &&  this.validNacionality && this.validPhone &&  this.validEmail && this.validPassowrd && this.validCustomer && this.validBiweekly && this.validAcconunt_number && this.validAcconunt_clabe;
          },

          validRfc(){
              return this.draft.rfc.length > 3;
          },
          validName(){
              return this.draft.name.length > 3;
          },

           validNacionality(){
              return this.draft.nacionality.length > 3;
          },

          validPhone(){
              return this.draft.phone.length > 3;
          },

          validEmail(){
              return this.draft.email.length > 3;
          },

          validPassowrd(){
              return this.draft.password.length > 3;
          },
        validBiweekly(){
              return this.draft.biweekly_salary.length > 3;
          },
          
            validAcconuntNumber(){
              return this.draft.acconunt_number.length == 11;
          },
            validAcconuntClabe(){
              return this.draft.acconunt_clabe.length == 18;
          },
      },
    }
</script>
