<script setup lang="ts">
import {router} from "@inertiajs/vue3";
import axios from "axios";
import {HttpStatus} from "http-status-ts";
import {onMounted, reactive, ref} from "vue";

import DefaultButton from "@/components/DefaultButton.vue";
import DefaultInput from "@/components/DefaultInput.vue";

const errors = ref({});
const token = ref(localStorage.getItem('auth_token'));
const errorMessage = ref(null);
const credentials = reactive({
    email: '',
    password: '',
})

const middlewareGuest = () => {
    if (token.value) {
        router.visit(route('main'));
    }
}
onMounted(() => {
    middlewareGuest();
})

const login = () => {
    errors.value = {};
    errorMessage.value = '';
    axios.post(route('api.v1.auth.login'), credentials)
        .then(response => {
            localStorage.setItem('auth_token', response.data.token);
            // Cookies.set('is_logged_in', true, { expires: 30, sameSite: 'Lax' });
            router.visit(route('main'));
        })
        .catch(error => {
            switch (error.status) {
                case HttpStatus.UNPROCESSABLE_ENTITY:
                    errors.value = error.response.data;
                    break;
                case HttpStatus.UNAUTHORIZED:
                    errorMessage.value = error.response.data.message;
            }
        });
}
</script>

<template>
    <section v-if="!token" class="bg-gray-50">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            <div class="w-full bg-white rounded-lg shadow md:mt-0 sm:max-w-md xl:p-0">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl font-bold text-center leading-tight tracking-tight text-gray-900 md:text-2xl">
                        Авторизация
                    </h1>
                    <div class="space-y-4 md:space-y-6">
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Почта</label>
                            <DefaultInput
                                v-model="credentials.email"
                                type="email"
                                placeholder="name@company.com"
                                required=""
                                class="mb-2 w-full"
                                id="email"
                            />
                            <div v-if="errors.errors?.email" class="text-red-500 text-sm">{{
                                    errors.errors?.email[0]
                                }}
                            </div>
                            <div v-if="errorMessage" class="text-red-500 text-sm">{{ errorMessage }}</div>
                        </div>
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Пароль</label>
                            <DefaultInput
                                v-model="credentials.password"
                                type="password"
                                placeholder="••••••••"
                                required
                                class="mb-2 w-full"
                                id="password"
                            />
                            <div v-if="errors.errors?.password" class="text-red-500 text-sm">
                                {{ errors.errors.password[0] }}
                            </div>
                        </div>
                        <DefaultButton
                            type="button"
                            @click="login"
                            class="w-full bg-blue-600 hover:bg-blue-700 focus:ring-blue-300 text-white"
                        >
                            Войти
                        </DefaultButton>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>

</style>
