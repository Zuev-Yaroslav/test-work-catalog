<script setup lang="ts">
import {Link} from "@inertiajs/vue3";
import {computed, ref} from "vue";
import { PencilSquareIcon, TrashIcon, XMarkIcon, ArrowUturnLeftIcon } from '@heroicons/vue/24/solid'
import DefaultButton from "@/components/DefaultButton.vue";
import Dialog from "primevue/dialog";
import {restoreProduct} from "@/utils/product/productMethods";

const props = defineProps({
    product: Object,
    isAdmin: Boolean,
});


const shortDescription = computed((): string => {
    const limit: number = 40;
    return props.product.description.length > limit
        ? props.product.description.slice(0, limit) + '...'
        : props.product.description;
});
const showDestroyModal = ref(false);
const showForceDestroyModal = ref(false);
const emits = defineEmits([
    'destroyProduct',
    'forceDestroyProduct',
])
</script>

<template>
    <div class="mb-4 border-b" :class="(isAdmin) ? 'flex justify-between' : 'block'">
        <div >
            <Link v-if="isAdmin && !product.is_trashed" :href="route('admin.products.edit', product.id)" class="flex gap-2 text-2xl items-center text-blue-500">
                <span>{{ product.name }}</span>
                <PencilSquareIcon class="w-[15px] h-[15px]"></PencilSquareIcon>
            </Link>
            <span v-else-if="isAdmin && product.is_trashed" class="text-2xl">{{ product.name }}</span>
            <Link v-else :href="route('products.show', product.id)" class="text-2xl text-blue-500">{{ product.name }}</Link>

            <p>Категория: {{ product.category.name }}</p>
            <p>Цена: {{ product.price }} &#8381;</p>
            <p>{{ shortDescription }}</p>
        </div>
        <div v-if="isAdmin" class="flex flex-row gap-2 items-center">
            <button
                v-if="product.is_trashed"
                type="button"
                v-tooltip.top="'Восстановить'"
                @click="restoreProduct(product.id)"
                class="mb-4 text-green-500 hover:text-green-700 hover:cursor-pointer focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm text-center"
            >
                <ArrowUturnLeftIcon class="w-[25px] h-[25px]"/>
            </button>
            <button
                v-if="!product.is_trashed"
                type="button"
                v-tooltip.top="'В корзину'"
                @click="showDestroyModal = true"
                class="mb-4 text-red-500 hover:text-red-700 hover:cursor-pointer focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm text-center"
            >
                <TrashIcon class="w-[25px] h-[25px]"/>
            </button>
            <button
                type="button"
                v-tooltip.top="'Удалить навсегда'"
                @click="showForceDestroyModal = true"
                class="mb-4 text-red-500 hover:text-red-700 hover:cursor-pointer focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm text-center"
            >
                <XMarkIcon class="w-[25px] h-[25px]"/>
            </button>
        </div>
        <Dialog
            :closable="true"
            :draggable="false"
            header="Удалить в корзину?"
            v-model:visible="showDestroyModal"
            modal :style="{ width: '20rem' }"
        >
            <div class="flex justify-end gap-3">
                <DefaultButton
                    type="button"
                    @click="emits('destroyProduct', product.id); showDestroyModal = false"
                    class="text-white bg-red-600 hover:bg-red-700 focus:ring-red-300 mb-5"
                >
                    Да
                </DefaultButton>
                <DefaultButton
                    type="button"
                    @click="showDestroyModal = false"
                    class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-blue-300 mb-5"
                >
                    Нет
                </DefaultButton>
            </div>
        </Dialog>
        <Dialog
            :closable="true"
            :draggable="false"
            header="Удалить навсегда?"
            v-model:visible="showForceDestroyModal"
            modal :style="{ width: '20rem' }"
        >
            <div class="flex justify-end gap-3">
                <DefaultButton
                    type="button"
                    @click="emits('forceDestroyProduct', product.id); showForceDestroyModal = false"
                    class="text-white bg-red-600 hover:bg-red-700 focus:ring-red-300 mb-5"
                >
                    Да
                </DefaultButton>
                <DefaultButton
                    type="button"
                    @click="showForceDestroyModal = false"
                    class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-blue-300 mb-5"
                >
                    Нет
                </DefaultButton>
            </div>
        </Dialog>
    </div>
</template>

<style scoped>

</style>
