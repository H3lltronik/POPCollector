<template>
    <div class="toolbar_container row mx-0 align-items-center">
        <div class="toolbar_logo h-100 mr-lg-4"><img class="h-100 img-fluid" :src="logo" alt=""></div>
        <form action="" method="GET" class="toolbar_search">
            <div class="row mx-0">
                <el-input class="w-100" name="searchParam" :placeholder="searchPlaceholder" v-model="form.busqueda">
                    <i slot="prefix" class="el-input__icon el-icon-search"></i>
                </el-input>
            </div>
        </form>

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
                <el-badge :value="3" class="item ml-lg-2">
                    <el-button class="normal-button" icon="el-icon-shopping-cart-1" round></el-button>
                </el-badge>
            </div>
        </div>
    </div>
</template>

<script>
export default {
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
    },
    data () {
        return {
            form: {},
            menuOpts: []
        }
    },
    created () {
        this.menuOpts = JSON.parse(this.mainmenuopts)
        console.log("Menu", this.menuOpts)
    },
    methods: {
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