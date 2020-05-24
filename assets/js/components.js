import Vue from 'vue'
import ElementUI from 'element-ui';

import Toolbar from './components/toolbar.vue'
import ProductForm from './components/productForm.vue'

Vue.use(ElementUI);

window.Vue = Vue;

new Vue ({
    el: '#app',
    components: {
        'toolbar-component': Toolbar,
        'product-form-component': ProductForm,
    },
    created() {
    }
});