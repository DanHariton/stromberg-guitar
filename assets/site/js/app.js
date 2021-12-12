// Vendors -------------------------------------------------------------------------------------------------------------
window.$ = window.jQuery = require('jquery/dist/jquery');
require('bootstrap');
window.Splide = require('@splidejs/splide').default;
window.AOS = require('aos');
AOS.init();


// Components ----------------------------------------------------------------------------------------------------------
require('./contact_tab.js');
require('./image_slider.js');