<template>
    <div>
        <div class="container" style="padding-top: 140px">
            <div
                style="margin: auto;background-color: #b70000;border-radius: 15px;box-shadow: 2px 2px 50px rgba(0,0,0,0.05);width: 50%">
                <div style="text-align: center;color: white" class="mb-2" v-for="(item, index) in errors" :key="index">
                    <b>{{ Object.values(item) | cutErrors }}</b></div>
            </div>
            <div class="row">
                <div style="margin: auto; z-index: 0" class="col-sm-6" v-if="registercomp!=1">
                    <md-card>
                        <md-card-header>
                            <md-card-header-text>
                                <div class="md-title">Mesajif</div>
                                <div class="md-subhead">Giriş işlemi</div>
                            </md-card-header-text>
                        </md-card-header>
                        <md-card-content>
                            <md-field>
                                <label>E-mail</label>
                                <md-input required v-model="loginform.email"></md-input>
                                <span class="md-error">Bu alan Zorunludur</span>

                            </md-field>
                            <md-field>
                                <label>Şifre</label>
                                <md-input required type="password" v-model="loginform.password"></md-input>
                                <span class="md-error">Bu alan Zorunludur</span>

                            </md-field>
                        </md-card-content>


                        <md-card-actions>
                            <md-button @click.prevent="login">Giriş yap</md-button>
                            <md-button @click.prevent="switchcomp">Hesabın yok mu? Kaydol!</md-button>
                        </md-card-actions>
                    </md-card>
                </div>
                <div style="margin: auto; z-index: 0" class="col-sm-6" v-if="registercomp">
                    <md-card>
                        <md-card-header>
                            <md-card-header-text>
                                <div class="md-title">Mesajif</div>
                                <div class="md-subhead">Kayıt işlemi</div>
                            </md-card-header-text>
                        </md-card-header>
                        <md-card-content>
                            <md-field>
                                <label>E-mail</label>
                                <md-input required v-model="registerform.email"></md-input>
                            </md-field>
                            <md-field>
                                <label>Kullanıcı Adı</label>
                                <md-input required v-model="registerform.username" maxlength="20"
                                          @input="usernameChange(registerform.username)"></md-input>
                            </md-field>
                            <md-field>
                                <label>İsim</label>
                                <md-input required v-model="registerform.name"></md-input>
                            </md-field>
                            <md-field>
                                <label>Şifre</label>
                                <md-input required type="password" v-model="registerform.password"></md-input>
                            </md-field>
                            <md-field>
                                <label>Şifre Yeniden</label>
                                <md-input required type="password"
                                          v-model="registerform.password_confirmation"></md-input>
                            </md-field>
                        </md-card-content>
                        <md-card-actions>
                            <md-button @click.prevent="register">Kaydol</md-button>
                            <md-button @click.prevent="switchcomp">Hesabın varsa Giriş yapabilirsin!</md-button>
                        </md-card-actions>
                    </md-card>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "LoginRegister",
    mounted() {
        if (localStorage.getItem('appState')) {
            this.$router.push({name: 'home'})
        }
    },

    data() {
        return {
            errors: "",
            loginform: {
                email: "",
                password: "",
            },
            registerform: {
                email: "",
                username: "",
                name: "",
                password: "",
                password_confirmation: "",
            },
            registercomp: false
        }
    }, filters: {
        cutErrors: function (value) {
            value = value.toString();
            return value;
        },
    }, methods: {
        usernameChange() {
            this.registerform.username = this.registerform.username.replace(/ /g, "_");
        },
        switchcomp() {
            this.registercomp = !this.registercomp
        },
        login() {
            this.errors = ""
            axios.post('/api/login', this.loginform).then(res => {
                    if (res.data.success) {
                        const userData = {
                            id: res.data.id,
                            name: res.data.name,
                            username: res.data.username,
                            email: res.data.email,
                            is_admin: res.data.is_admin,
                            access_token: res.data.access_token
                        }
                        const appState = {
                            user: userData,
                            isLoggedIn: true
                        }
                        try {
                            localStorage.setItem('appState', JSON.stringify(appState))
                            window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + JSON.parse(localStorage.getItem('appState')).user.access_token;
                            this.$router.push({name: "home"});
                        } catch (e) {
                            console.log(e)
                        }
                    } else {
                        alert('Bir Hata Var')
                    }
                }
            ).catch(error => this.errors = error.response.data.errors)
        },
        register() {
            this.errors = ""
            axios.post('/api/register', this.registerform).then(res => {
                    if (res.data.success) {
                        const userData = {
                            id: res.data.id,
                            name: res.data.name,
                            is_admin: res.data.is_admin,
                            username: res.data.username,
                            email: res.data.email,
                            access_token: res.data.access_token
                        }
                        const appState = {
                            user: userData,
                            isLoggedIn: true
                        }
                        try {
                            localStorage.removeItem('appState')
                            localStorage.setItem('appState', JSON.stringify(appState))
                            window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + JSON.parse(localStorage.getItem('appState')).user.access_token;
                            this.$router.push({name: "home"});
                        } catch (e) {
                            console.log(e)
                        }
                    } else {
                        alert('Bir Hata Var')
                    }
                }
            ).catch(error => this.errors = error.response.data.errors)
        }
    }
}
</script>

<style scoped>
</style>
