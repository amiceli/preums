<template>
    <div class="world__stats">
        <h1>
            Oldest and starred repository for
            <b class="for--info">{{ props.lang }}</b>
        </h1>
        <RepositoryCard :repository="repo" v-if="repo" show-avatar />
    </div>
</template>

<script lang="ts" setup>
import axios from "axios"
import { ref, watch } from "vue"
import type { GithubRepository } from "@/types/github"
import RepositoryCard from "../RepositoryCard.vue"

const props = defineProps<{
    lang: string | null
}>()
const repo = ref<GithubRepository | null>(null)

watch(
    () => props.lang,
    async () => {
        const { data } = await axios.post<GithubRepository>("/old-stars", {
            lang: props.lang,
        })

        repo.value = data
    },
)
</script>

<style scoped>
.world__stats {
}
</style>
