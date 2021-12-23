<template>
    <div>
        <app-navbar></app-navbar>
        <div class="container">
            <div class="card mb-2" v-for="(item,index) in posts" :key="index"
                 :style="{'background-color':item.backgroundColor,'color':item.textColor}">
                <div class="card-body" style=" z-index: 0;">
                    <router-link style="text-decoration: none" :style="{'color':item.textColor}"
                                 :to="{name:'profile',params:{username:item.user.username}}">
                        <h5 class="card-title">{{ item.user.name }}</h5>
                    </router-link>
                    <router-link style="text-decoration: none" :style="{'color':item.textColor}" :to="{name:'profile',params:{username:item.user.username}}">
                        <div style="font-size: 12px" class="card-subtitle mb-4">{{ item.user.username }}</div>
                    </router-link>
                    <md-divider></md-divider>
                    <p class="card-text">{{ item.message }}</p>
                    <md-card-actions>

                        <md-button :to="{name:'post',params:{username:item.user.username,id:item.id}}"
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
                <div slot="no-more">Daha fazla gönderi yok, arkadaş edinmeye ne dersin?</div>
                <div slot="no-results">Hiç gönderi yok, arkadaş edinmeye ne dersin?</div>
            </infinite-loading>

        </div>

        <app-footer></app-footer>
    </div>
</template>

<script>
import Footer from "./partials/Footer";
import Navbar from "./partials/Navbar";

export default {
    name: "Home",
    components: {
        'app-footer': Footer,
        'app-navbar': Navbar,
    },
    data() {
        return {
            posts: [],
            page: 1,

        }
    }
    ,
    mounted() {

        this.infiniteHandler
    },
    methods: {
        likePost(id, index) {
            axios.post('/api/likePost', {id: id}).then(res => {
                if (res.data.success) {
                    this.posts[index].likes_count = this.posts[index].likes_count + 1
                } else {
                    this.posts[index].likes_count = this.posts[index].likes_count - 1
                }
            }).catch(e => console.log(e))
        },

        infiniteHandler($state) {
            axios.post('/api/getPosts', {}, {
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
    }
}
</script>

<style scoped>
.container {
    padding-top: 25px;
    padding-bottom: 200px;
}

.card {
    width: 100%;
    margin-bottom: 10px;
}


</style>
