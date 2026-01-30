<script setup lang="ts">

import PaginationComponent from "@/components/Pagination/PaginationComponent.vue";
import ProductFilterComponent from "@/components/Product/ProductFilterComponent.vue";
import ProductItem from "@/components/Product/ProductItem.vue";
import {
    filter,
    destroyProduct,
    forceDestroyProduct,
    refreshProducts
} from "@/utils/product/productMethods";
import {onMounted, onUnmounted, reactive, ref, watch} from "vue";
import MainLayout from "@/layouts/MainLayout.vue";
import {usePage} from "@inertiajs/vue3";
import {getCategories} from "@/utils/product/categoryMethods";
import DefaultButton from "@/components/DefaultButton.vue";

const products = ref([]);
const categories = ref([]);
const filterData = reactive({
    page: route().params.page || null,
    category_id: route().params.category_id || null,
    search: route().params.search || null,
    only_trashed: (route().params.only_trashed === 'true') || null
});
const handleDestroyProduct = (id) => destroyProduct(id, products, filterData);
const handleForceDestroyProduct = (id) => forceDestroyProduct(id, products, filterData);
const handleRestoreProduct = (id) => forceDestroyProduct(id, products, filterData);

onMounted(() => {
    refreshProducts(products, filterData);
    getCategories(categories);
})
watch(() => usePage().url,
    (newVal) => {
        refreshProducts(products, filterData)
    },
    { flush: 'post' }
);
defineOptions({ layout: MainLayout });
</script>

<template>
    <div class="border-b-3 border-gray-300 mb-8">
        <ProductFilterComponent
            class="border-b mb-5"
            @filter="filter(filterData, $event)"
            :categories="categories"
            :filter-data="filterData"
            :is-admin="true"
        />
        <DefaultButton
            :linkable="true"
            class="mb-5 bg-blue-600 hover:bg-blue-700 focus:ring-blue-300 text-white"
            :href="route('admin.products.create')"
        >
            Создать продукт
        </DefaultButton>
        <div>
            <ProductItem
                v-for="product in products.data"
                :product="product"
                :is-admin="true"
                @destroy-product="handleDestroyProduct(product.id)"
                @force-destroy-product="handleForceDestroyProduct(product.id)"
                @restore-product="handleRestoreProduct(product.id)"
            />
        </div>
    </div>
    <PaginationComponent
        v-if="products.meta && products.links"
        :meta="products.meta"
        :filterData="filterData"
        route-name="admin.products.index"
    />
</template>

<style scoped>

</style>
