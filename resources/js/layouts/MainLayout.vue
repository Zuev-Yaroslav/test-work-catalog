<script setup lang="ts">
import {Link, router} from '@inertiajs/vue3'
import {onMounted, ref} from "vue";

import api from "@/axios/api";

const user = ref(null)

const getUser = async () => {
    const token = localStorage.getItem('auth_token');
    if (!token) {
        return;
    }

    try {
        const response = await api.get(route('api.v1.auth.me'))
        user.value = response.data;
    } catch (error) {
        console.log(error);
    }

}
const logout = () => {
    api.post(route('api.v1.auth.logout'))
        .then(() => {
            localStorage.removeItem('auth_token')
            user.value = null
            router.visit(route('main'));
        })
}

onMounted(() => {
    getUser();
})
</script>

<template>
    <div class="m-auto px-5 max-w-210">
        <header class="flex justify-between mb-6">
            <div>
                <Link :href="route('main')" class="text-blue-500">Главная</Link>
            </div>
            <div v-if="user" class="flex justify-end gap-2">
                <Link :href="route('admin.products.index')" class="text-blue-500">Управление товарами</Link>
                <a href="#" @click.prevent="logout" class="text-blue-500">Выйти</a>
            </div>
            <div v-else class="flex justify-end gap-2">
                <Link :href="route('auth.login')" class="text-blue-500">Авторизоваться</Link>
            </div>
        </header>
        <div>
            <slot/>
        </div>
    </div>
</template>

<style scoped>

</style>
