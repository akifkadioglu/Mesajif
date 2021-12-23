<template>
    <div>
        <md-switch v-model="sidebar">Sohbetleri Kapat</md-switch>
        <div v-if="sidebar!==true">
            <div class="card">
                <div class="card-header p-3 text-center header md-title">Sohbetler</div>
                <div class="md-scrollbar" style="overflow: hidden scroll; height: 600px;">
                    <div class="user" type="button" v-for="(item,index) in contact" :key="index"
                         @click="sendUsername(item.username,item.image)">
                        <div class="row p-2">
                            <div style="flex: 1">
                                <img v-if="item.image === null" class="md-avatar" src="https://via.placeholder.com/150">
                                <img v-if="item.image !== null" class="md-avatar" :src="getPhoto(item.image)">
                            </div>
                            <div style="flex: 3; margin: auto">
                                <div>{{ item.name }}</div>
                                <small class="text-muted">{{ item.username }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Sidebar",
    data() {
        return {
            sidebar: false,
            contact: {
                type: Array,
                default: []
            }
        }
    },
    methods: {
        getPhoto(path) {
            return "/storage/" + path;
        },
        sendUsername(username, image) {
            this.$emit('username', username)
            this.$emit('image', image)
        }
    },
    mounted() {
        axios.post('/api/contacts').then(res => this.contact = res.data).catch(e => console.log(e))
    },
}
</script>

<style scoped>
.user {
    background-color: rgba(255, 255, 255, 0.00);
    transition-duration: 0.1s;
}

.user:hover {
    background-color: #f1f1f1;
}

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
