<template>
    
    <form class="row row-cols-lg-auto g-2 align-items-center justify-content-end" @submit.prevent="store">
            
        <div class="col-12" v-if="errors.email">
            <input required type="email" name="email" v-model="data.email" class="form-control is-invalid" placeholder="Entrez votre email " />
            <div v-for="errorName in errors.email" :key="errorName" class="invalid-feedback">
                {{ errorName }}
            </div>
        </div>
        <div class="col-12" v-else>
            <input required type="email" name="email" v-model="data.email" class="form-control " placeholder="Entrez votre email " />
        </div>
        <div class="col-12">
            <button type="submit"  class="btn btn-danger m-0" v-if="!loading">S' abonner</button>
            <button type="button"  class="btn btn-danger m-0" v-else> 
                <i  style="color: #fff" class="fa fa-spinner fa-1x fa-spin fa-fw"></i>
                <span class="sr-only">Loading...</span>
            </button>
        </div>
        <div class="form-text mt-2 text-white">
            Etre avertis par mail de nos prochains articles
        </div>
    </form>
    
</template>

<script>
export default{
    data() {
        return {
            data: {
                email: null,
            },
            errors: {},
            loading: false,
        }
    },
    methods: {
        store(){
            this.loading = true
           
            axios.post('/api/home/newsletter/store',{
                email : this.data.email,
            })
            .then(response =>{
                
                    if (response.data.data.status == 401) {
                        this.loading = false
                        this.errors = response.data.data.errors
                        const Toast = this.$swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 4000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', this.$swal.stopTimer)
                                    toast.addEventListener('mouseleave', this.$swal.resumeTimer)
                                }
                            })

                            Toast.fire({
                                icon: 'error',
                                title: response.data.message
                            })
                    }else{

                        if (response.data.data.status == 200) {
                            
                            this.loading = false
                            
                            const Toast = this.$swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 4000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', this.$swal.stopTimer)
                                    toast.addEventListener('mouseleave', this.$swal.resumeTimer)
                                }
                            })

                            Toast.fire({
                                icon: 'success',
                                title: response.data.message
                            })

                            this.data.email = null

                        }
                    }
              
                }).catch(error =>{
                   
            })
        }, 
    },
    
}
</script>