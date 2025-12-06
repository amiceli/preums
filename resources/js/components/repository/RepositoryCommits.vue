<template>
    <div class="commits__grid">
        <div ref="first">
            <wa-card v-if="props.firstCommit">
                <div slot="media">
                    <img :src="props.firstCommit.authorImg" />
                </div>
                <h3>First commit</h3>
                <p class="wa-caption">
                    <i class="hn hn-calender-solid"></i>
                    {{ new Date(props.firstCommit.dateStr).toLocaleString() }}
                </p>
                <b>{{ props.firstCommit.author }}</b>
                <br />
                {{ props.firstCommit.message }}
            </wa-card>
        </div>
        <div class="grid__diff">
             <h3>
                 {{ props.total}} commit(s) in
                {{ props.diff.days }}
                day(s)
            </h3>
        </div>
        <div ref="last">
            <wa-card>
                <div slot="media">
                    <img :src="props.lastCommit.authorImg" />
                </div>
                <h3>Last commit</h3>
                <p class="wa-caption">
                    <i class="hn hn-calender-solid"></i>
                    {{ new Date(props.lastCommit.dateStr).toLocaleString() }}
                </p>
                <b>{{ props.lastCommit.author }}</b>
                <br />
                {{ props.lastCommit.message }}
            </wa-card>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { useTemplateRef } from "vue"
import type { GithubCommit, GithubCommitDiff } from "@/types/github"

// import { createTimeline } from "animejs";

const first = useTemplateRef("first")
const last = useTemplateRef("last")

const props = defineProps<{
    firstCommit: GithubCommit | null
    lastCommit: GithubCommit
    diff: GithubCommitDiff
    total: number
}>()
</script>

<style scoped>
.commits__grid {
    display: grid;
    grid-template-columns: 300px 1fr 300px;
    gap: 20px;
    margin-right: 10px;
    margin-bottom: 10px;
    justify-content: space-between;
}

.grid__diff {
    display: flex;
    align-items: center;
    justify-content: center;

    h3 {
        text-align: center;
        margin: 0;
    }
}
</style>
