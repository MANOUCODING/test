import Vuex from 'vuex'

export default new Vuex.Store({
    state: {
        isLoggedIn: !!localStorage.getItem('token'),
        user: {
            data: {},
            role: null,
            token: localStorage.getItem('token')
        }
    },

    actions: {
        getUserRole({commit}){
            axios.get('/api/auth/getRole', { 
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('token')
                }
            }).then(response => {

                if(response.data.status === 200){

                    commit('setUserRole', response.data.role_id)
                    
                }

            })

        }
    },

    mutations: {
        
        loginUser (state) {

            state.isLoggedIn = true

        },

        logoutUser (state) {

            state.isLoggedIn = false

        },

        setUserRole(state, role){

            state.user.role = role

        }
    }
})