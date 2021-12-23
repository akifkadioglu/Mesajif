<template>
    <div>
        <div class="navbar bg-dark footer text-light">
            <div></div>
            <div>Mesajif 2022'</div>
            <div></div>
        </div>


        <md-dialog :md-active.sync="active">
            <md-dialog-title>Genel Mesaj Paylaş!</md-dialog-title>

            <md-tabs md-dynamic-height>
                <md-tab md-label="Mesaj">
                    <md-field style="width: 100%">
                        <label>Mesajınız</label>
                        <md-input v-model="value" maxlength="255"></md-input>
                    </md-field>
                </md-tab>
                <md-tab md-label="Tema" :style="{'background-color':backGroundColor,'color':textColor}">
                    <md-button @click.prevent="changeTheme">Tema Kullan!</md-button>
                    <div v-if="theme">
                        Arka plan
                        <input v-model="backGroundColor" type="color">
                    </div>
                    <div v-if="theme">
                        Yazı rengi
                        <input v-model="textColor" type="color">
                    </div>
                </md-tab>

            </md-tabs>

            <md-dialog-actions>
                <md-button class="md-primary" @click="active = false">Kapat</md-button>
                <md-button class="md-primary" @click="active = false" @click.prevent="sendPost">Gönder!</md-button>
            </md-dialog-actions>
        </md-dialog>

        <md-button @click="active = true" class="md-fab button">
            <md-icon style="color: black">edit</md-icon>
        </md-button>
    </div>
</template>

<script>
export default {
    name: "Footer",
    data() {
        return {
            active: false,
            value: null,
            backGroundColor: 'white',
            textColor: 'black',
            theme: false
        }
    },
    methods: {
        changeTheme() {
            this.theme = !this.theme;
            this.backGroundColor = "white";
            this.textColor = "black";
        },
        sendPost() {
            axios.post('/api/sendPost', {
                message: this.value,
                backGroundColor: this.backGroundColor,
                textColor: this.textColor
            }).then(this.value = null).catch(e => console.log(e))
            this.changeTheme()
        }
    }
}
</script>

<style scoped>

.footer {
    position: fixed;
    bottom: 0;
    width: 100%;
    z-index: 1;
}

.button {
    background-image: linear-gradient(30deg, #ff7d7d, #fff780, #beff7b, #7effec);
    background-size: 400%;
    animation: bg-animation 3s infinite alternate;
    position: fixed;
    bottom: 15%;
    right: 10%;
}

@keyframes bg-animation {
    0% {
        background-position: left;
    }
    100% {
        background-position: right;
    }
}
</style>
