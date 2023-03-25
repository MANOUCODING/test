<template>
   
    <div class="row mt-3" v-if="loading">
        <div style="position: relative">
            <div class="lds-ellipsis" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%);"><div></div><div></div><div></div><div></div></div>
            <br/><br/><br/><br/><br/><br/>
        </div>
    </div>
    <div class="row" v-else>
        <h5 class="mb-2 text-white">Les Tags</h5>
        <ul class="list-inline text-primary-hover lh-lg">
            <li class="list-inline-item" v-for="info in tags" :key="info.id" ><span @click="tag(info.slug)" style="cursor: pointer"> #{{ info.name }} </span></li>
        </ul>
    </div>
</template>
<script>
export default {
    
    data(){
        return {
            tags: {},
            loading: true,
        }
    },
    methods: {

        getResults(){
            this.axios.get('/api/home/footer/tags' )
                .then(response => {
                
                if(response.data.data.status == 200){

                    this.loading = false
                    
                    this.tags = response.data.data.tags
    
                }
            });
        },

        tag(slug){
            window.location = '/tags/' + slug
        }

    },
    mounted() {
        
        this.getResults();
    }
}
</script>