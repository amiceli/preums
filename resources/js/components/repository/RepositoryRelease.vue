<template>
    <div class="p-releases" :class="{ 'is--empty': props.total === 0 }">
        <template v-if="props.total > 0">
            <!-- first release -->
            <ReleaseCard
                v-if="props.firstRelease"
                :release="props.firstRelease"
                label="First release"
            />
            <!-- stats -->
            <div class="p-releases__details">
                <div class="for--text">
                    <div class="parent">
                        <div class="div1">
                            <h1>{{ props.total }} release(s)</h1>
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
                </div>
            </div>
            <!-- last release -->
            <ReleaseCard
                v-if="props.lastRelease"
                :release="props.lastRelease"
                label="Last release"
            />
        </template>
        <div v-else>
            <wa-card class="for--skeleton">
                <h3 slot="header">No published releases</h3>
                <Skeleton />
            </wa-card>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { showUserHistory } from "@/actions/App/Http/Controllers/GithubController"
import type { GithubCommitDiff, GithubRelease } from "@/types/main"
import Skeleton from "../common/Skeleton.vue"
import ReleaseCard from "../release/ReleaseCard.vue"

const props = defineProps<{
    firstRelease: GithubRelease | null
    lastRelease: GithubRelease
    diff: GithubCommitDiff
    total: number
}>()

function releaseReaction(r: GithubRelease) {
    return Object.keys(r.reactions)
        .filter((s) => {
            return r.reactions[s] > 0 && s !== "total"
        })
        .map((s) => {
            return {
                icon: getReactionIcon(s),
                count: r.reactions[s],
            }
        })
}

function getReactionIcon(value: string) {
    return {
        "+1": "👍",
        "-1": "👎",
        laugh: "😄",
        confused: "😕",
        heart: "❤️",
        hooray: "🎉",
        rocket: "🚀",
        eyes: "👀",
    }[value]
}
</script>

<style scoped>
wa-card img {
    max-height: 150px;
}
.p-releases__details {
    height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 20px;
    margin-bottom: 20px;
}
.p-releases__details wa-card {
    display: inline-block;
}
.for--skeleton {
    width: 70%;
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
