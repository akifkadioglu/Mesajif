import Home from './components/Home'
import Movements from "./components/Movements";
import LoginRegister from "./components/LoginRegister";
import Profile from "./components/Profile";
import Post from "./components/Post";
import SettingPage from "./components/SettingPage";
import AdminUsers from "./components/admin/AdminUsers";
import AdminPosts from "./components/admin/AdminPosts";
import Settings from "./components/admin/Settings";
import Messages from "./components/Messages";


export default {
    mode: 'history',
    routes: [

        {
            path: '/yonetim/ayarlar',
            name: 'adminSettings',
            component: Settings,
            beforeEnter: (to, from, next) => {
                if ($isAdmin!=1) {
                    next(false);
                } else {
                    next();
                }
            }
        },
        {
            path: '/yonetim/kullanicilar',
            name: 'adminUsers',
            component: AdminUsers,
            beforeEnter: (to, from, next) => {
                if ($isAdmin!=1) {
                    next(false);
                } else {
                    next();
                }
            }
        },
        {
            path: '/yonetim/gonderiler',
            name: 'adminPosts',
            component: AdminPosts,
            beforeEnter: (to, from, next) => {
                if ($isAdmin!=1) {
                    next(false);
                } else {
                    next();
                }
            }
        },
        {
            path: '/',
            name: 'home',
            component: Home
        },
        {
            path: '/hareketler',
            name: 'movements',
            component: Movements
        },
        {
            path: '/mesajlar',
            name: 'messages',
            component: Messages
        },
        {
            path: '/giris',
            name: 'loginRegister',
            component: LoginRegister
        },
        {
            path: '/hesap/:username',
            name: 'profile',
            component: Profile
        },
        {
            path: '/:username/:id',
            name: 'post',
            component: Post
        },
        {
            path: '/ayarlar',
            name: 'setting',
            component: SettingPage
        },

    ]
}
