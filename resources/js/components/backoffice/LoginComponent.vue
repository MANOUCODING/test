<template>
<HeaderWhithoutAuth></HeaderWhithoutAuth>
    <!-- **************** MAIN CONTENT START **************** -->
    <main>
        <!-- =======================Inner intro START -->
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-md-10 col-lg-8 col-xl-6 mx-auto">
                        <div class="p-4 p-sm-5 bg-primary bg-opacity-10 rounded">
                            <h2>Page de connexion</h2>
                            <!-- Form START -->
                            <div v-if="error">
                                <br>
                                <div class="alert alert-danger"  role="alert">
                                    {{ error }}
                                </div>
                            </div>
                            <form class="mt-4">
                                <!-- Email -->
                                <div class="mb-3" v-if="errors.username">
                                    <label class="form-label" for="exampleInputEmail1">Nom d'utilisateur ou votre email</label>
                                    <input type="text" v-model="username" name="username" class="form-control is-invalid" id="exampleInputEmail1" placeholder="Votre nom d'utilisateur ou votre email">
                                    <div id="exampleInputEmail1" v-for="errorUsername in errors.username" :key="errorUsername" class="invalid-feedback">
                                        {{ errorUsername }}
                                    </div>
                                </div>
                                <div class="mb-3" v-else>
                                    <label class="form-label" for="exampleInputEmail1">Nom d'utilisateur ou votre email</label>
                                    <input type="text" v-model="username" name="username" class="form-control" id="exampleInputEmail1" placeholder="Votre nom d'utilisateur ou votre email">
                                </div>
                                <!-- Password -->
                                <div class="mb-3" v-if="errors.password">
                                    <label class="form-label" for="exampleInputPassword1">Mot de passe</label>
                                    <input type="password"  v-model="password" name="password" class="form-control is-invalid" id="exampleInputPassword1" placeholder="*********">
                                    <div id="exampleInputPassword1" v-for="errorPassword in errors.password" :key="errorPassword" class="invalid-feedback">
                                        {{ errorPassword }}
                                    </div>
                                </div>
                                <div class="mb-3" v-else>
                                    <label class="form-label" for="exampleInputPassword1">Mot de passe</label>
                                    <input type="password"  v-model="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="*********">
                                </div>
                                <!-- Checkbox -->
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                    <label class="form-check-label" for="exampleCheck1">Se souvenir de moi</label>
                                </div>
                                <!-- Button -->
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <button type="button" v-if="loading" class="btn btn-success"> <i  style="color: #fff" class="fa fa-spinner fa-spin fa-1x fa-fw"></i>
                                            <span class="sr-only">Loading...</span>  Connexion en cours ...</button>
                                        <button type="submit"  @click.prevent="submitLogin" v-else class="btn btn-success">Se connecter</button>
                                    </div>
                                    
                                </div>
                            </form>
                            <!-- Form END -->
                            <hr>
                            
                            <!-- Social-media btn -->
                            <div class="text-center">
                                <p>Visitez nos pages sur les différents réseaux sociaux</p>
                                <ul class="list-unstyled d-sm-flex mt-3 justify-content-center">
                                    <li class="mx-2">
                                        <a href="https://www.facebook.com/Togoactualite-148480121847124" class="btn bg-facebook d-inline-block"><i class="fab fa-facebook-f me-2"></i> &nbsp; Facebook</a>
                                    </li>
                                    <li class="mx-2">
                                        <a href="https://twitter.com/Togoactualite" class="btn bg-twitter d-inline-block"><i class="fab fa-twitter-square"></i> &nbsp; Twitter</a>
                                    </li>
                                    <li class="mx-2">
                                        <a href="https://fr.linkedin.com/in/togoactualite-togo-actualit%C3%A9-3a076648" class="btn bg-linkedin d-inline-block"><i class="fab fa-linkedin"></i> &nbsp; Linkedin</a>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- =======================Inner intro END -->    
    </main>
<!-- **************** MAIN CONTENT END **************** -->
<FooterBackoffice></FooterBackoffice>
</template>

<script>
import store from '../../store/index'
    export default {
        data() {
            return {
                username: '',
                password: '',
                loginError: false,
                loading: false,
                error: false,
                errors: []
            }
        },
        methods: {
            submitLogin() {
                this.loading = true

                this.loginError = false;

                axios.post('/api/auth/login', {username: this.username, password: this.password}).then(response => {

                    this.loading = false

                    if(response.data.status === 200){

                        // login user, store the token and redirect to dashboard

                        store.commit('loginUser')

                        store.state.user.token = response.data.token.original.access_token

                        store.state.user.role = response.data.user.role_name

                        localStorage.setItem('token', response.data.token.original.access_token)

                        if(response.data.user.role_name == "admin"){

                           window.location = '/admin/dashboard'

                        }else{

                            window.location = '/publicateur/dashboard'

                        }

                    }else{

                        if (response.data.status === 401) {

                            this.errors = response.data.errors

                        } else {
                            
                            this.error = response.data.message

                        }

                    }

                    
                   
                }).catch(error => {
                    this.loginError = true
                });
            }
        }
    }

</script>