<template>
    <div>
        <md-switch v-model="content">Mesajları Kapat</md-switch>
        <div v-if="content!==true">
            <div class="card">
                <div class="card-header p-3 navbar header md-title">
                    <div>
                        <img v-if="image!==null" class="md-avatar" :src="getPhoto(image)">
                    </div>
                    <div>{{ username ? username : 'Bir Kullanıcı Seçiniz' }}</div>
                    <div></div>

                </div>


                <div class="md-scrollbar" ref="feed" style="overflow: hidden scroll; height: 600px;">

                    <div style="padding-bottom: 80px">
                        <div class="row" v-for="(item,index) in messages" :key="index">
                            <div class="col-sm-12" v-if="item.from !== user.id">
                                <div class="my-3 mb-3"
                                     style="min-width: 0; padding: 10px;border-radius:15px 15px 15px 0; background-color:#154f69;color: white; max-width:75%;float: left ">
                                    <div style="width:100%;word-break: break-word;display: inline-block;">
                                        <md-tooltip md-direction="right">{{ item.created_at | moment("Do MMMM YYYY, H:mm:ss") }}</md-tooltip>
                                        {{ item.message }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12" v-if="item.from === user.id">
                                <div class="my-3 mb-3"
                                     style="min-width: 0; padding: 10px;border-radius:15px 15px 0 15px; background-color:#595959;color: white; max-width:75%;float:right; ">
                                    <div style="width:100%;word-break: break-word;display: inline-block;">
                                        {{ item.message }}
                                        <md-tooltip md-direction="left">{{ item.created_at | moment("Do MMMM YYYY, H:mm:ss") }}</md-tooltip>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="username" style="position:absolute; bottom: 0; width: 100%" class="bg-white">
                        <md-field style="margin-left: 5%; width: 90%">
                            <label>Mesaj</label>
                            <md-textarea v-model="message" md-autogrow @keydown.enter="sendMessage"></md-textarea>
                            <md-button v-if="message!==''" class="md-icon-button" @click.prevent="sendMessage">
                                <md-icon style="color: #1d5a9a;">send</md-icon>
                            </md-button>
                            <md-button disabled v-if="message===''" class="md-icon-button" @click.prevent="sendMessage">
                                <md-icon style="color: #ff0000;">send</md-icon>
                            </md-button>
                        </md-field>
                    </div>
                </div>


            </div>
        </div>
    </div>
</template>

<script>
import  io from  'socket.io-client';
var socket = io('http://localhost:3000');

export default {
    name: "Content",
    props: {
        username: {
            type: String,
            default: null
        },
        image: {
            type: String,
            default: null
        },
    },
    created()
    {
        socket.emit('hello');
    },
    data() {
        return {
            content: false,
            message: "",
            messages: [],
            user: $userData
        }
    },

    watch: {
        'username'(newUsername, oldUsername) {
            this.username = newUsername
            axios.post('/api/messages', {username: this.username}).then(res => {
                this.messages = res.data
                this.scrollToBottom()
            }).catch(e => console.log(e));
        },
        'image'(newImage, oldImage) {
            this.getPhoto(newImage)
        }
    },

    methods: {
        scrollToBottom() {
            setTimeout(() => {
                this.$refs.feed.scrollTop = this.$refs.feed.scrollHeight - this.$refs.feed.clientHeight;
            }, 1)
        }
        ,
        getPhoto(image) {
            return "/storage/" + image;
        },
        sendMessage() {
            if (this.message.trim() === "") {
                return
            }
            axios.post('/api/sendMessage', {
                username: this.username,
                message: this.message
            }).then(res => {
                    this.messages.push({
                        created_at: res.data.created_at,
                        from: res.data.from,
                        id: res.data.id,
                        message: res.data.message,
                        to: res.data.to,
                        updated_at: res.data.updated_at
                    })
                this.scrollToBottom()
                }
            )
                .catch(e => console.log(e))
            this.message = ""
        }
    }
}
</script>

<style scoped>
.header {
    background-image: linear-gradient(
        45deg,
        rgb(255, 122, 122),
        rgb(255, 234, 118),
        rgb(126, 255, 126),
        rgb(112, 229, 232)
    );
    background-size: 500%;
    animation: bg-animation 5s infinite alternate;
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
