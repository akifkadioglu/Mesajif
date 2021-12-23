<template>
    <div>
        <md-toolbar class="md-primary">
            <md-button class="md-icon-button" @click="showNavigation = true">
                <md-button
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        fill="currentColor"
                        class="bi bi-activity"
                        viewBox="0 0 16 16"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M6 2a.5.5 0 0 1 .47.33L10 12.036l1.53-4.208A.5.5 0 0 1 12 7.5h3.5a.5.5 0 0 1 0 1h-3.15l-1.88 5.17a.5.5 0 0 1-.94 0L6 3.964 4.47 8.171A.5.5 0 0 1 4 8.5H.5a.5.5 0 0 1 0-1h3.15l1.88-5.17A.5.5 0 0 1 6 2Z"
                        />
                    </svg
                    >
                </md-button>
            </md-button>
            <div style="margin: auto">
                <span class="md-title">Mesajif Yönetim Paneli</span>
            </div>
        </md-toolbar>

        <md-drawer :md-active.sync="showNavigation" md-swipeable>
            <md-toolbar class="md-transparent" md-elevation="0">
                <span class="md-title">Mesajif</span>
            </md-toolbar>

            <md-list>
                <md-list-item>
                    <router-link :to="{name:'adminUsers'}">
                        <span class="md-list-item-text">Kullanıcılar</span>
                    </router-link>
                </md-list-item>

                <md-list-item>
                    <router-link :to="{name:'adminPosts'}">
                        <span class="md-list-item-text">Gönderiler</span>
                    </router-link>
                </md-list-item>

                <md-list-item>
                    <router-link :to="{name:'adminSettings'}">
                        <span class="md-list-item-text">Site Ayarları</span>
                    </router-link>
                </md-list-item>


            </md-list>
        </md-drawer>
    </div>
</template>

<script>
export default {
    name: "Navbar",
    data() {
        return {
            showNavigation: false,
        };
    },
}
</script>

<style scoped>

</style>
