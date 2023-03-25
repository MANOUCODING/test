import { createRouter, createWebHistory } from "vue-router";

import store from '../store/index';

import LoginComponent from '../components/backoffice/LoginComponent.vue'

import DashboardComponent from '../components/backoffice/admin/DashboardComponent.vue';

import CategoryComponent from '../components/backoffice/admin/categories/CategoryComponent.vue';

import DashboardPublicatorComponent from '../components/backoffice/publicator/DashboardPublicatorComponent.vue';

const routes = [

    {
        path: '/login',
        name: 'login',
        component: LoginComponent,
        meta: {requiresAuth: false}
    },

    {
        path: '/admin/dashboard',
        name: 'admins.dashboard',
        component: DashboardComponent,
        meta: { requiresAuth: true,  }  // add this
    },

    {
        path: '/admin/categories',
        name: 'admins.categories',
        component: CategoryComponent,
        meta: { requiresAuth: true,  }  // add this
    },
   
    {
        path: '/publicateur/dashboard',
        name: 'publicateurs.dashboard',
        component: DashboardPublicatorComponent,
        meta: { requiresAuth: true }  // add this
    },

    
]

const router =  createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((to, from, next) => {

    // check if the route requires authentication and user is not logged in
    if (to.matched.some(route => route.meta.requiresAuth) && !store.state.isLoggedIn) {
        // redirect to login page
        next({ name: 'login' })
        return
    }
    // if logged in redirect to dashboard
    if(to.path === '/login' && store.state.isLoggedIn) {

        if(store.state.user.role == "admin"){

            next({ name: 'admins.dashboard' })
            return

        }else{

            next({ name: 'publicateurs.dashboard' })
            return

        }

    }

    next()
})


export default router;