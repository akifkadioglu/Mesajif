<template>
    <div>
        <div class="navbar">
            <div>
                <router-link :to="{name:'home'}">
                    <img v-if="setting.image" :src="getPhoto(setting.image)"
                         width="100">
                </router-link>
            </div>
            <div class="dropdown search">
                <md-field>
                    <label>Ara</label>
                    <md-input v-model="searchValue" @input="searchFunction(searchValue)"></md-input>
                </md-field>

                <div class="dropdown-content" v-if="users.length>0">
                    <div v-for="(item,index) in users" :key="index" style="justify-content: space-around;">
                        <router-link :to="{name:'profile',params:{username:item.username}}"
                                     style="text-decoration: none">
                            <div><img
                                style="border-radius:100%; width: 35px" src="https://via.placeholder.com/250">
                                {{ item.username }} &emsp; <small class="text-muted">{{ item.name }}</small>
                            </div>
                        </router-link>
                    </div>
                </div>
            </div>
            <md-menu md-size="small" md-align-trigger>
                <md-button md-menu-trigger class="md-icon-button">
                    <md-icon>account_circle</md-icon>
                </md-button>
                <md-menu-content>
                    <router-link :to="{name:'profile',params: {username: localUser.username}}">
                        <md-menu-item>Hesabım</md-menu-item>
                    </router-link>
                    <md-menu-item>My Item 2</md-menu-item>
                    <md-menu-item>My Item 3</md-menu-item>
                </md-menu-content>
            </md-menu>
        </div>
        <div class="tools container">
            <md-button style="z-index: 0;" :to="{name:'home'}">Ana Sayfa</md-button>
            <md-button style="z-index: 0;" :to="{name:'movements'}">Hareketler</md-button>
            <md-button style="z-index: 0;" :to="{name:'messages'}">Mesajlar</md-button>
        </div>
    </div>
</template>

<script>
export default {
    name: "Navbar",

    mounted() {
        if (!localStorage.getItem('appState')) {
            this.$router.push({name: 'loginRegister'})
        }
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
            searchValue: "",
            users: [],
            localUser: JSON.parse(localStorage.getItem('appState')).user,
        }
    },
    methods: {
        getPhoto(path) {
            return "/storage/" + path;
        },
        searchFunction(value) {
            this.searchValue = value
            axios.post('/api/searchUser', {username: this.searchValue}).then(res => this.users = res.data).catch(e => console.log(e))
        }
    }
}
</script>

<style scoped>
.navbar {
    background-image: linear-gradient(
        45deg,
        rgb(255, 122, 122),
        rgb(255, 234, 118),
        rgb(126, 255, 126),
        rgb(112, 229, 232)
    );
    background-size: 500%;
    animation: bg-animation 5s infinite alternate;
    box-shadow: 0px 0px 30px rgba(0, 0, 0, 0.08);
    position: fixed;
    top: 0;
    width: 100%;
    justify-content: space-around;
    z-index: 1;
}

.tools {
    padding-top: 130px;
    display: flex;
    justify-content: space-around;
    z-index: 0;
}

.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-content {
    width: 100%;
    position: absolute;
    background-color: #ffffff;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    padding: 12px 16px;
    z-index: 1;
}

.search {
    width: 50%;
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
