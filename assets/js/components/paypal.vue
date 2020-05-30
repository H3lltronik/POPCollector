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
                sandbox: "ATNn6cgvTRJq6nqJLV50CE-fsVD-BZFlielc-d2kU9LFGiwAeHP6jq34y8T85anG5nOIXgDUQ91DYXzk",
                production: "ATNn6cgvTRJq6nqJLV50CE-fsVD-BZFlielc-d2kU9LFGiwAeHP6jq34y8T85anG5nOIXgDUQ91DYXzk",
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