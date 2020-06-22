<template>
    <div>
        <el-dialog title="Carrito" :visible.sync="dialog" center>
            <!-- {{cart}} -->
            <el-table :data="cart" height="400" style="width: 850px">
                <el-table-column prop="product.title" label="Producto" width="250"></el-table-column>
                <el-table-column prop="product.priceText" label="Precio"></el-table-column>
                <el-table-column prop="quantity" label="Cantidad"></el-table-column>
                <el-table-column prop="total" label="Total"></el-table-column>

                <el-table-column label="Accion">
                <template slot-scope="scope">
                    <el-button size="mini" type="danger" @click="handleDelete(scope.$index, scope.row)">Eliminar</el-button>
                </template>
                </el-table-column>
            </el-table>

            <div class="w-100 d-flex justify-content-center" v-if="cart.length > 0">
                <el-button class="mx-auto" primary @click="buyCart">Realizar compra</el-button>
                <!-- <paypal-button :items="cart"></paypal-button> -->
            </div>
        </el-dialog>  
    </div>
</template>

<script>
import { EventBus } from './eventBus.js';
import axios from 'axios';
import paypal from './paypal'

export default {
    components: {
        'paypal-button': paypal,
    },
    props: {
        cart: {
            default: () => { return [] }
        }
    },
    data () { 
        return {
            dialog: false
        }
    },
    created () {
        EventBus.$on("toggle-cart", () => {
            this.dialog = !this.dialog
        })
        EventBus.$on("paypal-purchased", () => {
            this.buyCart ();
        })
        console.log("cart", this.cart)
    },
    methods: {
        handleDelete (index, row) { 
            this.$notify({
                title: "Removiendo producto",
                type: "info",
                message: "El producto esta siendo eliminado de su carrito",
            })
            let formData = new FormData ();
            formData.set("idProduct", row.id)
            axios.post("/cart/remove", formData).then(() => {
                this.$notify({
                    title: "Producto removido",
                    type: "success",
                    message: "Completado correctamente",
                })
                window.location.reload()
            })
        },
        buyCart () {
            axios.post("/cart/buy").then(() => {
                this.$notify({
                    title: "Productos comprados",
                    type: "success",
                    message: "Se ha registrado su compra",
                })
                window.location.reload()
            }).catch(err => {
                this.$notify({
                    title: "Error",
                    type: "error",
                    message: "Error al registrar su compra",
                })
            })
        }
    }
}
</script>