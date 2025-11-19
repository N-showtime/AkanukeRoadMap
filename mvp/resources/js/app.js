import './bootstrap';

// TailwindCSS は Vite プラグインで読み込まれるので何もしなくてOK

// Alpine.js（CDNではなくローカルで管理したい場合）
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// Flatpickr（JSのみ、CSSはapp.cssで管理する）
import flatpickr from "flatpickr";
window.flatpickr = flatpickr;

// Livewire（必要なら）
// import 'livewire-turbolinks';
