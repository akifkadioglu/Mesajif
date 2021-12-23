<template>
    <div>
        <app-navbar></app-navbar>
        <div class="container">
            <div class="card mb-2"
                 :style="{'background-color':post.backgroundColor,'color':post.textColor}">
                <div class="card-body" style=" z-index: 0;">

                    <h5 class="card-title">{{ post.user.name }}</h5>
                    <div style="font-size: 12px" class="card-subtitle mb-4">{{ post.user.username }}</div>
                    <md-divider></md-divider>
                    <p class="card-text">{{ post.message }}</p>

                    <md-card-actions>
                        <input :style="{'background-color':post.backgroundColor,'color':post.backgroundColor}"
                               style="border: 0;text-align: left" ref="button" disabled
                               :value="'https://mesajif.com/'+post.user.username+'/'+post.id">

                        {{ post.likes_count }}
                        &emsp;
                        <md-button @click.prevent="likePost(post.id)" v-if="post.likes_count > 0"
                                   style="background-color: white" class="md-icon-button">
                            <md-icon style="color: red;">favorite</md-icon>
                        </md-button>
                        <md-button @click.prevent="likePost(post.id)" v-if="post.likes_count === 0"
                                   style="background-color: white"
                                   class="md-icon-button">
                            <md-icon>favorite</md-icon>
                        </md-button>
                        &emsp;
                        <small>{{ post.created_at | moment("Do MMMM YYYY, H:mm:ss") }}</small>
                    </md-card-actions>
                </div>
            </div>
        </div>
        <md-dialog :md-active.sync="nullPost">
            <md-dialog-title>Böyle bir gönderi yok!</md-dialog-title>

            <md-tabs md-dynamic-height>
                <md-tab md-label="Hata!">
                    <p>Sizi Ana sayfaya yönlendiriyoruz</p>
                </md-tab>
            </md-tabs>
                <md-dialog-actions>
                    <md-button :to="{name:'home'}" class="md-primary" @click="nullPost = false">Tamam</md-button>
                </md-dialog-actions>

        </md-dialog>
        <app-footer></app-footer>
    </div>
</template>

<script>
import Footer from "./partials/Footer";
import Navbar from "./partials/Navbar";

export default {
    name: "Post",
    data() {
        return {
            nullPost: false,
            post: {
                user: {}
            },
            id: 0
        }
    },
    components: {
        'app-footer': Footer,
        'app-navbar': Navbar,
    }, mounted() {
        this.id = this.$route.params.id;
        this.getPost(this.id, this.$route.params.username)
    },
    watch: {
        '$route.params.id'(newID, oldId) {
            this.getPost(newID, this.$route.params.username)
        }

    }, methods: {
        getPost(id, username) {
            axios.post('/api/getPost', {
                id: id,
                username: username
            }).then(res => {
                if (res.data != false) {
                    this.post = res.data
                } else {
                    this.nullPost = true
                }
            }).catch(e => console.log(e));
        },
        likePost(id) {
            axios.post('/api/likePost', {id: id}).then(res => {
                if (res.data.success) {
                    this.post.likes_count = this.post.likes_count + 1
                } else {
                    this.post.likes_count = this.post.likes_count - 1
                }
            }).catch(e => console.log(e))
        }
    }
}
</script>

<style scoped>

</style>
