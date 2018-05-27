<template>
    <div class="container">
        <div class="margen-components">
        <div class="row">
            <div class="panel panel-default" style="margin-right:20px;" >
                <div class="panel-heading">
                    <div class="pull-left">
                        <h3 style="padding-left:28px;">Usuarios</h3>
                        <div class="col-md-12">
                            <div class="form-group col-md-4" style="padding-right:2px; !important;">
                                <input class="form-control" type="text" v-model="target">
                            </div>
                            <div class="form-group col-md-6" style="padding-left:2px; padding-right:2px; !important;">
                                 <select class="form-control" v-model="customer_id">
                                    <option value="">Seleccione una opción</option>
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
                        <!-- <button class="btn btn-primary" @click="showModalImport = true"> <i class="fa fa-file" aria-hidden="true"></i> Carga de archivo</button>-->
                        <button class="btn btn-primary" @click="showModal = true"> <i class="fa fa-plus-square-o" aria-hidden="true"></i> Nuevo</button>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                         <table class="table table-striped table-hover datatable-asc">
                            <thead>
                                <tr>
                                    <th>RFC</th>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Teléfono</th>
                                     <th>Banco</th>
                                    <th>Empresa</th>
                                    <th>Estatus</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(user, index) in users">
                                    <td>{{user.rfc}}</td>                   
                                    <td>{{user.name}}</td>
                                    <td>{{user.email}}</td>
                                    <td>{{user.role}}</td>
                                    <td>{{ user.customer_user != null ? user.customer_user.phone : ''}}</td>
                                    <td>{{ user.banks.length > 0 ? user.banks[0].name : '' }}</td>
                                    <td>{{ user.customers.length > 0 ? user.customers[0].name : ''}}</td>
                                    <td v-text="checkstatus(user)"></td>
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
                        <h3 slot="header">Crear usuario</h3>
                        <div slot="body">
                            <div class="col-md-4">
                                 <div class="form-group" :class="! validRfc ? 'has-error' : ''">
                                    <label for="">RFC</label>
                                    <input type="text"  maxlength="13" class="form-control" v-model="draft.rfc">
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Roles</label>
                                    <select class="form-control" v-model="draft.role">
                                        <option disabled value="">Seleccione un rol</option>
                                        <option value="administrador">Administrador</option>
                                        <option value="empresa">Empresa</option>
                                        <option value="empleado">Empleado</option>
                                    </select>
                                </div>                                
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" :class="! validBiweekly ? 'has-error' : ''">
                                    <label for="">Salario quincenal</label>
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
                                <div class="form-group"  :class="! validAcconuntClabe ? 'has-error' : ''">
                                    <label for="">N° Clabe</label>
                                    <input type="text" class="form-control" v-model="draft.acconunt_clabe">
                                </div>                                
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Bancos</label>
                                    <select class="form-control" v-model="draft.bank_id">
                                        <option disabled value="">Seleccione un banco</option>
                                        <option v-for="(bank, index) in banks" :value="bank.id" >{{bank.name}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Empresas</label>
                                    <select class="form-control" v-model="draft.customer_id">
                                        <option class="form-control" disabled value="" >Seleccione una empresa</option>
                                        <option class="form-control" v-for="(customer, index) in customers" :value="customer.id">{{ customer.name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Estatus</label>
                                    <select class="form-control" v-model="draft.status">
                                        <option disabled value="">Seleccione un estatus</option>
                                        <option value="1">Activo</option>
                                        <option value="0">Inactivo</option>
                                    </select>
                                </div>
                            </div>
                        </div>    
                    </modal>
                    <!-- resultados de la carga masiva de usuarios -->
                    <modal v-if="showModalResult" @close="showModalResult = false">
                        <h3 slot="header">{{message}}</h3>
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
                        <h3 slot="header">Cargar archivo</h3>
                        <div slot="body">
                            <label class="btn btn-default btn-primary form-control">
                               <b>Seleccione el archivo</b> <input class="form-control btn-xs" type="file" style="display: none;" @change="loadfile">
                            </label>
                        </div>
                    </modal>
                    <!--  END Carga de archivo del usuario-->                       
                </div>
            </div>    
        </div>
    </div>
    </div>
</template>
<script>
    // toastr
    import toastr from 'toastr';
    export default {
        data(){
            return{
                editing: false, // editar un empleado
                showModal: false, // crear un empleado
                showModalImport: false, // Muestra el modal de cargar
                showModalResult: false,// Muestra el modal con los resultados de la carga
                draft: {
                    customers: [],
                    banks: [],
                },
                currentIndex: null,
                currentId: null,
                users: [],
                banks: [],
                customers: [],
                customer_users: [],
                message: '',
                import: {
                    file:''
                },
                //file: null, // variable guarda el archivo  
                target: '',
                current: '',
                ready: false,
                customer_id: '',  
                last: '',  
            }
        },
        mounted() {
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
                axios.get('/api/v1/user?search=' + this.target + '&page=' + page + '&customer_id=' + this.customer_id)
                    .then(response => {
                        console.log(response);
                        self.users = response.data.users.data;
                        self.banks = response.data.banks;
                        self.customers = response.data.customers;
                        self.current = response.data.users.current_page;
                        self.last = response.data.users.last_page;
                        self.ready = response.data.users.last_page > 1;
                        this.target = '';
                        this.customer_id = '';
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
            tranferOtherBanks(){
              //  alert("hola");
               
                var self = this;
                    axios.post('/api/v1/transfer/other-bank')
                    .then(response => {                 

                        self.message = response.data;
                        alert(self.message);
                        console.log(self.message);        
                    })
                    .catch(error => {
                       console.log(error);
                        toastr.error('Ha ocurrido un error');
                    })      
            },
            // end cargado el archivo de empleados
            store(){
                var self = this;
                axios.post('/api/v1/user', this.draft)
                    .then(response => {
                    console.log(response);                        
                        if(response.data.status==1){
                           toastr.success(response.data.message);
                           this.getUsers();
                           this.clean();  
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
                axios.put('/api/v1/user/' + this.currentId, this.draft)
                    .then( function(response){                        
                        self.users[self.currentIndex] = self.draft;
                        if(response.data.status==1){
                           toastr.success(response.data.message);
                           self.clean();  
                           self.getUsers();
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
                this.draft.customer_id = this.users[index].customers[0].id;
                this.draft.bank_id = this.users[index].banks[0].id; 
                this.currentIndex = index;
                this.currentId = this.users[index].id;
                this.showModal = true;
                this.editing = true;
            },

            destroy(id){
               var self = this;
                axios.get('/api/v1/user/delete?id=' + id)
                    .then(function(response) {
                        console.log(response);
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
        },
         computed:{
          validForm(){
              return this.validRfc && this.validName &&  this.validNacionality && this.validPhone &&  this.validEmail && this.validCustomer && this.validBiweekly && this.validAcconunt_number && this.validAcconunt_clabe;
          },

          validRfc(){
            if(this.draft.rfc && this.draft.rfc.length > 3){
                return true;
            }

            return false;
          },
          validName(){
             if(this.draft.name && this.draft.name.length > 3){
                return true;
            }
            return false;
          },

           validNacionality(){
               if(this.draft.nacionality && this.draft.nacionality.length > 3){
                
                return true;
            }
            return false;
          },

          validPhone(){

                if(this.draft.phone && this.draft.phone.length > 3 && !isNaN(this.draft.phone)) {
                    
                    return true;
                }
            return false;
          },

          validEmail(){
               if(this.draft.email && this.draft.email.length > 3){
                
                return true;
            }
            return false;
          },

        validBiweekly(){

            if(this.draft.biweekly_salary && this.draft.biweekly_salary > 3 && !isNaN(this.draft.biweekly_salary)){
                
                return true;
            }
            return false;
          },
          
            validAcconuntNumber(){
                console.log( isNaN(this.draft.acconunt_number));
                if(
                    this.draft.acconunt_number
                    && this.draft.acconunt_number.length == 11
                    && isNaN(this.draft.acconunt_number) == false
                ){
                    console.log("entre en el if");
                return true;
            }
                return false;
          },
            validAcconuntClabe(){

                if(this.draft.acconunt_clabe && this.draft.acconunt_clabe.length == 18 && !isNaN(this.draft.acconunt_clabe)){
                
                return true;
            }
                return false;
          },
      },
    }
</script>