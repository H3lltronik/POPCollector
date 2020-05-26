import Vue from 'vue'
import ElementUI from 'element-ui';

import Toolbar from './components/toolbar.vue'
import ProductForm from './components/productForm.vue'
import SeBuscaForm from './components/seBuscaForm.vue'
import RecommendForm from './components/recommendForm.vue'
import PersonalizationForm from './components/personalizationForm.vue'

Vue.use(ElementUI);

window.Vue = Vue;

new Vue ({
    el: '#app',
    components: {
        'toolbar-component': Toolbar,
        'product-form-component': ProductForm,
        'se-busca-form-component': SeBuscaForm,
        'recommend-form-component': RecommendForm,
        'personalization-form-component': PersonalizationForm,
    },
    created() {
    }
});