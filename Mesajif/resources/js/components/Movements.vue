<template>
    <div>
        <app-navbar></app-navbar>
        <div class="container">

            <md-field v-for="(item,index) in requests" :key="index">
                <div class="navbar" style="width: 100%;">
                    <div style="font-size: 17px"><img
                        style="border-radius:100%; width: 35px" src="https://via.placeholder.com/250">{{
                            item.user.name
                        }} <small>(<i>{{ item.user.username }}</i>)</small> arkadaşınız olmak istiyor.
                    </div>
                    <div>
                        <md-button style="background-color: #008286; color: white" @click.prevent="accept(item.id)">
                            Kabul Et
                        </md-button>
                        <md-button style="background-color: #940000; color: white" @click.prevent="reject(item.id)">
                            Reddet
                        </md-button>
                    </div>
                </div>
            </md-field>
        </div>
        <app-footer></app-footer>
    </div>
</template>

<script>
import Footer from "./partials/Footer";
import Navbar from "./partials/Navbar";

export default {
    name: "Movements",
    components: {
        'app-footer': Footer,
        'app-navbar': Navbar,
    },
    data() {
        return {
            requests: []
        }
    },
    mounted() {
        axios.post('/api/getRequests').then(res => this.requests = res.data).catch(e => console.log(e))
    },
    methods: {
        accept(id) {
            axios.post('/api/acceptFriend', {id: id}).then(res => this.requests = res.data).catch(e => console.log(e))
        },
        reject(id) {
            axios.post('/api/rejectFriend', {id: id}).then(res => this.requests = res.data).catch(e => console.log(e))
        }
    }
}
</script>

<style scoped>

</style>
