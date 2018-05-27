<template>
    <div class="container">
        <div class="margen-components">
            <div class="">
                <div class="panel panel-default ">
                    <div class="panel-heading">
                        <div class="text-center">
                            <h3>Formulario de contacto</h3>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body  " >
                        <h5 class="h5-font col-md-8 col-sm-4 col-lg-9 col-md-offset-1 col-lg-offset-2 col-sm-offset-0">
                            Si quieres una atención mas directa puedes comincarte con nosotros a traves de los siguientes numeros:                                        
                        </h5>
                        <div class="row">
                            <div class=" col-md-12 col-lg-12 col-md-offset-1 col-lg-offset-2 col-sm-offset-0 ">
                                <div class="input-group ">
                                    <div class=" margin-contact form-group col-sm-12 col-md-12 col-lg-12 " :class="! validSubject ? 'has-error' : ''">
                                        <label for="alias" class="control-label">Asunto <span class="text-danger">*</span></label>
                                        <input  type="text" class="form-control" v-model="draft.subject" placeholder="Escribe el asunto..." required="required">
                                    </div>
                                    <div  class=" margin-contact form-group col-sm-12 col-md-12 col-lg-12 " :class="! validContent ? 'has-error' : ''" >
                                        <label for="alias" class="control-label">Contenido <span class="text-danger">*</span></label>
                                        <textarea maxlength="500" class="form-control" rows="6" v-model="draft.content" placeholder="Escribe el contenido..."></textarea>
                                    </div>
                                    <div class=" margin-contact form-group pull-right" style="margin-right:17px;">
                                        <button class="btn-clean btn-sm">
                                            <i class="fa fa-history"  @click="cleanFill"> Limpiar</i>
                                        </button>
                                        <button class="btn btn-info btn-sm" @click="sendMessage" >
                                            <i class="fa fa-hourglass-end" aria-hidden="true"> Enviar</i>
                                        </button>
                                    </div>
                                </div>
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
                draft: {
                    subject:'',
                    content:'',                    
                },
                prueba:1,

            }
        },
        mounted() {

        },
        methods:{
            sendMessage: function( ){
                var mv = this; 

                if((mv.draft.subject!='') && (mv.draft.content!='')){
                
                     axios.post('/api/v1/contact', mv.draft  
                    ).then(response => {
                        
                        if (response.data.status==1) {                            
                            mv.draft.subject='';
                            mv.draft.content='';
                            toastr.success(response.data.message);
                        
                        }else{                        
                            toastr.warning(response.data.message);                        
                        }
                    }).catch(error => {
                        console.log(error);
                    });
                }else{
                    toastr.warning('Para enviar el mensaje, debe colocar el asunto y el contenido del mismo');
                }
            },

            cleanFill: function(){
                var mv = this;                
                mv.draft.subject='';
                mv.draft.content='';
            }
        },

        computed:{
          validForm(){
              return  this.validSubject &&  this.validContent ;
          },
          
          validSubject(){

              return this.draft.    subject.length > 6;
          },

          
            validContent(){
              return this.draft.content.length > 10;
          },
      },

    }
</script>
