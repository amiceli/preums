<template>
    <div class="p-commits">
        <CommitCard
            :commit="props.firstCommit"
            v-if="props.firstCommit"
            label="First commit"
        />
        <div class="p-commits__details">
            <div class="for--text">
                <div class="parent">
                    <div class="div1">
                        <h1>{{ props.total }} commit(s)</h1>
                    </div>
                    <div class="div3">
                        <h2 v-if="props.diff.days > 0">
                            in {{ props.diff.days }}
                        </h2>
                        <h2 v-else>it's</h2>
                    </div>
                    <div class="div5">
                        <h3 v-if="props.diff.days > 0">day(s)</h3>
                        <h3 v-else>enough</h3>
                    </div>
                </div>
                <h3>{{ props.activity.totalCommits || "No" }} commit(s) last year</h3>
            </div>
            <wa-divider orientation="vertical"></wa-divider>
            <div>
                <template v-if="props.activity.totalCommits > 0">
                    <h2>Yearly most coded days</h2>
                    <canvas ref="charts"></canvas>
                </template>
                <div v-else>
                    <h2>No enough stats</h2>
                    <Skeleton :line="true" />
                </div>
            </div>
        </div>
        <CommitCard :commit="props.lastCommit" label="Last commit" />
    </div>
</template>

<script lang="ts" setup>
import Chart from "chart.js/auto"
import { onMounted, useTemplateRef } from "vue"
import type { GithubCommit, GithubCommitActivity, GithubCommitDiff } from "@/types/github"
import Skeleton from "../common/Skeleton.vue"
import CommitCard from "./CommitCard.vue"

const charts = useTemplateRef("charts")

const props = defineProps<{
    firstCommit: GithubCommit | null
    lastCommit: GithubCommit
    diff: GithubCommitDiff
    total: number
    activity: GithubCommitActivity
}>()

onMounted(() => {
    if (charts.value) {
        new Chart(charts.value, {
            type: "bar",
            data: {
                labels: Object.keys(props.activity.days),
                datasets: [
                    {
                        label: "# days activity",
                        data: Object.values(props.activity.days),
                        borderWidth: 1,
                    },
                ],
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                    },
                },
            },
        })
    }
})
</script>

<style scoped>
.p-commits__details {
    min-height: 300px;
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    align-items: center;
    justify-content: center;
    margin-top: 20px;
    margin-bottom: 20px;
}
.p-commits__details h2 {
    text-align: center;
}

.for--text {
    text-align: center;
}

.parent + h3 {
    margin-top: 20px;
}

.parent {
    display: inline-grid;
    align-items: center;
    justify-content: center;
    grid-template-columns: repeat(2, 1fr);
    grid-template-rows: repeat(2, 1fr);
}

.div1 {
    grid-column: span 2 / span 2;
    display: flex;
    align-items: center;
    justify-content: center;
}

.div3 {
    grid-column: span 2 / span 2;
    grid-column-start: 1;
    grid-row-start: 2;
    display: flex;
    align-items: center;
    justify-content: center;
}

.div5 {
    grid-row: span 2 / span 2;
    grid-column-start: 3;
    grid-row-start: 1;

    text-align: center;
    writing-mode: vertical-rl;
    text-orientation: mixed;
    padding-left: 10px;
}
</style>
