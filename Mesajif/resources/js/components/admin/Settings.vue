<template>
    <div>
        <app-navbar></app-navbar>
        <div class="container">
            <md-card style="width: 100%; margin: auto; margin: 50px">
                <md-card-header>
                    <div class="md-title" style="text-align: center">
                        {{ setting.title }}
                    </div>
                </md-card-header>

                <md-card-content>
                    <img v-if="setting.image"
                        :src="getPhoto(setting.image)"
                        style="width: 100%"
                        alt=""
                    />
                    <input
                        type="file"
                        class="mb-4 form-control"
                        @change="handleOnchange"
                    />
                    <md-field>
                        <label>Site Başlığı</label>
                        <md-input v-model="setting.title"></md-input>
                    </md-field
                    >
                    <md-field>
                        <label>Anahtar Kelimeler</label>
                        <md-input
                            v-model="setting.keywords"
                        ></md-input>
                    </md-field
                    >
                    <md-field>
                        <label>Açıklamalar</label>
                        <md-input
                            v-model="setting.description"
                        ></md-input>
                    </md-field
                    >
                    <md-field>
                        <label>Adres</label>
                        <md-input
                            v-model="setting.address"
                        ></md-input>
                    </md-field
                    >
                    <md-field>
                        <label>Telefon</label>
                        <md-input v-model="setting.phone"></md-input>
                    </md-field
                    >
                    <md-field>
                        <label>Facebook</label>
                        <md-input
                            v-model="setting.facebook"
                        ></md-input>
                    </md-field
                    >
                    <md-field>
                        <label>İnstagram</label>
                        <md-input
                            v-model="setting.instagram"
                        ></md-input>
                    </md-field
                    >
                    <md-field>
                        <label>Youtube</label>
                        <md-input
                            v-model="setting.youtube"
                        ></md-input>
                    </md-field
                    >
                    <md-field>
                        <label>E-Mail</label>
                        <md-input v-model="setting.email"></md-input>
                    </md-field>
                </md-card-content>

                <md-card-actions>
                    <md-button
                        @click.prevent="editsettings"
                        style="color: green"
                    >Güncelle
                    </md-button
                    >
                </md-card-actions>
            </md-card>
        </div>
    </div>
</template>

<script>

import Navbar from "./adminPartials/Navbar";

export default {
    name: 'Settings',
    mounted() {
        axios.get("/api/settings").then((res) => (this.setting = res.data));
    },
    data() {
        return {
            setting: {
                title: "",
                keywords: "",
                description: "",
                image: "",
                address: "",
                phone: "",
                facebook: "",
                instagram: "",
                youtube: "",
                email: "",
            },
        };
    },
    components: {
        "app-navbar": Navbar,
    },
    methods: {
        handleOnchange(e) {
            this.setting.image = e.target.files[0];
        },
        getPhoto(path) {
            return "/storage/" + path;
        },
        editsettings() {
            const formData = new FormData();
            formData.append("image", this.setting.image);
            formData.append("title", this.setting.title);
            formData.append("keywords", this.setting.keywords);
            formData.append("description", this.setting.description);
            formData.append("address", this.setting.address);
            formData.append("phone", this.setting.phone);
            formData.append("facebook", this.setting.facebook);
            formData.append("instagram", this.setting.instagram);
            formData.append("youtube", this.setting.youtube);
            formData.append("email", this.setting.email);
            axios
                .post("/api/editsettings", formData)
                .then((res) => (this.setting = res.data));
        },
    },
};
</script>

<style></style>
