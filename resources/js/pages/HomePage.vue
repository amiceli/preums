<template>
    <Layout>
        <MainTitle :langs="props.allLangs" />

        <div class="page__form">
            <SearchForm />
        </div>

        <div class="page__title">
            <h1>
                <i class="hn hn-book-heart"></i>
                Top 50 oldest and starred repositories
            </h1>
            <p>
                This list ranks among the oldest repositories with the most
                stars. It reminds us that it is possible to <br />
                code and improve the tech field without AI.
            </p>
        </div>

        <div class="p-most">
            <template
                v-for="p in Object.keys(props.oldestRepos)"
                :key="`year-${p}`"
            >
                <h2>
                    {{ p }}
                </h2>
                <div class="p-most__grid">
                    <div v-for="item in props.oldestRepos[p]" :key="item.id">
                        <RepositoryCard
                            :repository="item"
                            :show-avatar="true"
                        />
                    </div>
                </div>
            </template>
        </div>
    </Layout>
</template>

<script setup lang="ts">
import Layout from "@/components/Layout.vue"
import MainTitle from "@/components/MainTitle.vue"
import RepositoryCard from "@/components/RepositoryCard.vue"
import SearchForm from "@/components/search/SearchForm.vue"
import type { GithubSearchResult } from "@/types/github"

const props = defineProps<{
    oldestRepos: {
        [key: string]: GithubSearchResult["items"]
    }
    allLangs: string[]
}>()
</script>

<style scoped>
.p-most {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding-bottom: 100px;
}
.p-most__grid {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 30px;
}

h2 {
    display: block;
    margin-top: 40px;
    margin-bottom: 40px;
}
.page__form {
    margin-bottom: 50px;
    margin-top: 50px;
}
</style>
