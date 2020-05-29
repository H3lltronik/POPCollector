<template>
    <el-dialog :title="header" :visible.sync="dialog" :close-on-click-modal="canclose" :show-close="canclose">
        <el-form ref="form" :model="form" :loading="loading">
            <div class="row">
                <div class="col-12 col-lg-6">
                    <el-form-item label="Nombre">
                        <el-input v-model="form.name" placeholder="Nombre"></el-input>
                    </el-form-item>
                </div>
                <div class="col-12 col-lg-6" v-if="!isseller">
                    <el-form-item label="Apellido paterno">
                        <el-input v-model="form.father" placeholder="Apellido paterno"></el-input>
                    </el-form-item>
                </div>
                <div class="col-12 col-lg-6" v-if="!isseller">
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
                        <el-button v-if="canclose" @click="dialog = false">Cancel</el-button>
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
        }
    },
    data() {
        return {
            dialog: true,
            loading: false,
            form: {},
            states: [],
        };
    },
    created() {
        this.states = JSON.parse(this.statesprop);
    },
    methods: {
        sendForm () {
            let formData = new FormData ();
            formData.set("name", this.form.name)
            formData.set("father", this.form.father)
            formData.set("mother", this.form.mother)
            formData.set("cp", this.form.cp)
            formData.set("address", this.form.address)
            formData.set("description", this.form.description)
            formData.set("phone", this.form.phone)
            formData.set("state", this.form.state)
            
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