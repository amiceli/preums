<template>
    <wa-card>
        <h2>
            Releases
            <small> - Published to us</small>
        </h2>
        <table>
            <thead>
                <tr>
                    <th>When</th>
                    <th>Title</th>
                    <th>Body</th>
                    <th>We loved this ?</th>
                    <th>How made this</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="release in props.releases" :key="release.name">
                    <td>
                        {{ new Date(release.dateStr).toLocaleString() }}
                    </td>
                    <td>
                        <a :href="release.url" target="_blank">
                            {{ release.name }}
                        </a>
                    </td>
                    <td>
                        <p class="release__body">
                            {{ release.body }}
                        </p>
                    </td>
                    <td>
                        <span v-if="isEmpty(release)"> Nothing </span>
                        <template v-else>
                            <span
                                v-for="(r, index) in releaseReaction(release)"
                                :key="`reaction-${index}`"
                            >
                                {{ r.icon }}
                                {{ r.count }}
                            </span>
                        </template>
                    </td>
                    <th>
                        {{ release.author }}
                    </th>
                </tr>
            </tbody>
        </table>
    </wa-card>
</template>

<script lang="ts" setup>
import { computed } from "vue"
import type { GithubRelease } from "@/types/github"

const props = defineProps<{
    releases: GithubRelease[]
}>()

function isEmpty(r: GithubRelease) {
    return r.reactions.total === 0
}

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
wa-badge {
    margin-right: 10px;
    margin-bottom: 10px;
}
.release__body {
    max-width: 400px;
}
</style>
