<script setup lang="ts">

defineProps({
    items: Array,
    keyValue: String,
    keyName: String,
    addNoSelect: Boolean,
})
const value = defineModel()
</script>

<template>
    <select v-bind="$attrs" v-model="value" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-1.5">
        <option v-if="addNoSelect" :value="null">Не выбирать</option>
        <option v-for="item in items" :key="item[keyValue]" :value="item[keyValue]">{{ item[keyName] }}</option>
    </select>
</template>

<style scoped>

</style>
