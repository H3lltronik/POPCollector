<template>
    <div>
        <el-form ref='form' :model='form'>
            <div class='row'>
                <div class='col-6'>
                    <el-select v-model='recommended' placeholder='Select'>
                        <el-option v-for='product in products' :key='product.id' :label='product.title' :value='product.id'></el-option>
                    </el-select>
                </div>
                <div class='col-6'>
                    <button id='submit' type='button' class='btn btn-primary mb-2' @click="recommendProduct">Recomendar producto</button>
                </div>
            </div>
        </el-form>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: {
        productsprops: {
            default: () => { return [] },
        },
        addrecommend: {
            default: "/",
        },
        sebuscaid: {
            default: 0,
        }
    },
    data () {
        return {
            form: {},
            products: [],
            recommended: null,
        }
    },
    created () {
        this.products = JSON.parse(this.productsprops);
    },
    methods: {
        recommendProduct () {
            let formData = new FormData ();
            if (!this.recommended) {
                this.$notify({
                    type: "error",
                    title: "Error",
                    message: "Seleccione un producto a recomendar"
                })
                return;
            }
            formData.set("recommended", this.recommended)
            formData.set("sebuscaid", this.sebuscaid)
            
            axios.post(this.addrecommend, formData).then(res => {
                this.$notify({
                    type: "success",
                    title: "Recomendacion añadida",
                    message: "Su recomendacion fue enviada"
                })
                window.location.reload ();
            }).catch(err => {
                this.$notify({
                    type: "error",
                    title: "Error",
                    message: err
                })
            })
        }
    }
}
</script>