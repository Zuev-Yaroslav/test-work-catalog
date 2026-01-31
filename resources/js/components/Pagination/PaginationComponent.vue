<script setup lang="ts">
import PageComponent from "@/components/Pagination/PageComponent.vue";

defineProps({
    meta: Object,
    filterData: Object,
    routeName: String,
})
</script>

<template>
        <div class="flex items-center justify-between bg-white py-3">
            <div>
                <p class="text-sm w-30 text-gray-700">
                    <span v-if="meta.total > 0">
                        Просмотр от
                        {{ ' ' }}
                        <span class="font-medium">{{ meta.from }}</span>
                        {{ ' ' }}
                        до
                        {{ ' ' }}
                        <span class="font-medium">{{ meta.to }}</span>
                        {{ ' ' }}
                        из
                        {{ ' ' }}
                    </span>
                    <span class="font-medium">{{ meta.total }}</span>
                    {{ ' ' }}
                    продуктов
                </p>
            </div>
            <div>
                <nav class="isolate inline-flex flex-wrap gap-y-1 -space-x-px rounded-md shadow-xs" aria-label="Pagination">
                    <!-- Current: "z-10 bg-indigo-600 text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600", Default: "text-gray-900 inset-ring inset-ring-gray-300 hover:bg-gray-50 focus:outline-offset-0" -->

                    <PageComponent
                        v-for="(link, index) in meta.links"
                        :key="index"
                        :link="link"
                        :meta="meta"
                        :filter-data="filterData"
                        :route-name="routeName"
                    />
                </nav>
            </div>
        </div>
</template>

<style scoped>

</style>
