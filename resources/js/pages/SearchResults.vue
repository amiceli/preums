<template>
    <Layout>
        <div class="page__title">
            <h1>
                <i class="hn hn-bullet-list"></i>
                We found {{ props.items.length }} repositories
            </h1>
            <p>
                Repositories are sort by created date. It reminds time when we
                coded like searcher.
            </p>
        </div>
        <div class="preums_grid">
            <div class="grid__item" v-for="p in props.items" :key="p.id">
                <RepositoryCard :repository="p" />
            </div>
        </div>
    </Layout>
</template>

<script setup lang="ts">
import Layout from "@/components/Layout.vue"
import RepositoryCard from "@/components/RepositoryCard.vue"
import type { GithubSearchResult } from "@/types/github"

const props = defineProps<{
    items: GithubSearchResult["items"]
}>()
</script>

<style scoped>
.page__title {
    margin-top: 40px;
    margin-bottom: 40px;
}

.preums_grid {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 30px;
}
</style>
