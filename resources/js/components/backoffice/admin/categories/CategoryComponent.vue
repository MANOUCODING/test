<template>
    <HeaderAdmin></HeaderAdmin>
    <!-- **************** MAIN CONTENT START **************** -->
    <main>
        <!-- =======================Inner intro START -->
        <div v-if="loading">
            <div style="position: relative">
                <div class="lds-ellipsis" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%);"><div></div><div></div><div></div><div></div></div>
                <br/><br/><br/><br/><br/><br/>
            </div>
        </div>
        <!-- =======================Author list START -->
        <section v-else class="py-4">
            <div class="container">
            <!-- Author list title START -->
                <div class="row g-4 pb-4">
                    <div class="col-12">
                        <!-- Title -->
                        <div class="d-sm-flex justify-content-sm-between align-items-center">
                            <h1 class="mb-2 mb-sm-0 h2">Mes catégories <span class="badge bg-success bg-opacity-10 text-light">{{ infos.categoryCount }}</span></h1>			
                            <span @click="addCategory"  class="btn btn-sm btn-success mb-0"><i class="fas fa-plus me-2"></i>Ajouter une catégorie</span>
                            <!-- Vertically centered scrollable modal -->
                                                    
                            
                        </div>
                    </div>
                </div>
                <!-- Author list title START -->
                <div class="row g-4">
                    <div class="col-12">
                        <!-- Card START -->
                        <div class="card border">
                            <!-- Card header START -->
                            <div class="card-header border-bottom p-3">
                                <!-- Search and select START -->
                                <div class="row g-3 align-items-center justify-content-between">
                                    <!-- Search bar -->
                                    <div class="col-md-8">
                                        <form class="rounded position-relative"  @submit.prevent="getResults">
                                            <input class="form-control bg-transparent" type="search" v-model.trim="search"  @keyup="getResults" placeholder="Rechercher une catégorie" aria-label="Search">
                                            <button class="btn bg-transparent border-0 px-2 py-0 position-absolute top-50 end-0 translate-middle-y" type="submit"><i class="fas fa-search fs-6 "></i></button>
                                        </form>
                                    </div>
                                    <!-- Tab buttons -->
                                    <div class="col-md-3">
                                        <!-- Tabs START -->
                                        <ul class="list-inline mb-0 nav nav-pills nav-pill-dark-soft border-0 justify-content-end" id="pills-tab" role="tablist">
                                            <!-- Grid tab -->
                                            <li class="nav-item">
                                                <a href="#nav-list-tab" class="nav-link mb-0 me-2 active" data-bs-toggle="tab">
                                                    <i class="fas fa-fw fa-list-ul"></i>
                                                </a>
                                            </li>
                                            <!-- List tab -->
                                            <li class="nav-item">
                                                <a href="#nav-grid-tab" class="nav-link mb-0" data-bs-toggle="tab">
                                                    <i class="fas fa-fw fa-th-large"></i>
                                                </a>
                                            </li>
                                        </ul>
                                        <!-- Tabs end -->

                                    </div>
                                </div>
                                <!-- Search and select END -->
                            </div>
                            <!-- Card header END -->
                            <!-- Card body START -->
                            <div class="card-body p-3 pb-0">
                                <!-- Tabs content START -->
                                <div class="tab-content py-0 my-0">

                                    <!-- Tabs content item START -->
                                    <div class="tab-pane fade show active" id="nav-list-tab">
                                        <!-- Table START -->
                                        <div class="table-responsive border-0" v-if="empty === 2">
                                            <table class="table align-middle p-4 mb-0 table-hover" >
                                                <!-- Table head -->
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th scope="col" class="border-0 rounded-start">Catégorie</th>
                                                        <th scope="col" class="border-0">Ajouté le</th>
                                                        <th scope="col" class="border-0">Tous les articles</th>
                                                        <th scope="col" class="border-0">Articles publiés</th>
                                                        <th scope="col" class="border-0 rounded-end">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="border-top-0" v-for="category in infos.categories.data" :key="category.id">
											        <!-- Table row -->
                                                    <tr>
                                                        <!-- Table data -->
                                                        <td class="text-primary">
                                                            <td>{{ category.name }}</td>
                                                        </td>
                                                        <!-- Table data -->
                                                        <td>{{ moment(category.updated_at).format(" MMM DD, YYYY") }}</td>
                                                        <!-- Table data -->
                                                        <td v-if="category.count == 0"><span class="badge bg-danger bg-opacity-10 text-danger mb-2">Pas d'article</span></td>
                                                        <td v-else class="text-primary"> {{ category.count }} articles </td>
                                                        <!-- Table data -->
                                                        <td><span class="badge bg-danger bg-opacity-10 text-danger mb-2">Pas d'article</span></td>
                                                        
                                                        <!-- Table data -->
                                                        <td>
                                                            <div class="d-flex gap-2">
                                                                <a href="#" class="btn btn-primary btn-round mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Editer cette catégorie" aria-label="Editer cette catégorie">
                                                                    <i class="bi bi-pencil-fill"></i>
                                                                </a>
                                                                <span style="cursor: pointer" @click="deleteCategory(category.slug)" class="btn btn-danger btn-round mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Supprimez cette catégorie" aria-label="Supprimez cette catégorie">
                                                                    <i class="fas fa-trash"></i>
                                                                </span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="d-sm-flex justify-content-sm-between align-items-sm-center mt-4 mt-sm-3">
                                            <p class="mb-sm-0 text-center text-sm-start">Affichage de 1 à 10 sur {{ infos.categoryCount }} entrées</p>
                                            <Bootstrap5Pagination
                                                class="mb-0"
                                                :data="infos.categories"
                                                :limit="limit"
                                                :keep-length="keepLength"
                                                :show-disabled="showDisabled"
                                                :size="size"
                                                :align="align"
                                                @pagination-change-page="getResults"
                                            />
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="nav-grid-tab">
                                        <div class="row g-4">
        
                                            <!-- Card item START -->
                                            <div class="col-md-6 col-xl-4" v-for="category in infos.categories.data" :key="category.id">
                                                <div class="card border p-2">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            
                                                            <!-- Connections holder -->
                                                            <div class="flex-grow-1 d-block">
                                                                <h5 class="mb-1"><a href="#"> {{ category.name }} </a></h5>
                                                                <div class="small text-success" style="cursor: pointer">{{ category.slug }}</div>
                                                            </div>
                                                        </div>

                                                        <!-- Followers and Post -->
                                                        <div class="d-sm-flex justify-content-sm-between mt-3">
                                                            <!-- Followers -->
                                                            <div class="d-flex text-start align-items-center mt-3">
                                                                <div class="icon-md bg-light text-body rounded-circle flex-shrink-0">
                                                                    <i class="bi bi-people-fill fa-fw"></i>
                                                                </div>
                                                                <div class="ms-2">
                                                                    <h5 class="mb-0"> {{ category.count }} </h5>
                                                                    <h6 class="mb-0 fw-light">Publiés</h6>
                                                                </div>
                                                            </div>

                                                            <!-- Total post -->
                                                            <div class="d-flex text-start align-items-center mt-3">
                                                                <div class="icon-md bg-light text-body rounded-circle flex-shrink-0">
                                                                    <i class="bi bi-file-earmark-text-fill fa-fw"></i>
                                                                </div>
                                                                <div class="ms-2">
                                                                    <h5 class="mb-0"> {{ category.count }} </h5>
                                                                    <h6 class="mb-0 fw-light">Articles</h6>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Buttons -->
                                                        <div class="d-sm-flex gap-2 mt-4">
                                                            <a href="#" class="btn btn-primary w-100">
                                                                 Voir les articles
                                                            </a>
                                                            <button type="button" @click="deleteCategory(category.slug)" class="btn btn-danger w-50"  data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Supprimez cette catégorie" aria-label="Supprimez cette catégorie">
                                                                <i class="fas fa-trash pe-2"></i> 
                                                            </button>
                                                            <a href="#" class="btn btn-success w-50" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Editer cette catégorie" aria-label="Editer cette catégorie">
                                                                <i class="fas fa-edit pe-2"></i> 
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-sm-flex justify-content-sm-between align-items-sm-center mt-4 mt-sm-3">
                                            <p class="mb-sm-0 text-center text-sm-start">Affichage de 1 à 10 sur {{ infos.categoryCount }} entrées</p>
                                            <Bootstrap5Pagination
                                                class="mb-0"
                                                :data="infos.categories"
                                                :limit="limit"
                                                :keep-length="keepLength"
                                                :show-disabled="showDisabled"
                                                :size="size"
                                                :align="align"
                                                @pagination-change-page="getResults"
                                            />
                                        </div>
                                    </div>
                                
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <FooterBackoffice></FooterBackoffice>
</template>
<script>
import moment from 'moment'
import store from '../../../../store/index'
import AxiosBackoffice from '../../../../axios/AxiosBackoffice'
import { Bootstrap5Pagination } from '../../../../pagination/lib';
export default {
    components: {
        Bootstrap5Pagination,
    },
    data() {
        return {
            loading: true,
            search: '',
            query: '',
            infos: null,
            empty: null,
            style: 'bootstrap5',
            limit: 1,
            keepLength: false,
            showDisabled: false,
            size: 'default',
            align: 'left'
        }
    },
    methods: {
        getResults(page = 1){
            AxiosBackoffice.get('/getRole').then(response => {

                if(response.data.status === 200){

                    if (response.data.role === 'admin') {

                        AxiosBackoffice.get(`/admin/categories?page=${page}&search=${this.search}`).then((response) => {
                            
                            this.loading = false
                            
                            if(response.data.data.status == 401){

                                this.empty = 1
                                this.message = response.data.message
                                this.infos = response.data.data  

                            }else if(response.data.data.status == 200){

                                this.empty = 2
                                this.infos = response.data.data

                            }

                            
                        })
                        .catch((err) => {
                            store.commit('logoutUser')
                            localStorage.removeItem('token')
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
                                title: 'Veuillez vous recconectez,Votre session est expiré.'
                            })
                            this.$router.push({ name: 'login'})
                        });
                        
                    } else if (response.data.role === 'author') {

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
                                title: 'Vous n\'êtes pas autorisé à accceder a cette page'
                            })
                            this.$router.push({ name: 'publicateurs.dashboard'})

                    }

                }
            }).catch(err =>{
                store.commit('logoutUser')
                localStorage.removeItem('token')
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
                    title: 'Veuillez vous recconectez,Votre session est expiré.'
                })
                this.$router.push({ name: 'login'})
            })

        },

        addCategory(){
            let open = false
            const value =  this.$swal.fire({
                title: 'Ajouter une catégorie',
                input: 'email',
                inputLabel: 'Nom de la catégorie',
                inputPlaceholder: 'Entrez le nomde la catégorie',
                confirmButton: true,
                inputValidator: (value) => {
                    if (!value) {
                        open = true
                        return 'Le nom de la catégorie est obligatoire!'

                    }else{
                        AxiosBackoffice.post('/admin/categories/store',{
                            name : this.data.name,
                            })
                            .then(response =>{
                                if(response.data.data.status == 200){  
                                
                                }else if(response.data.data.status = 401){

                                    const Toast = this.$swal.mixin({
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 10000,
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

                                }
                            }).catch(error =>{
                                console.dir(error)
                        })
                    }
                }
            })

           
        },

        deleteCategory(slug) {
            this.$swal({
                title: "Etes-vous sûr?",
                text: "Voulez vous vraiment supprimez cette catégorie!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "blue",
                confirmButtonText: "Oui, supprimez!",
                cancelButtonText: "Non, annuler !",
                closeOnConfirm: true,
                closeOnCancel: true
            }).then((confirmed) => {
                if (confirmed.isConfirmed) {
                    AxiosBackoffice.get(`/admin/categories/${slug}/delete`)
                        .then(response => {
                            this.getResults();
                            if (response.data.data.status == 200) {
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
                            } else {
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
                            }
                        }).catch((err) => {
                            store.commit('logoutUser')
                            localStorage.removeItem('token')
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
                                title: 'Veuillez vous recconectez,Votre session est expiré.'
                            })
                            this.$router.push({ name: 'login'})
                        });
                }
            });
            
        },

    },
    mounted() {
        this.moment = moment;
        this.searchCategoryButton = true
        this.getResults();
    },
}
</script>