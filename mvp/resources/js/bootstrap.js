import axios from 'axios';

window.axios = axios;

// CSRFトークンを送信する設定
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
