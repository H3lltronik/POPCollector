<template>
    <div class="toolbar">
        <div class="container px-0">
            <div class="toolbar_container row mx-0 align-items-center">
                <div class="toolbar_logo h-100 mr-lg-4"><img class="h-100 img-fluid" src="images/magiarecord_logo-chido.png" alt=""></div>
                <el-form ref="form" :model="form" class="toolbar_search">
                    <div class="row mx-0">
                        <el-form-item class="my-0 w-100">
                            <el-input class="w-100" placeholder="Buscar" v-model="form.busqueda">
                                <i slot="prefix" class="el-input__icon el-icon-search"></i>
                            </el-input>
                        </el-form-item>
                    </div>
                </el-form>

                <div class="flex-grow-1"></div>

                <div class="d-flex">
                    <el-link :underline="false" class="mr-lg-3" primary href="login" v-if="!user"><i class="el-icon-sell el-icon--left"></i>Login</el-link>
                    <div class="d-flex" v-else>
                        <div class="d-flex align-items-center">
                            <el-dropdown type="primary" class="normal-button" trigger="hover">
                                <el-button class="normal-button" type="primary" icon="el-icon-user-solid" round></el-button> 
                                <el-dropdown-menu slot="dropdown">
                                    <el-dropdown-item>Cuenta</el-dropdown-item>
                                    <el-link :underline="false" :href="logout">
                                        <el-dropdown-item>Cerrar sesion</el-dropdown-item>
                                    </el-link>
                                </el-dropdown-menu>
                            </el-dropdown>
                        </div>
                        <el-badge :value="3" class="item ml-lg-2">
                            <el-button class="normal-button" icon="el-icon-shopping-cart-1" round></el-button>
                        </el-badge>
                    </div>
                </div>
            </div>

            <div class="toolbar_submenu_container mx-0">
                <ul class="toolbar_submenu">
                    <a href=""><li>Inicio</li></a>
                    <a href=""><li>Libros</li></a>
                    <a href=""><li>Peliculas</li></a>
                    <a href=""><li>Series</li></a>
                    <a href=""><li>CD's</li></a>
                    <a href=""><li>Vinilos</li></a>
                    <a href=""><li>Se busca</li></a>
                    <a href=""><li>Contacto</li></a>
                </ul>
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
            default: "/login"
        },
        logout: {
            default: "/logout"
        },
    },
    data () {
        return {
            form: {},
        }
    }
}
</script>