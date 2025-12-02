<template>
    <Layout>
        <h1>Rate limit</h1>

        Wait some minutes ...
        {{ new Date(props.nextReset).toLocaleString() }}
    </Layout>
</template>

<script setup lang="ts">
import Layout from "@/components/Layout.vue";

const props = defineProps<{ nextReset: string }>();
</script>

<style scoped></style>
