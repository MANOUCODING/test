import axios from 'axios'

import store from '../store/index'

const AxiosBackoffice = axios.create({
    baseURL: 'http://localhost:8000/api/auth'
})

AxiosBackoffice.interceptors.request.use(config => {

    config.headers.Authorization = `Bearer ${store.state.user.token}`

    return config;

})

export default AxiosBackoffice;