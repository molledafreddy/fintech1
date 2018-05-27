<template>
    <div class="container">
        <div class="margen-components">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h3>Empresas</h3>
                         <div class="col-md-12">
                            <div class="form-group col-md-4" style="padding-right:2px; !important;">
                                <input class="form-control" type="text" v-model="target">
                            </div>
                            <div  class="form-group col-md-6" style="padding-left:2px; padding-right:2px; !important;">
                                 <select class="form-control" v-model="status">
                                    <option value="">Seleccione estatus</option>
                                    <option value="pendiente por verificar"> Pendiente por verificar</option>
                                    <option value="verificado">Verificado</option>
                                    <option value="inactivo">Inactivo</option>
                                </select>
                            </div>
                            <div class="col-md-2" style="padding-left:2px !important;">
                                  <button  @click="search" class="btn btn-primary"><i class="fa fa-search"></i></button>
                            </div>
                        </div>  
                        </div>
                        <div class="pull-right">
                            <button class="btn btn-primary" @click="showModal = true">
                                <i class="fa fa-plus-square-o" aria-hidden="true"></i> Nuevo
                            </button>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="panel-body">
                         <div class="table-responsive">
                            <table class="table table-striped table-hover datatable-asc">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>N° RFC</th>
                                        <th>Dirección</th>
                                        <th>Telefono</th>
                                        <th>Sitio Web</th>
                                        <th>Ciudad</th>
                                        <!--<th>Nombre de contacto</th>
                                        <th>Apellido de contacto</th>
                                        <th>Telefono de contacto</th>
                                        <th>Correo de contacto</th>-->
                                        <th>Estatus</th>
                                        <th>Acciones</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(customer, index) in customers">
                                        <td>{{customer.name}}</td>
                                        <td>{{customer.rfc}}</td>
                                        <td>{{customer.address}}</td>
                                        <td>{{customer.phone}}</td>
                                        <td>{{customer.web_site}}</td>
                                        <td>{{customer.city}}</td>
                                        <!--<td>{{customer.cp_first_name}}</td>
                                        <td>{{customer.cp_last_name}}</td>
                                        <td>{{customer.cp_phone}}</td>
                                        <td>{{customer.cp_email}}</td>-->
                                        <td 
                                        v-text="checkstatus(customer)"></td>
                                        <td>
                                            <button class="btn btn-primary btn-xs" @click="edit(index)">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </button>
                                            <button class="btn btn-danger btn-xs" @click="destroy(customer.id)">
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
                            <h3 slot="header">Crear empresa</h3>
                            <div slot="body">
                                <div class="form-group col-md-4" :class="! validName ? 'has-error' : ''">
                                    <label for="">Nombre</label>
                                    <input type="text" class="form-control" v-model="draft.name">
                                </div>

                                <div class="form-group col-md-4" :class="! validRfc ? 'has-error' : ''">
                                    <label for="">RFC</label>
                                    <input type="text" class="form-control" v-model="draft.rfc">
                                </div>

                                <div class="form-group col-md-4" :class="! validAddress ? 'has-error' : ''">
                                    <label for="">Dirección</label>
                                    <textarea class="form-control" v-model="draft.address"></textarea>
                                </div>

                                <div class="form-group col-md-4" :class="! validPhone ? 'has-error' : ''">
                                    <label for="">Telefono</label>
                                    <input type="text" class="form-control" v-model="draft.phone">
                                </div>

                                 <div class="form-group col-md-4" :class="! validWeb ? 'has-error' : ''">
                                    <label for="">Sitio Web</label>
                                    <input type="text"class="form-control" v-model="draft.web_site">
                                </div>
                                <div class="form-group col-md-4" :class="! validCity ? 'has-error' : ''">
                                    <label for="">Ciudad</label>
                                    <input type="text" class="form-control" v-model="draft.city">
                                </div>
                                <div class="form-group col-md-6" :class="! validCPfirst ? 'has-error' : ''">
                                    <label for="">Nombre de contacto</label>
                                    <input type="text" class="form-control" v-model="draft.cp_first_name">
                                </div>

                                <div class="form-group col-md-6" :class="! validCPlast ? 'has-error' : ''">
                                    <label for="">Apellido de contacto</label>
                                    <input type="text" class="form-control" v-model="draft.cp_last_name">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="">Telefono de contacto</label>
                                    <input type="text" class="form-control" v-model="draft.cp_phone">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="">Email de contacto</label>
                                    <input type="text" class="form-control" v-model="draft.cp_email">
                                </div>

                                <!--
                                <div v-if="editing && users.length > 0" class="form-group col-md-6">
                                    <label for="">¿Seleccione un administrador</label>
                                    <select class="form-control" v-model="draft.admin_id">
                                        <option class="form-control" disabled value="" >Seleccione un administrador
                                        </option>
                                        <option class="form-control" v-for="(user, index) in users" :value="user.id">{{ user.name }}</option>
                                    </select>
                                </div> 
                                -->
                                <div v-if="editing" class="col-md-4">
                                <div class="form-group">
                                    <label for="">Estatus</label>
                                    <select class="form-control" v-model="draft.status">
                                        <option disabled value="">Seleccione un estatus</option>
                                        <option value="pendiente por verificar"> Pendiene por verificar</option>
                                        <option value="verificado">Verificado</option>
                                         <option value="inactivo">Inactivo</option>
                                    </select>
                                </div>
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
                draft: {
                    name:'',
                    rfc:'',
                    address:'',
                    phone:'',
                    web_site:'',
                    city:'',
                    cp_first_name:'',
                    cp_last_name:'',
                    cp_phone:'',
                    cp_email:'',
                    comments:'',
                    register:'link',
                },
                currentIndex: null,
                currentId: null,
                customers: [],
                users: [],
                target: '',
                current: '',
                ready: false,
                last: '',  
                status: '',
                hola: null,
            }
        },
        mounted() {
            this.getCustomers(1);
        },
        methods:{
            search(){
                this.current = 1;
                this.getCustomers(this.current);
                this.target = '';
                this.status = '';
            },
            go(page){
                this.getCustomers(page);
            },
            next(){
                this.current += 1;
                this.getCustomers(this.current);
            },
            prev(){
                this.current -= 1;
                this.getCustomers(this.current);
            },
            getCustomers(page){
                var self = this;
                axios.get('/api/v1/customer?search=' + this.target + '&page=' + page + '&status='+this.status)
                    .then(function(response) {
                        console.log(response);
                        self.customers = response.data.data;
                        self.current = response.data.current_page;
                        self.last = response.data.last_page;
                        self.ready = response.data.last_page > 1;
                    })
                    .catch(function (error) {
                    console.log(error);
                    toastr.error('Ha ocurrido un error');
                });
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
                axios.post('/api/v1/customer', this.draft)
                    .then(response => {                        
                        self.customers.push(self.draft);
                        console.log(response);
                        if (response.data.status ==1) {
                            toastr.success(response.data.message);
                            self.getCustomers();
                        }else{
                            toastr.warning(response.data.message);
                        }
                    })
                    .catch(error => {
                        //alert(error.data.message);
                         console.log(error);
                        toastr.error('Ha ocurrido un error');
                    })                
            },
            edit(index){
                self = this;
                this.draft = Object.assign({}, this.customers[index]);
                // axios.get('/api/v1/customer/edit?id=' + (index + 1))
                //     .then( function(response) {                        
                //         self.users = response.data;
                //         console.log(self.users);
                //     })
                //     .catch(function (error) {
                //         //console.log(error);
                //         // toastr.error(error);
                //     });  
                this.currentIndex = index;
                this.currentId = this.customers[index].id;
                this.showModal = true;
                this.editing = true;
                this.hola = 'selected';
            },
            update(){
                var self = this;
                axios.put('/api/v1/customer/' + this.currentId, this.draft)
                    .then( function(response){                        
                        self.customers[self.currentIndex] = self.draft;
                        if (response.data.status ==1) {
                            toastr.success(response.data.message);
                            self.getCustomers();
                        }else{
                            toastr.warning(response.data.message);
                        }
                    })
                    .catch(error => {
                        //alert(error.data.message);
                        toastr.error('ha ocurrido un error');
                        console.log(error);
                    })                  
            },
            destroy(id){
               var self = this;
                axios.get('/api/v1/customer/delete?id=' + id)
                    .then(function(response) {
                        toastr.success(response.data.message);
                        self.getCustomers();
                    })
                    .catch(error => {
                        toastr.error('ha ocurrido un error');
                    }) 
            },
            checkstatus: function(customer) {
                if(customer.status== 'pendiente por verificar'){
                    return 'pendiente por verificar';
                }else{
                    if(customer.status == 'verificado'){
                        return 'verificado';
                    }
                }
                return 'Inactivo';
            },
            checkSelected: function(draft) {
                console.log("antes del if");
                if(draft.status == 1){
                    console.log("entre al if");
                    return this.hola = 'selected';
                }
                return  null;
            },
        },
        computed:{
            validForm(){
                return  this.validRfc &&  this.validName &&  this.validWed &&  this.validCity &&  this.ValidPhone 
                    &&  this.validAddress &&  this.validCPfirst &&  this.validCPlast &&  this.validCPphone &&  this.validCPemail &&  this.validComments;
            },
            validRfc(){
                  return this.draft.rfc.length > 3;
            },
            validName(){
                  return this.draft.name.length > 3;
            },
            validWeb(){

              return this.draft.web_site.length > 6;
            },          
            validCity(){
               return this.draft.city.length > 3;
            },
            validPhone(){
              return this.draft.phone.length > 3;
            },
            validAddress(){
              return this.draft.address.length > 8;
            },
            validCPfirst(){
              return this.draft.cp_first_name.length > 3;
            },
            validCPlast(){
              return this.draft.cp_last_name.length > 3;
            },
            validCPphone(){
              return this.draft.cp_phone.length > 3;
            },
            validCPemail(){
              return this.draft.cp_email.length > 3;
            },
            validComments(){
              return this.draft.comments.length > 3;
            },
        },
    }
</script>
