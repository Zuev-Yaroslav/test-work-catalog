<script setup lang="ts">
import {onMounted, reactive, ref, watch} from "vue";
import MainLayout from "@/layouts/MainLayout.vue";
import PaginationComponent from "@/components/Pagination/PaginationComponent.vue";
import {usePage} from "@inertiajs/vue3";
import ProductItem from "@/components/Product/ProductItem.vue";
import ProductFilterComponent from "@/components/Product/ProductFilterComponent.vue";
import {filter, refreshProducts} from "@/utils/product/productMethods";
import {getCategories} from "@/utils/product/categoryMethods";

const products = ref([]);
const categories = ref([]);
const filterData = reactive({
    page: route().params.page || null,
    category_id: route().params.category_id || null,
    search: route().params.search || null,
});

onMounted(() => {
    refreshProducts(products, filterData);
    getCategories(categories);
})
watch(() => usePage().url,
    (newVal) => {
        refreshProducts(products, filterData);
    },
    { flush: 'post' }
);
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

