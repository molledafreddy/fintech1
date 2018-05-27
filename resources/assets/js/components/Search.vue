<template>
    <div class="panel panel-default">
        <div class="panel-body">
            <div>
                <div class="input-group">
                    <input v-model="target" type="text" class="form-control" placeholder="Buscar...">
                    <span class="input-group-btn">
				        <a class="btn btn-primary" role="button" @click.prevent="$emit('search', target)">
					    <i class="fa fa-search" aria-hidden="true"></i></a>
				    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default{
        data(){
            return{
                target: '',
            }
        },

        methods:{

        },
    }
</script>