import Vue from 'vue'
import ElementUI from 'element-ui';

import Toolbar from './components/toolbar.vue'

Vue.use(ElementUI);

window.Vue = Vue;

new Vue ({
    el: '#app',
    components: {
        'toolbar-component': Toolbar,
    },
    created() {
    }
});