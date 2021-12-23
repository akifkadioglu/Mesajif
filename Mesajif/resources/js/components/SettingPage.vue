<template>
    <div>
        <app-navbar></app-navbar>
        <div class="container" style="margin: auto; width: 50%">

            <div
                style="margin: auto;background-color: #b70000;border-radius: 15px;box-shadow: 2px 2px 50px rgba(0,0,0,0.05);width: 50%">
                <div style="text-align: center;color: white" class="mb-2" v-for="(item, index) in errors" :key="index">
                    <b>{{ Object.values(item) | cutErrors }}</b></div>
            </div>
            <md-steppers :md-active-step.sync="active" md-vertical md-linear style="width: 450px;margin: auto;">
                <md-step id="first" md-label="Aktif şifre" md-description="Zorunlu" :md-editable="false"
                         :md-done.sync="first">
                    <md-field>
                        <label>Değişiklikleri yapmak için e-mailinizi yazmanız gerekmektedir.</label>
                        <md-input type="email" placeholder="E-mail" v-model="email" required></md-input>
                    </md-field>
                    <md-field>
                        <label>Değişiklikleri yapmak için şifrenizi yazmanız gerekmektedir.</label>
                        <md-input type="password" placeholder="Şifre" v-model="password" required></md-input>
                    </md-field>
                    <md-button class="md-raised md-primary"
                               @click.prevent="checkPassword">Devam
                    </md-button>
                </md-step>

                <md-step id="second" md-label="Açıklama" :md-error="secondStepError" :md-editable="false"
                         :md-done.sync="second">
                    <p>Değiştirmek istediğiniz ayarların tikini açmanız gerekmektedir.</p>
                    <md-button class="md-raised md-primary" @click="setDone('second', 'third')">Anladım</md-button>
                </md-step>

                <md-step id="third" md-label="Ayarlar" :md-editable="false" :md-done.sync="third">
                    <md-switch v-model="form.newEmail">Yeni E-mail?</md-switch>
                    <md-field v-if="form.newEmail==1">
                        <label>E-mail</label>
                        <md-input v-model="form.email"></md-input>
                    </md-field>
                    <md-switch v-model="form.newUsername">Yeni Kullanıcı adı?</md-switch>
                    <md-field v-if="form.newUsername==1">
                        <label>Kullanıcı Adı</label>
                        <md-input maxlength="20" v-model="form.username"
                                  @input="usernameChange(form.username)"></md-input>
                    </md-field>
                    <md-switch v-model="form.newImage">Yeni Profil Fotoğrafı?</md-switch>
                    <md-field v-if="form.newImage==1">
                        <input
                            type="file"
                            class="mb-4 form-control"
                            @change="handleOnchange"
                        />
                    </md-field>
                    <md-switch v-model="form.newName">Yeni Ad?</md-switch>
                    <md-field v-if="form.newName==1">
                        <label>Adı</label>
                        <md-input maxlength="20" v-model="form.name"></md-input>
                    </md-field>
                    <md-switch v-model="form.newAbout_me">Yeni Hakkımda?</md-switch>
                    <md-field v-if="form.newAbout_me==1">
                        <label>Hakkımda</label>
                        <md-input maxlength="255" v-model="form.about_me"></md-input>
                    </md-field>
                    <md-switch v-model="form.newPassword">Yeni Şifre?</md-switch>
                    <md-field v-if="form.newPassword==1">
                        <label>Yeni Şifre</label>
                        <md-input v-model="form.password"></md-input>
                    </md-field>

                    <md-button class="md-raised" @click.prevent="saveSettings">Kaydet
                        <md-icon>build</md-icon>
                    </md-button>
                </md-step>
            </md-steppers>
        </div>
        <md-speed-dial style="position:fixed; bottom: 10%;right: 15%">
            <md-speed-dial-target style="background-color: #1d5a9a">
                <md-icon>account_circle</md-icon>
                <md-tooltip md-direction="left">Hesap</md-tooltip>
            </md-speed-dial-target>

            <md-speed-dial-content>
                <md-button @click.prevent="deleteMyAllMessage" class="md-icon-button">
                    <md-icon style="color: red">delete</md-icon>
                    <md-tooltip md-direction="left">Mesajları Sil</md-tooltip>
                </md-button>
            </md-speed-dial-content>
        </md-speed-dial>

        <md-dialog-alert
            :md-active.sync="deleteAlert"
            md-content="Paylaşılan tüm mesajların silindi!"
            md-confirm-text="Tamam"/>
    </div>

</template>

<script>
import Navbar from "./partials/Navbar";

export default {
    name: "SettingPage",
    components: {
        'app-navbar': Navbar,
    },
    mounted() {
    },
    data() {
        return {
            deleteAlert: false,
            form: {
                email: "",
                username: "",
                name: "",
                about_me: "",
                password: "",
                image: "",

                newImage: 0,
                newEmail: 0,
                newUsername: 0,
                newName: 0,
                newAbout_me: 0,
                newPassword: 0,
            },
            password: "",
            email: "",
            active: 'first',
            first: false,
            second: false,
            third: false,
            secondStepError: null,
            errors: {}
        }
    },
    methods: {
        getPhoto(path) {
            return "/storage/" + path;
        },
        handleOnchange(e) {
            this.form.image = e.target.files[0];
        },
        usernameChange() {
            this.form.username = this.form.username.replace(/ /g, "_");
        },
        checkPassword() {
            axios.post('/api/checkPassword', {password: this.password, email: this.email})
                .then(
                    res => {
                        if (res.data === 1) {
                            this.setDone('first', 'second')
                        } else (
                            alert('Şifre Hatalı')
                        )
                    }
                ).catch(e => console.log(e));
        }, saveSettings() {
            const image = new FormData();
            image.append("image", this.form.email);
            image.append("username", this.form.username);
            image.append("name", this.form.name);
            image.append("about_me", this.form.about_me);
            image.append("password", this.form.password);
            image.append("image", this.form.image);
            image.append("newImage", this.form.newImage);
            image.append("newEmail", this.form.newEmail);
            image.append("newUsername", this.form.newUsername);
            image.append("newName", this.form.newName);
            image.append("newAbout_me", this.form.newAbout_me);
            image.append("newPassword", this.form.newPassword);

            axios.post('/api/changeSettings', image)
                .then(
                    res => {
                        const userData = {
                            id: res.data.id,
                            name: res.data.name,
                            username: res.data.username,
                            email: res.data.email,
                            access_token: res.data.access_token,
                            about: res.data.about
                        }
                        const appState = {
                            user: userData,
                            isLoggedIn: true
                        }
                        try {
                            localStorage.removeItem('appState')
                            localStorage.setItem('appState', JSON.stringify(appState))
                            axios.defaults.headers.common['Authorization'] = `Bearer ${res.data.access_token}`
                            this.$router.push({name: "profile", params: {username: res.data.username}});
                        } catch (e) {
                            console.log(e)
                        }
                    }
                ).catch(error => this.errors = error.response.data.errors)
        },
        setDone(id, index) {
            this[id] = true

            this.secondStepError = null

            if (index) {
                this.active = index
            }
        },
        setError() {
            this.secondStepError = 'This is an error!'
        },

        deleteMyAllMessage() {
            axios.post('/api/deleteUserPosts', {id: JSON.parse(localStorage.getItem('appState')).user.id}).then(this.deleteAlert = true).catch();
        }
    }, filters: {
        cutErrors: function (value) {
            value = value.toString();
            return value;
        },
    },

}
</script>

<style scoped>

</style>
