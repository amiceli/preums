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
        {{ props }}
        <!-- <div class="nb-grid-3">
            <div class="grid__item" v-for="p in props.items" :key="p.id">
                <RepositoryCard :repostiory="p" />
            </div>
        </div> -->
    </Layout>
</template>

<script setup lang="ts">
import Layout from "@/components/Layout.vue";
import RepositoryCard from "@/components/RepositoryCard.vue";
import type { GithubSearchResult } from "@/types/github";

const props = defineProps<{
    repository: any;
    commit: any;
    topics: string[];
    languages: Record<string, number>;
}>();
</script>

<style scoped>
.repo__title {
    display: flex;
    align-items: center;
    gap : 40px;
    justify-content: center;
}
</style>
