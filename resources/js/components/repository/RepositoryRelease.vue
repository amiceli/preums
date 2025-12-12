<template>
    <div class="p-releases" :class="{ 'is--empty': props.total === 0 }">
        <template v-if="props.total > 0">
            <wa-card v-if="props.firstRelease" orientation="horizontal">
                <div slot="media">
                    <img :src="props.firstRelease.authorImg" />
                </div>
                <h3>
                    <a :href="props.firstRelease.url" target="_blank">
                        First release
                    </a>
                </h3>
                <p class="wa-caption">
                    <i class="hn hn-calender-solid"></i>
                    {{ new Date(props.firstRelease.dateStr).toLocaleString() }}
                </p>
                <b>{{ props.firstRelease.author }}</b>
                <br />
                <b>{{ props.firstRelease.name }}</b>
                <div>
                    <wa-badge
                        appearance="outlined"
                        v-for="r in releaseReaction(props.firstRelease)"
                    >
                        {{ r.icon }} {{ r.count }}
                    </wa-badge>
                </div>
                <br />
                <!-- {{ props.firstRelease.body }} -->
            </wa-card>
            <div class="p-releases__details">
                <wa-card>
                    <h3>
                        {{ props.total }} release(s) in
                        {{ props.diff.days }}
                        day(s)
                    </h3>
                </wa-card>
            </div>
            <wa-card orientation="horizontal">
                <div slot="media">
                    <img :src="props.lastRelease.authorImg" />
                </div>
                <h3>
                    <a :href="props.lastRelease.url" target="_blank">
                        Last release
                    </a>
                </h3>
                <p class="wa-caption">
                    <i class="hn hn-calender-solid"></i>
                    {{ new Date(props.lastRelease.dateStr).toLocaleString() }}
                </p>
                <b>{{ props.lastRelease.author }}</b>
                <br />
                <b>{{ props.lastRelease.name }}</b>
                <div>
                    <wa-badge
                        appearance="outlined"
                        v-for="r in releaseReaction(props.lastRelease)"
                    >
                        {{ r.icon }} {{ r.count }}
                    </wa-badge>
                </div>
                <!-- {{ props.lastRelease.body }} -->
            </wa-card>
        </template>
        <div v-else>
            <h1>No published releases</h1>
            <wa-skeleton></wa-skeleton>
            <wa-skeleton></wa-skeleton>
            <wa-skeleton></wa-skeleton>
        </div>
    </div>
</template>

<script lang="ts" setup>
import type { GithubCommitDiff, GithubRelease } from "@/types/github"

const props = defineProps<{
    firstRelease: GithubRelease | null
    lastRelease: GithubRelease
    diff: GithubCommitDiff
    total: number
}>()

// function isEmpty(r: GithubRelease) {
//     return r.reactions.total === 0
// }

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
    height: 300px;
    background: rgba(255, 0, 0, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 20px;
    margin-bottom: 20px;
}
.p-releases__details wa-card {
    display: inline-block;
}
</style>
