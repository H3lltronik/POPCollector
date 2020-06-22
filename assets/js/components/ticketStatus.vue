<template>
    <el-dialog title="Informacion del ticket" :visible.sync="dialog">
        <el-form ref="form" :model="form" v-loading="loading">
            <div class="row">
                <div class='col-12 col-lg-12'>
                    <div>Informacion del cliente</div>
                    <div>Direccion: {{form.buyer.personalization.shipping_address.address}}</div>
                    <div>Codigo Postal: {{form.buyer.personalization.shipping_address.postal_code}}</div>
                    <div>Estado: {{form.buyer.personalization.shipping_address.state._name}}</div>
                    <div>Telefono: {{form.buyer.personalization.shipping_address.phone}}</div>
                    <div>Descripcion: {{form.buyer.personalization.shipping_address.description}}</div>
                </div>
                
                <div class='col-12 col-lg-12 mt-3'>
                    <el-form-item label="Status">
                        <el-select v-model='form.status' placeholder='Status'>
                            <el-option label='Proceso' value='Proceso'></el-option>
                            <el-option label='Enviando' value='Enviando'></el-option>
                            <el-option label='Enviado' value='Enviado'></el-option>
                        </el-select>
                    </el-form-item>
                </div>

                <div class="col-12 col-lg-12" v-if="form.status != 'Proceso'">
                    <el-form-item label="Numero de rastreo">
                        <el-input v-model="form.rastreo" placeholder="Numero de rastreo"></el-input>
                    </el-form-item>
                </div>

                <div class="col-12 col-lg-12">
                    <el-form-item class="mt-lg-3">
                        <el-button type="primary" @click="sendForm">Guardar</el-button>
                        <el-button @click="dialog = false">Cancel</el-button>
                    </el-form-item>
                </div>
            </div>
        </el-form>
    </el-dialog>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            dialog: false,
            loading: false,
            form: {
                buyer: {
                    personalization: {
                        shipping_address: {
                            address: "",
                            postal_code: "",
                            state: {
                                _name: "",
                            },
                            phone: "",
                            description: "",
                        }
                    }
                }
            },
        };
    },
    created() {
        window.openTicketStatus = (idTicket) => {
            this.$notify({
                type: "info",
                title: "Cargando ticket",
                message: "Cargando informacion del ticket"
            })
            let formData = new FormData();
            formData.set("idTicket", idTicket)
            axios.post("/ticket", formData).then(res => {
                this.form = JSON.parse(res.data.ticket)
                console.log("Form", this.form)
                this.dialog = true
            }).catch(err => {
                this.$notify({
                    type: "error",
                    title: "Error",
                    message: err
                })
            })
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
            formData.set("idTicket", this.form.id)
            formData.set("status", this.form.status)
            if (this.form.rastreo.length > 0)
                formData.set("rastreo", this.form.rastreo)
            
            axios.post("/ticket/save", formData).then(res => {
                this.$notify({
                    type: "success",
                    title: "Datos guardados",
                    message: "La informacion del ticket fue guardada"
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