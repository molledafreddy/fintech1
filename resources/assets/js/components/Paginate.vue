<template>
    <div class="pagination pagination-small text-center">
        <nav aria-label="Page navigation">
            <ul class="pagination">


                <li :class="current == 1 ? 'disabled' : '' ">
                    <a href="#" aria-label="Anterior" @click.prevent="$emit('prev'); setPages(0)">
                        <i class="fa fa-backward" aria-hidden="true"></i>
                    </a>
                </li>

                <li v-for="page in pages" @click.prevent="$emit('go', page); setPages(0)" :class="page == current ? 'active' : ''"><a href="#">{{page}}</a></li>

                <li  :class="last == current ? 'disabled' : '' ">
                    <a href="##" aria-label="Próxima" @click.prevent="$emit('next'); setPages(0)">
                        <i class="fa fa-forward" aria-hidden="true"></i>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</template>

<script>
    export default {
        props:[
            'current',
            'last',

        ],
        data(){
            return{
                pages: [],
            }
        },
        mounted() {
            console.log('Pagination mounted.');
            this.setPages(1);
        },

        methods:{
            setPages(sw){
                this.pages = [];

                var size = 5
                var i = 1;
                var vEndPages = size;

                if (this.last > size && this.current > 2)
                {
                    i = this.current - 2;
                    vEndPages = this.current + 2
                }

                if (vEndPages > this.last)
                {
                    i = this.last - (size-1);
                    vEndPages = this.last;
                }

                if (i < 1)
                {
                    i = 1;
                }

                for (i; i <= vEndPages; i++)
                {
                    this.pages.push(i);
                }

            },

            page(n){
                return n - 1 + this.current;
            }
        }
    }
</script>