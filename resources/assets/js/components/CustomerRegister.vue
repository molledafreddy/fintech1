<template>
    <div class="container">
        <div class="margen-components">
            <div class="">
                <div class="panel panel-default ">
                    <div class="panel-heading">
                        <div class="text-center">
                            <h3>Registro de Empresas</h3>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body  " >
                        <div class="row">
                            <div class="col-md-12 col-lg-12">
                                <div class="form-group col-md-4" :class="! validName ? 'has-error' : ''">
                                    <label for="">Nombre</label>
                                    <input type="text" class="form-control" v-model="draft.name">
                                </div>

                                <div class="form-group col-md-4" :class="! validRfc ? 'has-error' : ''">
                                    <label for="">RFC</label>
                                    <input type="text" class="form-control" v-model="draft.rfc">
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
                                <div class="form-group col-md-6" :class="! validAddress ? 'has-error' : ''">
                                    <label for="">Dirección</label>
                                    <textarea class="form-control" v-model="draft.address"></textarea>
                                </div>
                                <div class="form-group col-md-6" :class="! validComments ? 'has-error' : ''">
                                    <label for="">Comentario</label>
                                    <textarea class="form-control" v-model="draft.comments"></textarea>
                                </div>
                                <div class="form-group col-md-6" :class="! validCPfirst ? 'has-error' : ''">
                                    <label for="">Nombre de contacto</label>
                                    <input type="text" class="form-control" v-model="draft.cp_first_name">
                                </div>
                                <hr style="width: 89px;">
                                <div class="form-group col-md-6" :class="! validCPlast ? 'has-error' : ''">
                                    <label for="">Apellido de contacto</label>
                                    <input type="text" class="form-control" v-model="draft.cp_last_name">
                                </div>
                                <div class="form-group col-md-5":class="! validCPphone ? 'has-error' : ''">
                                    <label for="">Telefono de contacto</label>
                                    <input type="text" class="form-control" v-model="draft.cp_phone">
                                </div>
                                <div class="form-group col-md-5" :class="! validCPemail ? 'has-error' : ''">
                                    <label for="">Email de contacto</label>
                                    <input type="email" class="form-control" v-model="draft.cp_email">
                                </div>
                                <div class="form-group col-md-2" style="padding-left:6px; padding-top:28px; !important;">
                                    <input type="hidden" name="register" v-model="draft.register">
                                  <button  @click="store" class="btn btn-primary">Registrar <i class="fa fa-save"></i></button>
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
                
            }
        },
        mounted() {

        },
        methods:{
            store(){
                var self = this;
                axios.post('/register/customer',this.draft)
                    .then(response => {                        
                        toastr.success(response.data.message);
                        self.draft.address='';
                        self.draft.name='';
                        self.draft.rfc='';
                        self.draft.address='';
                        self.draft.phone='';
                        self.draft.web_site='';
                        self.draft.city='';
                        self.draft.cp_first_name='';
                        self.draft.cp_last_name='';
                        self.draft.cp_phone='';
                        self.draft.cp_email='';
                        self.draft.comments='';
                    })
                    .catch(error => {
                        toastr.error('Ha ocurrido un error');
                    })                
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
