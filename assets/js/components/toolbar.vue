<template>
    <div class="toolbar_container row mx-0 align-items-center">
        <div class="toolbar_logo h-100 mr-lg-4"><img class="h-100 img-fluid" :src="logo" alt=""></div>
        <search-component></search-component>

        <div class="flex-grow-1"></div>

        <div class="d-flex">
            <el-link :underline="false" class="mr-lg-3" primary :href="login" v-if="!user"><i class="el-icon-sell el-icon--left"></i>Login</el-link>
            <div class="d-flex" v-else>
                <div class="d-flex align-items-center">
                    <el-dropdown type="primary" class="normal-button" trigger="hover">
                        <el-button class="normal-button" type="primary" icon="el-icon-user-solid" round></el-button> 
                        <el-dropdown-menu slot="dropdown">
                            <el-dropdown-item v-for="(opt, index) in menuOpts" :key="index">
                                <el-link :underline="false" :href="opt.href" >
                                    {{opt.text}}
                                </el-link>
                            </el-dropdown-item>                                
                        </el-dropdown-menu>
                    </el-dropdown>
                </div>
                <el-badge :value="cart.length" class="item ml-lg-2">
                    <el-button class="normal-button" icon="el-icon-shopping-cart-1" round @click="toggleCart"></el-button>
                </el-badge>
            </div>
        </div>

        <cart-component :cart="cart"></cart-component>
    </div>
</template>

<script>
import Cart from './cart'
import SearchBox from './searchBox'
import { EventBus } from './eventBus.js';

export default {
    components: {
        'cart-component': Cart,
        'search-component': SearchBox,
    },
    props: {
        user: {
            default: null
        },
        login: {
            default: "/asd"
        },
        mainmenuopts: {
            default: () => {return []},
        },
        logo: {
            default: "images/magiarecord_logo-chido.png"
        },
        currentcategory: {
            default: null
        },
        cartprop: {
            default: () => { return [ ]}
        }
    },
    data () {
        return {
            form: {},
            menuOpts: [],
            cart: [],
        }
    },
    created () {
        this.menuOpts = JSON.parse(this.mainmenuopts)
        this.cart = JSON.parse(this.cartprop)
        console.log("Menu", this.menuOpts)
    },
    methods: {
        toggleCart () {
            EventBus.$emit("toggle-cart");
        }
    },
    computed: {
        searchPlaceholder () {
            if (this.currentcategory) {
                return "Buscar en " + this.currentcategory
            } else {
                return "Buscar"
            }
        }
    }
}
</script>