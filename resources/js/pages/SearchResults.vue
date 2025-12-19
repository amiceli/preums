<template>
    <Layout>
        <div class="page__title">
            <h1>
                We found {{ props.repositories.length }} repositories
            </h1>
            <p v-if="props.repositories.length > 0">
                Repositories are sort by created date. It reminds time when we
                coded like searcher.
            </p>
        </div>
        <div class="page__form">
            <SearchForm />
        </div>
        <div class="preums_grid">
            <div class="grid__item" v-for="p in props.items" :key="p.id">
                <RepositoryCard :repository="p" />
            </div>
        </div>
        <Skeleton v-if="props.repositories.length === 0" />
    </Layout>
</template>

<script setup lang="ts">
import Skeleton from "@/components/common/Skeleton.vue"
import Layout from "@/components/Layout.vue"
import RepositoryCard from "@/components/RepositoryCard.vue"
import SearchForm from "@/components/search/SearchForm.vue"
import type { GithubRepository } from "@/types/github"

const props = defineProps<{
    repositories: GithubRepository[]
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

.preums_grid + div {
    width: 70%;
}

.page__form {
    margin-bottom: 50px;
    margin-top: 50px;
}
</style>
