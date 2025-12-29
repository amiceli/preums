<template>
    <Layout>
        <div class="search__title">
            <h1>We found {{ props.repositories.length }} repositories</h1>
            <p v-if="props.repositories.length > 0">
                Repositories are sort by created date. It reminds time when we
                coded like searcher.
            </p>
        </div>
        <div class="search__form">
            <SearchForm />
        </div>
        <div class="search__grid">
            <div class="grid__item" v-for="p in props.repositories" :key="p.id">
                <RepositoryCard :repository="p" show-avatar />
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
import type { GithubRepository } from "@/types/main"

const props = defineProps<{
    repositories: GithubRepository[]
}>()
</script>

<style lang="scss" scoped>
.search {
    &__title {
        margin-top: 40px;
        margin-bottom: 40px;
    }

    &__form {
        margin-bottom: 50px;
        margin-top: 50px;
    }

    &__grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 30px;

        & + div {
            width: 70%;
        }
    }
}
</style>
