<template>
    <form @submit.prevent="submit">
        <wa-input
            label="Get history of"
            placeholder="A repository who changed your life"
            v-model="name"
            appearance="filled-outlined"
            required
        ></wa-input>
        <wa-button type="submit" :loading="isLoading">Search</wa-button>
    </form>
</template>

<script setup lang="ts">
import { router } from "@inertiajs/vue3"
import { ref } from "vue"

const name = ref<string>("")
const isLoading = ref(false)

function submit() {
    isLoading.value = true

    router.visit("/github/search", {
        data: {
            name: name.value,
        },
    })
}
</script>

<style scoped>
form {
    display: grid;
    grid-template-columns: 400px auto;
    align-items: end;
    justify-content: center;
    gap: 30px;
}
</style>
