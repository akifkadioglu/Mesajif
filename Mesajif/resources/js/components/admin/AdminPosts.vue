<template>
    <div>
        <app-navbar></app-navbar>
        <div class="container">
            <div
                class="
                    md-layout-item
                    md-medium-size-100
                    md-xsmall-size-100
                    md-size-100
                    move">
                <md-switch v-model="showUsers" style="padding-bottom: 50px" v-if="showUsers">Kullanıcıları kapat
                </md-switch>
                <md-switch v-model="showUsers" style="padding-bottom: 50px" v-if="showUsers!=1">Kullanıcıları aç
                </md-switch>
                <md-card class="md-card-plain" v-if="showUsers">
                    <md-card-header>
                        <h4 class="title">Kullanıcı</h4>
                        <div class="row">
                            <div class="subtitle col-sm-12">
                                <md-field style="width: 80%; margin: auto">
                                    <label>Ara..</label>
                                    <md-input v-model="search" @input="searchFunction"></md-input>
                                </md-field>
                            </div>
                        </div>
                    </md-card-header>
                    <md-card-content>
                        <div class="row">
                            <div class="col-sm-12">
                                <md-table>
                                    <md-table-row>
                                        <md-table-head>Id</md-table-head>
                                        <md-table-head>İsim</md-table-head>
                                        <md-table-head>Kullanıcı adı</md-table-head>
                                        <md-table-head>Görüntüle</md-table-head>
                                    </md-table-row>
                                    <md-table-row v-for="item in searchUser" :key="item.username">
                                        <md-table-cell>{{ item.id }}</md-table-cell>
                                        <md-table-cell>{{ item.name }}</md-table-cell>
                                        <md-table-cell>{{ item.username }}</md-table-cell>
                                        <md-table-cell>
                                            <md-button class="md-icon-button" @click.prevent="getPosts(item.username)">
                                                <md-icon>visibility</md-icon>
                                            </md-button>
                                        </md-table-cell>
                                    </md-table-row>
                                </md-table>
                            </div>
                        </div>
                    </md-card-content>
                </md-card>
                <div class="card mt-2" v-for="(item,index) in posts" :key="index"
                     :style="{'background-color':item.backgroundColor,'color':item.textColor}">
                    <div class="card-body" style=" z-index: 0;">

                        <p class="card-text">{{ item.message }}</p>
                        <md-card-actions>

                            &emsp;
                            {{ item.likes_count }}

                            <md-button
                                style="background-color: white"
                                class="md-icon-button">
                                <md-icon>favorite</md-icon>
                            </md-button>
                            <md-button
                                style="background-color: white"
                                class="md-icon-button"
                                @click.prevent="deletePost(item.id)">
                                <md-icon style="color:red">delete</md-icon>
                            </md-button>
                            &emsp;
                            <small>{{ item.created_at | moment("Do MMMM YYYY, H:mm:ss") }}</small>
                        </md-card-actions>
                    </div>
                </div>

                <infinite-loading spinner="spiral" v-if="posts.length>0" @infinite="infiniteHandler">
                    <div slot="no-more">.</div>
                    <div slot="no-results">.</div>
                </infinite-loading>
            </div>
        </div>

    </div>
</template>

<script>
import Navbar from "./adminPartials/Navbar";

export default {
    name: "AdminPosts",
    components: {
        "app-navbar": Navbar,

    },
    data() {
        return {
            showUsers: true,
            search: "",
            searchUser: [],
            posts: [],
            page: 1,
            username: "",
        }
    }, methods: {
        deletePost(id) {
            this.page = 1
            this.posts = []
            axios.post('/api/adminDeletePost', {id: id, username: this.username}).then(
                this.infiniteHandler
            ).catch(e => console.log(e))
        },
        getPosts(username) {
            this.username = username
            this.page = 1
            this.posts = []
            this.infiniteHandler()
        },
        searchFunction(value) {
            this.search = value
            axios.post('/api/adminSearchUser', {username: this.search}).then(res => this.searchUser = res.data).catch(e => console.log(e))
        },
        infiniteHandler($state) {
            axios.post('/api/adminGetUserPosts', {username: this.username}, {
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
.title {
    background-color: rgb(17, 77, 92);
    color: rgb(255, 255, 255);
    padding: 10px;
    border-radius: 10px;
    margin-top: -40px;
}

.move {
    padding-top: 100px;
}

.subtitle {
    padding-top: 10px;
}
</style>
