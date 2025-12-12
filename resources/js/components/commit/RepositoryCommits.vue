<template>
    <div class="p-commits">
        <CommitCard
            :commit="props.firstCommit"
            v-if="props.firstCommit"
            label="First commit"
        />
        <div class="p-commits__details" ref="parent">
            <div ref="background"></div>
             <wa-card>
                <h3>
                    {{ props.total }} commit(s) in
                    {{ props.diff.days }}
                    day(s)
                </h3>
            </wa-card>
        </div>
        <CommitCard :commit="props.lastCommit" label="Last commit" />
    </div>
</template>

<script lang="ts" setup>
import { onMounted, useTemplateRef } from "vue"
import type { GithubCommit, GithubCommitDiff } from "@/types/github"
import CommitCard from "./CommitCard.vue"

const background = useTemplateRef("background")

const props = defineProps<{
    firstCommit: GithubCommit | null
    lastCommit: GithubCommit
    diff: GithubCommitDiff
    total: number
}>()

onMounted(() => {})
</script>

<style scoped>
.p-commits__details {
    height: 300px;
    background: rgba(255, 0, 0, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 20px;
    margin-bottom: 20px;
}
.p-commits__details wa-card {
    display: inline-block;
}
</style>
