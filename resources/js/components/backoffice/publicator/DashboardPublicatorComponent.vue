<template>
    <HeaderPublicator></HeaderPublicator>
    <!-- **************** MAIN CONTENT START **************** -->
    <main>
        <!-- =======================Inner intro START -->
        <h1>Publicateur d'article</h1>
    </main>
    <FooterBackoffice></FooterBackoffice>
</template>

<script>
import store from '../../../store/index'
import AxiosBackoffice from '../../../axios/AxiosBackoffice'
export default {

    methods: {
        getResults(){
            AxiosBackoffice.get('/getRole').then(response => {

                if(response.data.status === 200){

                    if (response.data.role === 'author') {
                        
                    } else if ( response.data.role === 'admin') {

                        window.location = '/admin/dashboard'

                    }

                }
            }).catch(err =>{
                store.commit('logoutUser')
                localStorage.removeItem('token')
                window.location = '/login'
            })

        }
    },
    mounted() {
        this.getResults();
    },
}
</script>