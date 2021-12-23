window._ = require('lodash');

try {
    require('bootstrap');
} catch (e) {
}

window.axios = require('axios');
if (JSON.parse(localStorage.getItem('appState'))) {
    $userData = JSON.parse(localStorage.getItem('appState')).user
    window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + JSON.parse(localStorage.getItem('appState')).user.access_token;
    $isAdmin = JSON.parse(localStorage.getItem('appState')).user.is_admin;
}
