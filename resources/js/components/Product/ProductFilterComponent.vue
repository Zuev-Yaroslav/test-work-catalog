<script setup lang="ts">

import DefaultSelect from "@/components/DefaultSelect.vue";
import DefaultInput from "@/components/DefaultInput.vue";
import Checkbox from 'primevue/checkbox';

const emits = defineEmits(['filter']);
const props = defineProps({
    categories: Array,
    filterData: Object,
    isAdmin: Boolean,
});
</script>

<template>
    <div v-bind:class="$attrs">
        <h1 class="text-3xl mb-4">Фильтр</h1>
        <div class="flex mb-2 flex-wrap gap-y-3 gap-x-10">
            <div>
                <label for="countries" class="block mb-2 text-sm font-medium text-gray-900">Выберите категорию</label>
                <DefaultSelect
                    :add-no-select="true"
                    :items="categories"
                    v-model="filterData.category_id"
                    key-value="id"
                    key-name="name"
                    id="countries"
                    @change="emits('filter', $event)"
                />
            </div>
            <div>
                <label for="countries" class="block mb-2 text-sm font-medium text-gray-900">Поиск</label>
                <DefaultInput
                    class="w-75"
                    v-model="filterData.search"
                    @input="emits('filter', $event)"
                />
            </div>
            <div v-if="isAdmin">
                <label for="onlyTrashed" class="block mb-2 text-sm font-medium text-gray-900">Только в корзине</label>
                <Checkbox
                    v-model="filterData.only_trashed"
                    input-id="onlyTrashed"
                    name="only_trashed"
                    :value="true"
                    binary
                    @change="emits('filter', $event)"
                    :pt="{
                        box: ({ context }) => ({
                          class: context.checked
                            ? '!bg-blue-500 !border-blue-500'
                            : 'bg-transparent border-gray-300'
                        })
                    }"
                />
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
