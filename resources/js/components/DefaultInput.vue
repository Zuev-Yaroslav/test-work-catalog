<script setup lang="ts">
const value = defineModel()
</script>

<template>
    <input v-bind="$attrs" v-model="value" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:outline-blue-600 block py-1 px-2.5">
</template>

<style scoped>

</style>
