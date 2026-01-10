<template>
    <Head title="Preums"></Head>
    <NavBar />

    <main :class="{ 'full--width' : props.fullWidht}" >
        <slot></slot>
    </main>
</template>

<script setup lang="ts">
import { Head } from "@inertiajs/vue3"
import NavBar from "./NavBar.vue"

const props = defineProps<{
    fullWidht?: boolean
}>()
</script>

<style scoped>
main {
    padding-bottom: 100px;

    &.full--width {
        width: 100%;
    }

    &:not(.full--width) {
        width: 80%;
        margin-left: auto;
        margin-right: auto;
    }
}
</style>
