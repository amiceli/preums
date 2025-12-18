<template>
    <Layout>
        <div class="org__avatar">
            <img :src="props.org.avatarUrl" alt="Org logo" />
            <h1>
                <a :href="props.org.url" target="_blank">
                    {{ props.org.name }}
                </a>
            </h1>
        </div>
        <div class="org__details">
            <h1>~</h1>
            <h3>
                <i class="hn hn-calender-solid"></i>
                <template v-if="diffYears > 0">
                    created {{ diffYears }} year(s) ago
                </template>
                <template v-else>
                    {{ new Date(props.org.createdAtStr).toLocaleDateString() }}
                </template>
            </h3>
            <h3>
                <i class="hn hn-flag-solid"></i>
                {{ props.org.location }}
            </h3>
            <h3>
                <i class="hn hn-heart-solid"></i>
                {{ props.org.followers }} followers(s)
            </h3>
            <h3>
                <i class="hn hn-code"></i>
                {{ props.org.countRepos }} repositorie(s)
            </h3>
            <h1>~</h1>
        </div>

        <!-- <wa-card orientation="horizontal" class="main__card">
            <div slot="media">
                <img
                    :src="props.org.avatarUrl"
                    alt="A kitten sits patiently between a terracotta pot and decorative grasses."
                />
            </div>
            <h1>
                {{ props.org.name }}
            </h1>

        </wa-card> -->
        <h1>{{ props.repositories.length }} Repositories</h1>
        <MasonryWall :items="props.repositories" :column-width="300" :gap="20">
            <template #default="{ item, index }">
                <RepositoryCard :repository="item" />
            </template>
        </MasonryWall>
    </Layout>
</template>

<script setup lang="ts">
import MasonryWall from "@yeger/vue-masonry-wall"
import { computed } from "vue"
import Layout from "@/components/Layout.vue"
import RepositoryCard from "@/components/RepositoryCard.vue"
import type { GithubOrg, GithubSearchResultItem } from "@/types/github"

const props = defineProps<{
    org: GithubOrg
    repositories: GithubSearchResultItem[]
}>()

const diffYears = computed(() => {
    return new Date().getFullYear() - new Date(props.org.createdAtStr).getFullYear()
})
</script>

<style scoped>
.org__avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 30px;

    img {
        max-height: 200px;
    }
}

.org__details {
    text-align: center;

    h3 {
        display: flex;
        gap: 15px;
        align-items: center;
        justify-content: center;
        margin-top: 10px;
        margin-bottom: 10px;
    }
}

wa-card h3 {
    margin-bottom: 0;
}

/**/
.m ain__card {
    margin-top: 40px;
    img {
        max-height: 200px;
        max-width: 200px;
    }
}
wa-card + h1 {
    margin-top: 40px;
    margin-bottom: 40px;
    text-align: center;
}
</style>
