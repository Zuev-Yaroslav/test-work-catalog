<script setup lang="ts">
const value = defineModel()
</script>

<template>
    <textarea v-bind="$attrs" v-model="value" class="block p-2.5 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:outline-blue-500 focus:border-blue-500"></textarea>
</template>

<style scoped>

</style>
