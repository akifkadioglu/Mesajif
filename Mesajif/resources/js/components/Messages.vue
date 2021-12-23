<template>
    <div>
        <app-navbar/>
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    <app-sidebar @username="Changeusername($event)" @image="Changeimage($event)"/>
                </div>
                <div class="col-sm-8">
                    <app-content :username="username" :image="image"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Content from "./Messages/Content";
import Sidebar from "./Messages/Sidebar";
import Navbar from "./partials/Navbar";

export default {
    name: "Messages",
    components: {
        'app-content': Content,
        'app-sidebar': Sidebar,
        'app-navbar': Navbar
    },
    data() {
        return {
            username: "",
            image: ""
        }
    },
    methods: {
        Changeusername(event) {
            this.username = event
        },
        Changeimage(event) {
            this.image = event
        }
    }

}
</script>

<style scoped>

</style>
