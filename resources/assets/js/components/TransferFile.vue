<template>
    <div class="container">
        <div class="margen-components">
            <div class="panel panel-default"  style="margin-right:42px;">
                <div class="panel-heading">
                    <div class="pull-left" style="margin-top: 10px;">
                        <h3 style="padding-left:20px;">Solicitudes</h3>
                        <div class="col-md-12">
                            <div class="form-group col-md-10" style=" padding-left:2px; padding-right:2px; !important;">
                                 <select class="form-control" v-model="target">
                                    <option value="">Seleccione un Estatus</option>
                                    <option :value="0">Pendiente por procesar</option>
                                    <option :value="1">En proceso</option>
                                    <option :value="2">Con Error</option>
                                    <option :value="3">Lista</option>
                                </select>
                            </div>
                            <div class="col-md-2" style="padding-left:2px !important;">
                                  <button  @click="search" class="btn btn-primary"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="pull-right">
                         <button class="btn btn-primary" @click="showModalImport = true"> <i class="fa fa-file" aria-hidden="true"></i> Dar de alta transferencias</button>
                        <button class="btn btn-primary" @click="showModalTransferSantanderBank = true"> <i class="fa fa-plus-square-o" aria-hidden="true"></i> Archivo Banco Santander</button>
                        <button class="btn btn-primary" @click="showModalTransferOtherBank = true"> <i class="fa fa-plus-square-o" aria-hidden="true"></i> Archivo Otros Bancos</button>
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
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(credit, index) in credits">
                                <td>{{credit.order.customer_users.user != null ? credit.order.customer_users.user.name : ''}}</td>
                                <td>{{credit.order.customer_users != null ? credit.order.customer_users.acconunt_number : ''}}</td>
                                <td>{{credit.order.customer_users != null ? credit.order.customer_users.acconunt_clabe : ''}}</td>
                                <td>{{credit.order.customer_users.bank != null ? credit.order.customer_users.bank.name : ''}}</td>
                                <td>{{credit.order.customer_users.customer != null ? credit.order.customer_users.customer.name : ''}}</td>
                                <td>{{credit.status}}</td>  
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
                        <modal v-if="showModalTransferOtherBank" @close="showModalTransferOtherBank = false">
                            <h3 align="center" slot="header">Generar Archivo</h3>
                            <div slot="body">
                                <div class="col-md-12">
                                    <div v-if="fileNameOtherBank==''" class="col-md-8 col-md-offset-4 form-group">
                                          <button style="padding-left:10px; padding-rigth:10px; width: 120px;" @click="transferOtherBank" class="btn btn-primary">Generar</button>
                                    </div>
                                    <div class="col-md-6 form-group" v-else>    
                                        <ul class="list-group">
                                            <li style="padding-bottom: 8px;" v-for="(other, index) in fileNameOtherBank" class="">
                                              <a @click="deleteName(index)" style="padding-left:10px; padding-rigth:10px;" class="btn btn-primary " download :href="'storage/users/'+other.name">{{other.name}}</a>  
                                            </li>
                                        </ul>
                                    </div>
                                </div>                                    
                            </div>
                            <div slot="footer">
                                <button class="modal-default-button btn btn-warning" @click="voidNameFileOther"  >
                                  <i class="fa fa-times-circle" aria-hidden="true"></i>
                                    Atrás
                                </button>                            
                            </div>   
                        </modal>
                        <!--  END Generar archivo para dar de alta-->
                         <!-- Carga modal de archivo del usuario -->
                        <modal v-if="showModalTransferSantanderBank" @close="showModalTransferSantanderBank = false">
                            <h3 align="center" slot="header">Generar Archivo</h3>
                            <div slot="body">
                                <div class="col-md-12">
                                    <div v-if="fileNameSantanderBank==''" class="col-md-8 col-md-offset-4 form-group">
                                          <button style="padding-left:10px; padding-rigth:10px; width: 120px;" @click="transferSantanderBank" class="btn btn-primary">Generar</button>
                                    </div>
                                    <div class="col-md-6 form-group" v-else>    
                                        <ul class="list-group">
                                            <li style="padding-bottom: 8px;" v-for="(santander, index) in fileNameSantanderBank" class="">
                                              <a @click="deleteName(index)" style="padding-left:10px; padding-rigth:10px;" class="btn btn-primary " download :href="'storage/users/'+santander.name">{{santander.name}}</a>  
                                            </li>
                                        </ul>
                                    </div>
                                </div>                                      
                            </div>
                            <div slot="footer">

                                <button class="modal-default-button btn btn-warning" @click="voidNameFileSantander" >
                                  <i class="fa fa-times-circle" aria-hidden="true"></i>
                                    Atrás
                                </button>                              
                            </div>   
                        </modal>
                        <!--  END Generar archivo para dar de alta-->
                        <!-- Carga modal de archivo log del banco -->
                        <modal v-if="showModalImport" @close="showModalImport = false" @save="loading">
                            <h3 slot="header">Cargar archivo</h3>
                            <div slot="body">
                                <label class="btn btn-default btn-primary form-control">
                                   <b>Seleccione el archivo</b> <input class="form-control btn-xs" type="file" style="display: none;" @change="loadfile">
                                </label>
                            </div>
                        </modal>
                    <!--  END Carga de archivo del usuario-->
                    <!-- resultados del proceso de transferencias -->
                    <modal v-if="showModalResult" @close="showModalResult = false" @save="closeModalImport">
                        <h3 align="center" slot="header">Resultados del archivo procesado</h3>
                        <div slot="body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Numero de cuenta</th>
                                        <th>Estatus de Transferencia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(credit, index) in container_credits">
                                        <td>{{credit.account_number}}</td>                   
                                        <td>{{credit.account_status}}</td>
                                        <template v-if="credit.status != false">
                                            
                                            <td><span class="label label-success" value="disable"><i class="fa fa-check" aria-hidden="true"></i></span></td>                                            
                                        </template>
                                        <template v-else>
                                            <td><span class="label label-danger" value="disable"><i class="fa fa-times" aria-hidden="true"></i></span></td>
                                        </template>
                                    </tr>
                                </tbody>
                            </table>     
                        </div>
                        <div slot="footer">
                            <button class="modal-default-button btn btn-warning" @click="showModalResult= false" >
                              <i class="fa fa-times-circle" aria-hidden="true"></i>
                                Atrás
                            </button>                            
                        </div>    
                    </modal>
                    <!-- resultados de la carga masiva de usuarios -->
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
                    loadFileName:''
                },
                showModalTransferOtherBank: false,
                showModalTransferSantanderBank: false,
                showModalResult:false,
                showModalGit:false,
                showModalImport: false,
                showModal: false, // crear un empleado
                currentIndex: null,
                currentId: null,
                credits: [],
                container_credits:[],
                target: '',
                current: '',
                ready: false,
                customer_id: '',
                status:'',  
                last: '',
                fileNameOtherBank:[],
                fileNameSantanderBank:[],
                message:'', 
                arr:'',               
            }
        },
        created() {
            this.getCredits(1);
        },

        computed:{
            
        },

        methods:{
            search(target){
                this.current = 1;
                this.getCredits(this.current);
            },
            go(page){
                this.getCredits(page);
            },
            next(){
                this.current += 1;
                this.getCredits(this.current);
            },
            prev(){
                this.current -= 1;
                this.getCredits(this.current);
            },

            deleteName: function(index){
                this.fileNameSantanderBank.splice(index, 1);
                this.fileNameOtherBank.splice(index, 1);
            },
            getCredits(page){
                var self = this;
                
                axios.get('/api/v1/credit?search=' + this.target + '&page=' + page)
                    .then(response => {
                        // console.log(response);
                        self.credits = response.data.credits.data;
                        self.current = response.data.credits.current_page;
                        self.last = response.data.credits.last_page;
                        self.ready = response.data.credits.last_page > 1;
                        this.target = '';
                    })
                    .catch(error => {
                         console.log(error);
                    })
            },
            transferOtherBank(event){
                var self = this;
                this.showModalGit = true;
                this.showModalTransferOtherBank = false;
                
                axios.post('/api/v1/transfer/other-bank')
                .then(response => {                 
                    self.message = response.data.message;
                    self.fileNameOtherBank = response.data.fileNameOtherBank;
                    self.showModalGit = false;
                    self.showModalTransferOtherBank = true;

                    if (response.data.status==1) {
                        self.getCredits();
                        toastr.success(response.data.message);
                    }else{
                        toastr.error(response.data.message);
                    }
                    if (response.data.contenedor>0) {
                        toastr.warning('Quedaron solicitudes pendiente devido a que superan el monto maximo disponible por la cuentas de fintech');
                    } 
                })
                .catch(error => {
                    //console.log(error);
                    toastr.error(error); 
                })
            },
            voidNameFileSantander(){
                self = this;
                if ( self.fileNameSantanderBank.length > 0) {
                    toastr.warning('Debes descargar todos los archivos');                    
                }else{
                    self.showModalTransferSantanderBank = false;
                    self.fileNameSantanderBank= [];
                }
            },
            voidNameFileOther(){
                self = this;
                if ( self.fileNameOtherBank.length > 0) {
                    toastr.warning('Debes descargar todos los archivos');                    
                }else{
                    self.fileNameOtherBank=[];
                    self.showModalTransferOtherBank = false;
                }

            },
            transferSantanderBank(event){
                var self = this;
                    this.showModalGit = true;
                    this.showModalTransferSantanderBank = false;
                axios.post('/api/v1/transfer/santander-bank')
                .then(response => {                 
                    self.message = response.data.message;
                    self.showModalGit = false;
                    this.showModalTransferSantanderBank = true;
                    self.fileNameSantanderBank = response.data.fileNameSantanderBank;
                    console.log(response);
                    if (response.data.status==1) {
                        self.getCredits();
                        toastr.success(response.data.message);
                    }else{
                        toastr.error(response.data.message);
                    }
                    if (response.data.contenedor>0) {
                        toastr.warning('Quedaron solicitudes pendiente devido a que superan el monto maximo disponible por la cuentas de fintech');
                    } 
                })
                .catch(error => {
                    toastr.error(error); 
                })
            },
             generate(event){
                var self = this;
                if (self.fileGenerate.type != '') {

                    axios.post('/api/v1/file-validate-account', this.file)
                    .then(response => {                 
                        self.fileName = response.data.fileName;
                        
                        if (response.data.status == true) {
                            toastr.success(response.data.message);
                        }else{
                            toastr.error(response.data.message);
                        } 
                    })
                    .catch(error => {
                        toastr.error(error); 
                    })   
                }else{
                    toastr.warning("Debe seleccionar el tipo de archivo a generar");
                }
                   
            },
            // cargando el archivo de empleados
            load(){
                this.showModalImport = true;
                this.showModalFile = true;
            },
            loading(event){
                
                var self = this;
                    self.showModalImport = false;
                    self.showModalGit = true;
                    axios.post('/api/v1/file/transfer-import', this.import)
                    .then(response => {
                    console.log(response);                
                    self.container_credits = response.data.credits;
                    self.message = response.data.message; 
                    
                    if(response.data.status==false){
                       self.showModalGit = false;
                       self.showModalImport = true;
                       toastr.error(response.data.message);  
                    
                    }else{
                        self.showModalGit = false;    
                        self.showModalResult = true;
                        self.getCredits(1);
                        toastr.success(response.data.message);
                    }

                    })
                    .catch(error => {
                        console.log(error);
                    })      
            },
            closeModalImport(){
                this.showModalResult = false;
                this.getCredits(1);
            },
            
            loadfile(e){
                    var fileReader = new FileReader();
               fileReader.readAsDataURL(e.target.files[0]); // Guardar el archivo por el metodo change
                this.import.loadFileName = e.target.files[0].name;
               
               fileReader.onload = (e)=>{
                   this.import.file  = e.target.result;
               }
                    
            },
            
        
        }
    }
</script>
