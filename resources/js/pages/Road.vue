<template>
    <Layout>
        <div class="p-title">
            <i class="hn hn-startups"></i>
            <h1>
                I'm working on it
            </h1>
        </div>
    </Layout>
</template>

<script setup lang="ts">
import Layout from "@/components/Layout.vue"
</script>

<style scoped>
.p-title {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 40px;
    margin-top: 100px;
}
.p-title i {
    font-size: 100px;
}
</style>
