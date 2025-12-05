<template>
    <Layout>
        <wa-card orientation="horizontal" class="main__card">
            <div slot="media">
                <img
                    :src="props.repository.owner.avatarUrl"
                    alt="A kitten sits patiently between a terracotta pot and decorative grasses."
                />
            </div>

            <h1>
                {{ props.repository.fullName }}
            </h1>
            {{ props.repository.description }}

            <section>
                <RepositoryDetails :repository="props.repository" />
            </section>

            <wa-button
                :href="props.repository.url"
                slot="actions"
                variant="brand"
                size="small"
                appearance="plain"
                target="_blank"
            >
                See repository
            </wa-button>
        </wa-card>

        <masonry-wall
            :items="components"
            :ssr-columns="1"
            :column-width="300"
            :gap="16"
        >
            <template #default="{ item, index }">
                <div>
                    <RepositoryTopics
                        v-if="item === 'topics'"
                        :topics="props.topics"
                    />
                    <RepositoryLanguages
                        :languages="props.languages"
                        v-else-if="item === 'languages'"
                    />
                    <!-- <RepositoryCommits
                        :commirs="props.commits"
                        v-else-if="item === 'commits'"
                    /> -->
                </div>
            </template>
        </masonry-wall>
        <br>
        <RepositoryCommits
            v-if="props.commits.length > 0"
            :commirs="props.commits"
        />
        <br>
        <RepositoryRelease
            v-if="props.releases.length > 0"
            :releases="props.releases"
        />
    </Layout>
</template>

<script setup lang="ts">
import MasonryWall from "@yeger/vue-masonry-wall"
import { ref } from "vue"
import Layout from "@/components/Layout.vue"
import RepositoryCommits from "@/components/repository/RepositoryCommits.vue"
import RepositoryDetails from "@/components/repository/RepositoryDetails.vue"
import RepositoryLanguages from "@/components/repository/RepositoryLanguages.vue"
import RepositoryRelease from "@/components/repository/RepositoryRelease.vue"
import RepositoryTopics from "@/components/repository/RepositoryTopics.vue"
import type { GithubCommit, GithubRelease, GithubSearchResultItem } from "@/types/github"

const components = ref<string[]>(["topics", "languages", "commits"])

const props = defineProps<{
    repository: GithubSearchResultItem
    commits: GithubCommit[]
    topics: string[]
    languages: Record<string, number>
    releases: GithubRelease[]
}>()
</script>

<style scoped>
.main__card {
    display: grid;
    grid-template-columns: 250px 1fr auto;
    gap: 20px;
    margin-top: 40px;
    margin-bottom: 40px;
}

section {
    margin-top: 20px;
}

.repo__title {
    width: 80%;
    margin-left: auto;
    margin-right: auto;
    display: flex;
    align-items: center;
    gap: 40px;
    margin-top: 40px;
    margin-bottom: 40px;
}
.repo__history {
    display: grid;
    grid-template-columns: 200px 1fr;
    width: 80%;
    margin-left: auto;
    margin-right: auto;
}
</style>
