<script setup lang="ts">
import {onMounted, ref} from "vue";
import MainLayout from "@/layouts/MainLayout.vue";
import api from "@/axios/api";
import {Link, router} from "@inertiajs/vue3"
import {getProduct} from "@/utils/product/productMethods";

defineOptions({ layout: MainLayout });
const product = ref({});
const errorObject = ref(null);


onMounted(() => {
    getProduct(product, route().params.id, errorObject);
});
</script>

<template>
    <div v-if="!errorObject" class="mb-4 border-b">
        <h1 class="text-3xl mb-5 font-semibold">Продукт</h1>
        <div class="mb-3">
            <h2 class="text-1xl font-semibold">Название продукта</h2>
            <p>{{ product.name }}</p>
        </div>
        <div class="mb-3">
            <h2 class="text-1xl font-semibold">Категория</h2>
            <p>{{ product.category?.name }}</p>
        </div>
        <div class="ml-4 mb-5">
            <h2 class="text-1xl font-semibold">Описание категории</h2>
            <p>{{ product.category?.description }}</p>
        </div>
        <div class="mb-3">
            <h2 class="text-1xl font-semibold">Цена</h2>
            <p>{{ product.price }} &#8381;</p>
        </div>
        <div class="mb-3">
            <h2 class="text-1xl font-semibold">Описание продукта</h2>
            <p>{{ product.description }}</p>
        </div>
    </div>
    <h1 v-else class="text-3xl mb-4">{{ errorObject.response.status }} {{ ' ' }} {{ errorObject.response.statusText }}</h1>
    <Link :href="route('main')" class="text-blue-500">Назад</Link>
</template>

<style scoped>

</style>

