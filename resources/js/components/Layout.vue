<template>
    <Head title="Preums"></Head>
    <NavBar />

    <main>
        <slot></slot>
    </main>
</template>

<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3"
import NavBar from "./NavBar.vue"
</script>

<style scoped>
main {
    padding-bottom: 100px;
    width: 80%;
    margin-left: auto;
    margin-right: auto;
}
</style>
