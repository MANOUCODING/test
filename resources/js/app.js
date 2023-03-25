require('./bootstrap')

import { createApp } from 'vue'

import axios from 'axios'

import backofficeRouter from './router/backoffice'

import VueAxios from 'vue-axios'

import VueSweetalert2 from 'vue-sweetalert2';

import 'sweetalert2/dist/sweetalert2.min.css';

//Importation des composants frontoffice

import tagsFooter from './components/frontoffice/includes/tags.vue'

import newsletterFooter from './components/frontoffice/includes/newsletter.vue'

//Importation des composants d'inclusion backoffice

import HeaderWhithoutAuthComponent from './components/backoffice/includes/header/HeaderWhithoutAuthComponent.vue'

import HeaderAdminComponent from './components/backoffice/includes/header/HeaderAdminComponent.vue'

import HeaderPublicatorComponent from './components/backoffice/includes/header/HeaderPublicatorComponent.vue'

import FooterComponent from './components/backoffice/includes/FooterComponent.vue';

//Importation du composant layouts du backoffice

import backofficeL from './components/backoffice/layouts/backoffice.vue'

//Déclaration du composant layouts du backoffice

const backoffice = createApp(backofficeL)

backoffice.use(VueAxios, axios)

backoffice.use(backofficeRouter)

backoffice.use(VueSweetalert2)

backoffice.component('HeaderWhithoutAuth', HeaderWhithoutAuthComponent )

backoffice.component('HeaderPublicator', HeaderPublicatorComponent )

backoffice.component('HeaderAdmin', HeaderAdminComponent )

backoffice.component('FooterBackoffice', FooterComponent )

backoffice.mount('#backoffice')

//Déclaration des composants layouts du frontoffice

const tags = createApp(tagsFooter)

tags.use(VueAxios, axios)

const newsletter = createApp(newsletterFooter)

newsletter.use(VueAxios, axios)

newsletter.use(VueSweetalert2);

tags.mount('#tags')

newsletter.mount('#newsletter')
