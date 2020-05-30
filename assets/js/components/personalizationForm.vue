<template>
    <el-dialog :title="header" :visible.sync="dialog" :close-on-click-modal="canClose" :show-close="canClose">
        <el-form ref="form" :model="form" :loading="loading">
            <div class="row">
                <div class="col-12 col-lg-6">
                    <el-form-item label="Nombre">
                        <el-input v-model="form.name" placeholder="Nombre"></el-input>
                    </el-form-item>
                </div>
                <div class="col-12 col-lg-6" v-show="!isSeller">
                    <el-form-item label="Apellido paterno">
                        <el-input v-model="form.father" placeholder="Apellido paterno"></el-input>
                    </el-form-item>
                </div>
                <div class="col-12 col-lg-6" v-show="!isSeller">
                    <el-form-item label="Apellido materno">
                        <el-input v-model="form.mother" placeholder="Apellido materno"></el-input>
                    </el-form-item>
                </div>
                <div class="col-12 col-lg-4">
                    <el-form-item label="Codigo postal">
                        <el-input v-model="form.cp" placeholder="Codigo postal" type="number"></el-input>
                    </el-form-item>
                </div>
                <div class="col-12 col-lg-6">
                    <el-form-item label="Direccion">
                        <el-input v-model="form.address" placeholder="Direccion"></el-input>
                    </el-form-item>
                </div>
                <div class="col-12 col-lg-6">
                    <el-form-item label="Descripcion">
                        <el-input v-model="form.description" placeholder="Descripcion"></el-input>
                    </el-form-item>
                </div>
                <div class="col-12 col-lg-6">
                    <el-form-item label="Celular">
                        <el-input v-model="form.phone" placeholder="Celular" type="number"></el-input>
                    </el-form-item>
                </div>
                <div class='col-12 col-lg-6'>
                    <el-form-item label="Estado">
                        <el-select v-model='form.state' placeholder='Select'>
                            <el-option v-for='state in states' :key='state.id' :label='state._name' :value='state.id'></el-option>
                        </el-select>
                    </el-form-item>
                </div>
                <div class="col-12 col-lg-12">
                    <el-form-item class="mt-lg-3">
                        <el-button type="primary" @click="sendForm">Guardar</el-button>
                        <el-button v-if="canClose" @click="dialog = false">Cancel</el-button>
                    </el-form-item>
                </div>
            </div>
        </el-form>
    </el-dialog>
</template>

<script>
import axios from 'axios';

export default {
    props: {
        canclose: {
            default: false
        },
        statesprop: {
            default: () => {return []}
        },
        submit: {
            default: "/"
        },
        header: {
            default: "Header"
        },
        isseller: {
            defaul: false
        },
        userdata: {
            default: {},
        },
        opened: {
            default: true
        }
    },
    data() {
        return {
            dialog: true,
            loading: false,
            form: {
                father: "",
                mother: "",
                cp: "",
                address: "",
                description: "",
                phone: "",
                state: "",
                name: "",
            },
            states: [],
            user: {},
            canClose: false,
            isSeller: false,
        };
    },
    created() {
        this.states = JSON.parse(this.statesprop);
        this.user = JSON.parse(this.userdata);
        this.dialog = JSON.parse(this.opened);
        this.canClose = JSON.parse(this.canclose);
        this.isSeller = JSON.parse(this.isseller);

        if (this.user.personalization) {
            this.form.name = this.user.personalization.name
            this.form.father = this.user.personalization.father_last_name + ""
            this.form.mother = this.user.personalization.mother_last_name + ""
            this.form.cp = this.user.personalization.shipping_address.postal_code + ""
            this.form.address = this.user.personalization.shipping_address.address + ""
            this.form.description = this.user.personalization.shipping_address.description + ""
            this.form.phone = this.user.personalization.shipping_address.phone + ""
            this.form.state = this.user.personalization.shipping_address.state.id
        }

        console.log("User", this.user)


        window.openPersonalizationForm = () => {
            this.dialog = true
        }
    },
    methods: {
        sendForm () {
            this.$notify({
                title: "Espere",
                type: "info",
                message: "Guardando informacion"
            })
            let formData = new FormData ();
            formData.set("name", this.form.name)
            formData.set("father", this.form.father)
            formData.set("mother", this.form.mother)
            formData.set("cp", this.form.cp)
            formData.set("address", this.form.address)
            formData.set("description", this.form.description)
            formData.set("phone", this.form.phone)
            formData.set("state", this.form.state)
            formData.set("id", this.user.id)
            if (this.user.personalization) {
                formData.set("personalizationID", this.user.personalization.id)
                formData.set("shippingID", this.user.personalization.shipping_address.id)
            }
            
            axios.post(this.submit, formData).then(res => {
                this.$notify({
                    type: "success",
                    title: "Datos guardados",
                    message: "Su configuracion fue guardada"
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
};
</script>