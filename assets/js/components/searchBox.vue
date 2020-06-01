<template>
    <form action="/product/all" method="GET" class="toolbar_search">
        <div class="row ml-4 mr-3 mx-0">
            <el-input class="w-100" name="searchParam" placeholder="Buscar..." v-model="form.busqueda">
                <i slot="prefix" class="el-input__icon el-icon-search"></i>
            </el-input>
        </div>
    </form>
</template>

<script>
export default {
    data () {
        return {
            currentcategory: {
                default: ""
            },
            form: {},
        }
    },
    computed: {
        searchPlaceholder () {
            if (this.currentcategory) {
                return "Buscar en " + JSON.stringify(this.currentcategory)
            } else {
                return "Buscar"
            }
        }
    }
}
</script>