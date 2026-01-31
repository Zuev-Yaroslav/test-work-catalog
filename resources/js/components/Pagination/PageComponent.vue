<script setup lang="ts">
import {Link} from '@inertiajs/vue3'
import {computed} from "vue";

import PrevNextComponent from "@/components/Pagination/PrevNextComponent.vue";

const props = defineProps({
    link: Object,
    meta: Object,
    filterData: Object,
    routeName: String,
})

const url = computed(() => {
    const params = { ...props.filterData}
    params.page = props.link.page;
    return route(props.routeName, params)
})
const isPrev = computed(() => {
    return props.link.label && props.link.label.includes('Previous')
})
const isNext = computed(() => {
    return props.link.label && props.link.label.includes('Next')
})
</script>

<template>
    <PrevNextComponent v-if="isPrev || isNext" :url="url" :isPrev="isPrev" :isNext="isNext" :link="link"></PrevNextComponent>
    <span v-else-if="link.label === '...'" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 inset-ring inset-ring-gray-300 focus:outline-offset-0">...</span>
    <span
        v-else-if="link.active"
        class="relative z-10 inline-flex items-center bg-indigo-600 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
    >{{ link.page }}</span>
    <Link
        :preserve-scroll="true"
        :preserve-state="true"
        v-else-if="link.page"
        :href="url"
        class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 inset-ring inset-ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
    >{{ link.page }}</Link>
</template>

<style scoped>

</style>
