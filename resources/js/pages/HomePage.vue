<template>
    <Layout>
        <MainTitle :langs="props.allLangs" />

        <div class="home__form">
            <SearchForm />
        </div>

        <h1>
            <i class="hn hn-book-heart"></i>
            Top 50 oldest and starred repositories
        </h1>
        <p>
            This list ranks among the oldest repositories with the most
            stars. It reminds us that it is possible to <br />
            code and improve the tech field without AI.
        </p>

        <div class="home__most">
            <template
                v-for="p in Object.keys(props.oldestRepos)"
                :key="`year-${p}`"
            >
                <h2>
                    {{ p }}
                </h2>
                <MasonryWall
                    :items="props.oldestRepos[p]"
                    :column-width="300"
                    :gap="20"
                >
                    <template #default="{ item, index }">
                        <RepositoryCard
                            :repository="item"
                            :show-avatar="true"
                        />
                    </template>
                </MasonryWall>
            </template>
        </div>
    </Layout>
</template>

<script setup lang="ts">
import MasonryWall from "@yeger/vue-masonry-wall"
import Layout from "@/components/Layout.vue"
import MainTitle from "@/components/MainTitle.vue"
import RepositoryCard from "@/components/RepositoryCard.vue"
import SearchForm from "@/components/search/SearchForm.vue"
import type { GithubSearchResult } from "@/types/main"

const props = defineProps<{
    oldestRepos: {
        [key: string]: GithubSearchResult["items"]
    }
    allLangs: string[]
}>()
</script>

<style lang="scss" scoped>
.home {

    &__most h2 {
        display: block;
        margin-top: 40px;
        margin-bottom: 40px;
        text-align: center;
    }

    &__form {
        margin-bottom: 50px;
        margin-top: 50px;
    }
}
</style>
