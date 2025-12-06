<template>
    <div class="commits__grid">
        <div ref="first">
            <wa-card v-if="props.firstRelease">
                <div slot="media">
                    <img :src="props.firstRelease.authorImg" />
                </div>
                <h3>
                    <a
                        :href="props.firstRelease.url"
                        target="_blank"
                    >
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
        </div>
        <div class="grid__diff">
            <h3>
                {{ props.total }} release(s) in
                {{ props.diff.days }}
                day(s)
            </h3>
        </div>
        <div ref="last">
            <wa-card>
                <div slot="media">
                    <img :src="props.lastRelease.authorImg" />
                </div>
                <h3>
                    <a
                        :href="props.lastRelease.url"
                        target="_blank"
                    >
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
.commits__grid {
    display: grid;
    grid-template-columns: 300px 1fr 300px;
    gap: 20px;
    margin-right: 10px;
    margin-bottom: 10px;
    justify-content: space-between;
}

wa-badge {
    margin-right: 10px;
    margin-bottom: 10px;
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
