<template>
    <div class="container">
        <div class="margen-components">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default" style="margin-top:40px">
                    <div class="panel-heading" style="padding-top: 5px; ">
                        <div align="center">
                            <h3><strong>Términos y condiciones del servicio</strong> </h3> 
                        </div>
                    </div>
                    <div class="panel-body">
                        <div slot="body">
                            <div class="col-md-12">
                                 <div class="form-group">
                                    <textarea maxlength="65000" class="form-control" rows="20">
                                        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad,
                                        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                                        cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                                        proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim,
                                        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                                        cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                                        proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim,
                                        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                        consequat.
                                    </textarea>
                                </div>
                                <div  style="padding-left:2px; padding-top: 17px;  !important;">
                                    <input type="checkbox" id="checkbox" v-model="checked">
                                    <label for="checkbox">Seleccione si acepta los terminos y condiciones</label>
                                </div>
                                <div class="col-md-2 " style="padding-left:2px; padding-top: 17px;  !important;">
                                      <button @click="update" class="btn btn-primary">Aceptó</button>
                                </div>
                            </div>                            
                        </div> 
                    </div>
                    <!-- modal que indica que se esta procesando la solicitud -->
                    <modal v-if="showModal" @close="showModal = false">
                        <h3 align="center" slot="header">Resultado</h3>
                        <div slot="body">
                            <div align="center">
                                <!-- <h3>El proceso se realizó con exíto</h3> -->
                                <h3>{{message}}</h3>
                            </div>
                        </div>
                         <div slot="footer">
                             <button class="modal-default-button btn btn-warning" @click="showModal = false" >
                                  <i class="fa fa-times-circle" aria-hidden="true"></i>
                                    Cerrar
                                </button>  
                         </div>    
                    </modal>
                    <!-- modal que indica que se esta procesando la solicitud -->
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
                file: {
                    idd:''
                },
                id:'',
                array:'',
                position:'',
                checked:'',
                showModal: false,
                message:'',


            }
        },
        methods:{

            update(){
                self = this;
                if (this.checked !=true) {
                    toastr.error('Para aceptar los terminos debe seleccionar el checkbox');
                }else{
                    this.array = location.href.split('/');
                    this.position = this.array.length;
                    this.id =  this.array[this.position-1];
                    axios.put('/api/v1/validate-terms/' + this.id)
                        .then( function(response){
                        console.log(response);
                        self.message = response.data.message;  
                        if (response.data.status==1) {
                                self.showModal=true;
                                toastr.success(response.data.message);
                            }else{
                                self.showModal=true;
                                toastr.error(response.data.message);
                            } 
                            
                        })
                        .catch(error => {
                        }) 
                }
            }
        
        }
    }
</script>
