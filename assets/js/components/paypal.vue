<template>
    <paypal-component :amount="`${total}`" :items="myItems" currency="MXN" :client="paypalCredentials" env="sandbox"
    v-on:payment-authorized="handleAuthorized"
    v-on:payment-completed="handleCompleted"
    v-on:payment-cancelled="handleCancelled" v-if="showPaypal"/>
</template>

<script>
import { EventBus } from './eventBus.js';
import paypal from 'vue-paypal-checkout'

export default {
    components: {
        "paypal-component": paypal,
    },
    props: {
        items: {
            type: Array,
            default: () => {return []}
        }
    },
    data () {
        return {
            paypalCredentials: {
                sandbox: "AU7cr3IdrX9ivZpZHP2bT3BkuZy0LE5VIj2zj30in7gmDDdq0MhvxDx9kyFZGmNDIyHzXwh6l_4LlTlK",
                production: "AU7cr3IdrX9ivZpZHP2bT3BkuZy0LE5VIj2zj30in7gmDDdq0MhvxDx9kyFZGmNDIyHzXwh6l_4LlTlK",
            },
            showPaypal: true,
        }
    },
    mounted () {
        setTimeout(() => {
            console.log("Paypal info", this.myItems, this.total)
        }, 1000);
    },
    methods: {
        handleAuthorized (value) {
            console.log("Authorized", value, this.myItems)
        },
        handleCompleted (value) {
            if (!value.payer) {
                alert("La compra fallo");
                return
            }
            if (value.payer.status == "VERIFIED") {
                let precio = value.transactions[0].amount.total;
                console.log("Comprado")
                EventBus.$emit("paypal-purchased");
            }
        },
        handleCancelled (value) {
        },
    },
    computed: {
        myItems () {
            let items = this.items;
            return items.map(item => {
                return {
                    name: item.product.title,
                    description: item.product.description,
                    quantity: item.quantity,
                    price: item.product.price,
                    currency: "MXN"
                }
            })
        },
        total () {
            let items = this.items;
            let total = 0;
            items.forEach(item => {
                total += item.totalPrice
            });
            return total
        }
    }
}
</script>