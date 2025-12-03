<template>
    <Layout>
        <div class="repo__title">
            <div class="avatar avatar-lg pale-red">
                <img
                    :src="props.repository.owner.avatarUrl"
                    class="nb-card-img"
                />
            </div>

            <h1>
                {{ props.repository.fullName }}
            </h1>
        </div>
        <div class="repo__history">
            <div class="repo__details n-section">
                <h2>Topics</h2>

                <div
                    class="badge"
                    v-for="t in props.topics"
                >
                    <div class="badge-inner">
                        <p class="badge-text">
                            {{ t }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="repo__commits"></div>
        </div>
        {{ props }}
        <!-- <div class="nb-grid-3">
            <div class="grid__item" v-for="p in props.items" :key="p.id">
                <RepositoryCard :repostiory="p" />
            </div>
        </div> -->
    </Layout>
</template>

<script setup lang="ts">
import Layout from "@/components/Layout.vue"
import RepositoryCard from "@/components/RepositoryCard.vue"
import type { GithubSearchResult } from "@/types/github"

const props = defineProps<{
    repository: any
    commit: any
    topics: string[]
    languages: Record<string, number>
}>()
</script>

<style scoped>
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
