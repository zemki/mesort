import Popper from "popper.js";
import $ from "jquery";
import axios from "axios";
import { useRoute } from 'ziggy-js';


// Make libraries available globally
window.Popper = Popper;
window.$ = window.jQuery = $;

// Configure Axios
window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// Register CSRF Token
let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
} else {
    console.error(
        "CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token"
    );
}

