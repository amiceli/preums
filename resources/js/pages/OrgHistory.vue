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
        <h1>Repositories</h1>
        <MasonryWall :items="props.repositories" :column-width="300" :gap="20">
            <template #default="{ item, index }">
                <RepositoryCard :repository="item" />
            </template>
        </MasonryWall>
        <h1>
            Thanks to {{ props.org.name }} family
            <small> {{ props.members.length }} member(s) </small>
        </h1>
        <br />
        <MasonryWall :items="props.members" :column-width="70" :gap="15">
            <template #default="{ item, index }">
                <wa-tooltip :for="`member-${index}`">
                    {{ item.login }}
                </wa-tooltip>

                <a :href="item.url" target="_blank">
                    <wa-avatar
                        :image="item.avatarUrl"
                        :id="`member-${index}`"
                    ></wa-avatar>
                </a>
            </template>
        </MasonryWall>
    </Layout>
</template>

<script setup lang="ts">
import MasonryWall from "@yeger/vue-masonry-wall"
import { computed } from "vue"
import Layout from "@/components/Layout.vue"
import RepositoryCard from "@/components/RepositoryCard.vue"
import type { GithubOrg, GithubRepository, GithubUser } from "@/types/github"

const props = defineProps<{
    org: GithubOrg
    repositories: GithubRepository[]
    members: GithubUser[]
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
    padding-top: 40px;

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

wa-avatar {
    --size: 70px;
}

.masonry-wall + h1 {
    margin-top: 40px;
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
