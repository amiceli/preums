<template>
    <div class="p-commits">
        <CommitCard
            :commit="props.firstCommit"
            v-if="props.firstCommit"
            label="First commit"
        />
        <div class="p-commits__details">
            <div>
                <h3>
                    {{ props.total }} commit(s) in
                    {{ props.diff.days }}
                    day(s)
                </h3>
                <br />
                <h3>{{ props.activity.totalCommits }} last year</h3>
            </div>
            <wa-divider orientation="vertical"></wa-divider>
            <div>
                <h2>
                    Yearly most coded days
                </h2>
                <canvas ref="charts"></canvas>
            </div>
        </div>
        <CommitCard :commit="props.lastCommit" label="Last commit" />
    </div>
</template>

<script lang="ts" setup>
import Chart from "chart.js/auto"
import { onMounted, useTemplateRef } from "vue"
import type { GithubCommit, GithubCommitActivity, GithubCommitDiff } from "@/types/github"
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
    new Chart(charts.value!, {
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
</style>
