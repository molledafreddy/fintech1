<template>
    <div class="container">
        <div class="margen-components">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h3>Plaza Banxico</h3>
                            <search @search="search"></search>
                        </div>
                        <div class="pull-right">
                            <button class="btn btn-primary" @click="showModal = true">Nuevo</button>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Numero</th>
                                    <th>Ciudad</th>
                                    <th>Estatus</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(banxico, index) in banxicos">
                                    <td><a href="#" @click="show(banxico.id)">{{banxico.number}}</a></td>
                                    <td>{{banxico.city}}</td>
                                    <td>
                                        
                                        <template v-if="banxico.status=='active'">
                                            <span  class="label label-success" value="available">Activa</span>
                                        </template>
                                        <template v-else>
                                            <span class="label label-warning" value="disable">inactiva</span>
                                        </template>
                                    </td>
                                    <td>
                                        <button class="btn btn-info btn-xs" @click="edit(index)">
                                             <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </button>
                                    </td>                                    
                                </tr>
                            </tbody>
                        </table>
                        <modal v-if="showModal" @close="showModal = false" @save="save">
                            <h3 slot="header">Crear Banxico</h3>
                            <div slot="body">
                                <div class="form-group" :class="! validNumber ? 'has-error' : ''">
                                    <label for="">Número</label>
                                    <input type="text" name="number" class="form-control" v-model="draft.number">
                                </div>
                                <div class="form-group" :class="! validCity ? 'has-error' : ''">
                                    <label for="">Ciudad</label>
                                    <input type="text" name="city" class="form-control" v-model="draft.city">
                                </div>
                                <div class="form-group" >
                                    <label for="">Estatus</label>
                                    <select class="form-control" v-model="draft.status">
                                        <option disabled value="">Seleccione un estatus</option>
                                        <option value="active">Activo</option>
                                        <option value="inactive">Inactivo</option>
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
                draft: {
                    number:'',
                    city: '',
                    status: '',
                },
                banxicos: [],
                currentIndex: null,
                currentId: null,
                current: null,
                ready: false,
                last: '',
                target: '',
                currentIndex: null,
                currentId: null,
                banxicos: [],
                s1:'active',
                s2:'inactive',

            }
        },
        mounted() {
            this.getBanxicos();
        },
        methods:{
            search(target){
                this.target = target;
                this.current = 1;
                this.getBanxicos(this.current);
            },
            go(page){
                console.log(page);
               this.getBanxicos(page);
           },
           next(){
               this.current +=1;
               this.getBanxicos(this.current);
           },
           prev(){
               this.current -= 1;
               this.getBanxicos(this.current);
           },
            getBanxicos(page){
                var self = this;
                axios.get('/api/v1/banxico?search=' + this.target + '&page=' + page)
                    .then(function(response) {
                        self.banxicos = response.data.data;
                        self.current = response.data.current_page;
                        self.last = response.data.last_page;
                        self.ready = response.data.last_page > 1;
                    })
                    .catch(error => {
                        alert(error.data.message);
                        toastr.error(error);
                    })
            },
            validateStatus(status){
                var self = this;

                if (status == true) {
                    return true;
                }else{
                    return false;
                }
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
                alert(self.draft.status);
                axios.post('/api/v1/banxico', self.draft)
                    .then(response => { 
                        self.banxicos.push(self.draft);
                        if (response.data.status ==1) {
                            toastr.success(response.data.message);
                            self.draft.number = '';
                            self.draft.city = '';
                            self.getBanxicos();
                        }else{
                            toastr.warning(response.data.message);
                        }
                    })
                    .catch(error => {
                        toastr.error(error);
                    })                
            },
            update(){
                var self = this;
                axios.put('/api/v1/banxico/' + this.currentId, this.draft)
                    .then( function(response){
                        self.getBanxicos();                        
                        self.banxicos[self.currentIndex] = self.draft;
                        if (response.data.status ==1) {
                            toastr.success(response.data.message);
                            self.draft.number = '';
                            self.draft.city = '';
                            self.getBanxicos();
                        }else{
                            toastr.warning(response.data.message);
                        }                        
                    })
                    .catch(error => {
                        //alert(error.data.message);
                        self.getbanks();
                    })
                    .catch(error => {
                        alert(error.data.message);
                    })                  
            },
            edit(index){
                this.draft = Object.assign({}, this.banxicos[index]);
                this.currentIndex = index;
                this.currentId = this.banxicos[index].id;
                this.draft.status = this.banxicos[index].status;
                this.currentIndex = index;
                this.currentId = this.banxicos[index].id;
                this.showModal = true;
                this.editing = true;
            },
            destroy(id){
               var self = this;
                axios.get('/api/v1/banxico/delete?id=' + id)
                    .then(function(response) {
                        console.log(response);
                        self.getBanxicos();
                        
                    })
                    .catch(error => {
                        //alert(error.data.message);
                        toastr.error(error);
                    }) 
            },
        },
        computed:{
            validForm(){
              return this.validNumber && this.validCity
              ;
          },

            validNumber(){
              return this.draft.number.length > 3;
          },
           validCity(){
              return this.draft.city.length > 3;
          },
        }
    }
</script>
