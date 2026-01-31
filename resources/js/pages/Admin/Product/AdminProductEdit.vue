<script setup lang="ts">
import {Link} from "@inertiajs/vue3"
import {computed, onMounted, ref} from "vue";

import DefaultButton from "@/components/DefaultButton.vue";
import DefaultInput from "@/components/DefaultInput.vue";
import DefaultSelect from "@/components/DefaultSelect.vue";
import DefaultTextarea from "@/components/DefaultTextarea.vue";
import MainLayout from "@/layouts/MainLayout.vue";
import {getCategories} from "@/utils/product/categoryMethods";
import {getProduct, updateProduct} from "@/utils/product/productMethods";

defineOptions({ layout: MainLayout });
const product = ref({});
const categories = ref([]);
const errorValidation = ref({});
const errorObject = ref(null);

const getCategoryDescriptionById = computed(() => {
    const category = categories.value.find((category => category.id === product.value.category_id))
    return category?.description;
})
const handleUpdateProduct = () => updateProduct(product, errorValidation);
onMounted(() => {
    getProduct(product, route().params.id, errorObject);
    getCategories(categories)
})
</script>

<template>
    <div v-if="!errorObject" class="mb-4 border-b">
        <h1 class="text-3xl mb-8 font-semibold">Редактирование продукта</h1>
        <div class="mb-5">
            <label for="name" class="font-semibold mb-3">Название продукта</label>
            <DefaultInput
                id="name"
                class="w-full"
                v-model="product.name"
            />
            <div class="text-red-500 text-sm">
                {{ errorValidation.errors?.name?.[0] || '&nbsp;' }}
            </div>
        </div>
        <div class="mb-5">
            <label for="category_id" class="font-semibold">Категория</label>
            <default-select
                class="w-full"
                id="category_id"
                :items="categories"
                key-value="id"
                key-name="name"
                v-model="product.category_id"
            />
            <div class="text-red-500 text-sm">
                {{ errorValidation.errors?.category_id?.[0] || '&nbsp;' }}
            </div>
        </div>
        <div v-if="getCategoryDescriptionById" class="ml-4 mb-5">
            <h2 class="text-1xl font-semibold">Описание категории</h2>
            <p>{{ getCategoryDescriptionById }}</p>
        </div>
        <div class="mb-5">
            <label for="price" class="font-semibold">Цена</label>
            <default-input
                class="w-full"
                id="price"
                type="number"
                min="0"
                v-model="product.price"
            />
            <div class="text-red-500 text-sm">
                {{ errorValidation.errors?.price?.[0] || '&nbsp;'}}
            </div>
        </div>
        <div class="mb-5">
            <label class="font-semibold">Описание продукта</label>
            <DefaultTextarea
                v-model="product.description"
                class="w-full"
                rows="5"
            />
            <div class="text-red-500 text-sm">
                {{ errorValidation.errors?.description?.[0] || '&nbsp;' }}
            </div>
        </div>
        <DefaultButton
            type="button"
            @click="handleUpdateProduct"
            class="text-white bg-green-600 hover:bg-green-700 focus:ring-green-300 mb-5"
        >
            Сохранить изменения
        </DefaultButton>
    </div>
    <h1 v-else class="text-3xl mb-4">{{ errorObject.response.status }} {{ ' ' }} {{ errorObject.response.statusText }}</h1>
    <Link :href="route('admin.products.index')" class="text-blue-500">Назад</Link>
</template>

<style scoped>

</style>

