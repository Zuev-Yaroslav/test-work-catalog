<script setup lang="ts">
import {getCurrentInstance, onMounted, onUnmounted, reactive, ref} from "vue";
import MainLayout from "@/layouts/MainLayout.vue";
import api from "@/axios/api";
import PaginationComponent from "@/components/Pagination/PaginationComponent.vue";
import {router, Link} from "@inertiajs/vue3";
import ProductItem from "@/components/Product/ProductItem.vue";
import ProductFilterComponent from "@/components/Product/ProductFilterComponent.vue";
import { getProducts, filter } from "@/utils/product/productMethods";
import {getCategories} from "@/utils/product/categoryMethods";

const products = ref([]);
const categories = ref([]);
const filterData = reactive({
    page: route().params.page || null,
    category_id: route().params.category_id || null,
    search: route().params.search || null,
});

onMounted(() => {
    getProducts(products);
    getCategories(categories);
})
const removeFinishEventListener = router.on('finish', () => {
    getProducts(products);
})
onUnmounted(() => {
    removeFinishEventListener();
})
defineOptions({ layout: MainLayout });
</script>

<template>
    <div>
        <div class="border-b-3 border-gray-300 mb-8">
            <ProductFilterComponent
                @filter="filter(filterData, $event)"
                :categories="categories"
                :filter-data="filterData"
            />
            <ProductItem
                v-for="product in products.data"
                :product="product"
            />
        </div>

    </div>
    <PaginationComponent
        v-if="products.meta && products.links"
        :meta="products.meta"
        :filter-data="filterData"
        route-name="main"
    />
</template>

<style scoped>

</style>

