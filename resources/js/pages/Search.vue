<template>
    <Layout>
        <h1>Results</h1>
        <div class="nb-grid-3">
            <div class="grid__item" v-for="p in props.items" :key="p.id">
                <RepositoryCard :repostiory="p" />
            </div>
        </div>
    </Layout>
</template>

<script setup lang="ts">
import Layout from "@/components/Layout.vue";
import RepositoryCard from "@/components/RepositoryCard.vue";
import type { GithubSearchResult } from "@/types/github";

const props = defineProps<{
    items: GithubSearchResult["items"];
}>();
</script>

<style scoped>
.grid__item {
    display: flex;
    justify-content: center;
}
</style>
