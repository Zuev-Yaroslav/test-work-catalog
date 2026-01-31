<script setup lang="ts">
import {Link} from "@inertiajs/vue3"

defineProps({
    linkable: Boolean,
});
</script>

<template>
    <Link v-if="linkable" v-bind="$attrs" class="inline-block hover:cursor-pointer focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center">
        <slot />
    </Link>
    <button v-else v-bind="$attrs" class="inline-block hover:cursor-pointer focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center">
        <slot />
    </button>
</template>

<style scoped>

</style>
