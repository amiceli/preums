<template>
    <Layout>
        <div class="user__avatar">
            <img :src="props.user.avatarUrl" alt="Org logo" />
            <h1>
                <a :href="props.user.url" target="_blank">
                    {{ props.user.login }}
                </a>
            </h1>
        </div>
        <br />
        <div class="user__details">
            <h1>~</h1>
            <h3>
                <i class="hn hn-calender-solid"></i>
                <template v-if="diffYears > 0">
                    Sing-up {{ diffYears }} year(s) ago
                </template>
                <template v-else>
                    {{ new Date(props.user.createdAtStr).toLocaleDateString() }}
                </template>
            </h3>
            <h3 v-if="props.user.location">
                <i class="hn hn-flag-solid"></i>
                {{ props.user.location }}
            </h3>
            <h3>
                <i class="hn hn-heart-solid"></i>
                {{ props.user.followers }} followers(s)
                <span v-if="props.user.following > 0">
                    / following {{ props.user.following }} user(s)
                </span>
            </h3>
            <h3>
                <i class="hn hn-code"></i>
                {{ props.user.countRepos }} repositorie(s)
            </h3>
            <h1>~</h1>
        </div>
        <h1>Repositories</h1>
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
import type { GithubRepository, GithubUser } from "@/types/main"

const props = defineProps<{
    user: GithubUser
    repositories: GithubRepository[]
}>()

const diffYears = computed(() => {
    return new Date().getFullYear() - new Date(props.user.createdAtStr).getFullYear()
})
</script>

<style lang="scss" scoped>
.user {
    &__avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 30px;
        padding-top: 40px;

        img {
            max-height: 200px;
        }
    }

    &__details {
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
}
</style>
