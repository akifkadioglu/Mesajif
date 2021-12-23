<template>
    <div>
        <app-navbar></app-navbar>
        <div class="container" style="width: 100%; padding-bottom: 70px">
            <div style="text-align: center">
                <img v-if="user.image"
                     :src="getPhoto(user.image)"
                     alt=""
                />
            </div>
            <div class="row mt-5">
                <label style="font-size:25px">{{ user.name }}</label>
                <label class="mt-4">{{ user.username }}</label>
            </div>
            <md-button style="width: 100%" v-if="user.username !== localUser.username && isFriend === 2"
                       @click.prevent="addFriend" @click="isFriend = 0">
                Arkadaş ekle
            </md-button>
            <md-button style="width: 100%" v-if="user.username !== localUser.username && isFriend === 1"
                       @click.prevent="deleteFriend" @click="isFriend = 2">
                Arkadaşlıktan Çıkar
            </md-button>
            <md-button style="width: 100%" v-if="user.username !== localUser.username && isFriend === 0"
                       @click.prevent="addFriend" @click="isFriend = 2">
                Geri Al
            </md-button>

            <div class="mt-5" style="text-align: center;width:100%;word-break: break-word;display: inline-block;">
                <p>{{ user.about }}</p>
            </div>
            <hr>


            <div class="card mb-2" v-for="(item,index) in posts" :key="index"
                 :style="{'background-color':item.backgroundColor,'color':item.textColor}">
                <div class="card-body" style=" z-index: 0;">

                    <h5 class="card-title">{{ user.name }}</h5>
                    <div style="font-size: 12px" class="card-subtitle mb-4">{{ user.username }}</div>
                    <md-divider></md-divider>
                    <p class="card-text">{{ item.message }}</p>
                    <md-card-actions>
                        <md-button :to="{name:'post',params:{username:user.username,id:item.id}}"
                                   style="background-color: white" class="md-icon-button">
                            <md-icon>visibility</md-icon>
                        </md-button>
                        &emsp;
                        {{ item.likes_count }}
                        <md-button @click.prevent="likePost(item.id,index)" v-if="item.likes_count > 0"
                                   style="background-color: white" class="md-icon-button">
                            <md-icon style="color: red;">favorite</md-icon>
                        </md-button>
                        <md-button @click.prevent="likePost(item.id,index)" v-if="item.likes_count === 0"
                                   style="background-color: white"
                                   class="md-icon-button">
                            <md-icon>favorite</md-icon>
                        </md-button>
                        &emsp;
                        <small>{{ item.created_at | moment("Do MMMM YYYY, H:mm:ss") }}</small>
                    </md-card-actions>
                </div>
            </div>

            <infinite-loading spinner="spiral" @infinite="infiniteHandler">
                <div slot="no-more">.</div>
                <div slot="no-results">{{ user.name }} hiç gönderi atmamış.</div>
            </infinite-loading>


            <md-speed-dial style="position:fixed; bottom: 15%;
    right: 10%;" v-if="user.username === localUser.username">
                <md-speed-dial-target class="button">
                    <md-icon style="color:#000;">account_circle</md-icon>
                    <md-tooltip md-direction="left">Hesap</md-tooltip>
                </md-speed-dial-target>

                <md-speed-dial-content>
                    <md-button class="md-icon-button">
                        <md-icon>share</md-icon>
                        <md-tooltip md-direction="left">Bizi paylaşmak ister misin? :)</md-tooltip>
                    </md-button>
                </md-speed-dial-content>

                <md-speed-dial-content>
                    <md-button :to="{name:'setting'}" class="md-icon-button">
                        <md-icon>settings</md-icon>
                        <md-tooltip md-direction="left">Ayarlar</md-tooltip>
                    </md-button>
                </md-speed-dial-content>


                <md-speed-dial-content>
                    <md-button class="md-icon-button" @click.prevent="logout" style="background-color: red">
                        <md-icon style="color: white">logout</md-icon>
                        <md-tooltip md-direction="left">Çıkış yap</md-tooltip>
                    </md-button>
                </md-speed-dial-content>

            </md-speed-dial>

        </div>
    </div>
</template>

<script>
import Navbar from "./partials/Navbar";

export default {
    name: "Profile",
    components: {
        'app-navbar': Navbar,
    },
    data() {
        return {
            user: {},
            username: "",
            posts: [],
            localUser: JSON.parse(localStorage.getItem('appState')).user,
            isFriend: null,
            page: 1
        }
    },
    mounted() {
        this.username = this.$route.params.username
        this.getUser(this.username)
        this.isFriendMethod(this.username)
    },
    watch: {
        '$route.params.username'(newUsername, oldUsername) {
            this.username = newUsername
            this.infiniteHandler()
            this.getUser(newUsername)
            this.isFriendMethod(newUsername)
        }
    },
    methods: {
        getPhoto(path) {
            return "/storage/" + path;
        },
        infiniteHandler($state) {
            axios.post('/api/getUserPosts', {username: this.username}, {
                params: {
                    page: this.page,
                },
            }).then(({data}) => {
                if (data.data.length) {
                    this.page += 1;
                    this.posts.push(...data.data);
                    $state.loaded();
                } else {
                    $state.complete();
                }
            });
        },
        isFriendMethod(newUsername) {
            axios.post('/api/isFriend', {username: newUsername}).then(res => {
                    try {
                        this.isFriend = res.data
                    } catch (e) {
                        console.log(e)
                    }
                }
            )
        },
        getUser(newUsername) {
            axios.post('/api/getUser', {username: newUsername}).then(res => {
                    try {
                        this.user = res.data
                    } catch (e) {
                        console.log(e)
                    }
                }
            )
        },
        getUserPosts(newUsername) {
            axios.post('/api/getUserPosts', {username: newUsername}).then(res => {
                    try {
                        this.posts = res.data
                    } catch (e) {
                        console.log(e)
                    }
                }
            )
        }
        ,
        likePost(id, index) {
            axios.post('/api/likePost', {id: id}).then(res => {
                if (res.data.success) {
                    this.posts[index].likes_count = this.posts[index].likes_count + 1
                } else {
                    this.posts[index].likes_count = this.posts[index].likes_count - 1
                }
            }).catch(e => console.log(e))
        },
        addFriend() {
            axios.post('/api/addFriend', {
                username: this.username
            }).then(res => console.log(res.data)).catch(e => console.log(e))
        },
        deleteFriend() {
            axios.post('/api/deleteFriend', {
                username: this.username
            }).then(res => console.log(res.data)).catch(e => console.log(e))
        },
        logout() {
            window.axios.defaults.headers.common['Authorization'] = null
            localStorage.removeItem('appState')
            this.$router.push({name: 'home'})
        },
    }
}
</script>

<style scoped>
.button {
    background-image: linear-gradient(30deg, #ff7d7d, #fff780, #beff7b, #7effec);
    background-size: 400%;
    animation: bg-animation 3s infinite alternate;
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
