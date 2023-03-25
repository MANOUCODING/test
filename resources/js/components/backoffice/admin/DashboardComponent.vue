<template>
    <HeaderAdmin></HeaderAdmin>
    <!-- **************** MAIN CONTENT START **************** -->
    <main>
        <!-- =======================Inner intro START -->
        <h1>Super Administrateur</h1>
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

                    if (response.data.role === 'admin') {
                        
                    } else if (response.data.role === 'author') {

                        window.location = '/publicateur/dashboard'

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