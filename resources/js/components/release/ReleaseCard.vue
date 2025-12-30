<template>
    <wa-card
        orientation="horizontal"
        class="p-card"
    >
        <div slot="media" class="p-card__media">
            <img :src="props.release.authorImg" v-if="props.release.authorImg" />
            <div class="skeleton-avatars" v-else>
                <wa-avatar
                    label="Author avatar not found"
                    shape="rounded"
                >
                    <wa-icon slot="icon" name="image" variant="solid"></wa-icon>
                </wa-avatar>
            </div>
        </div>
        <h3>
            <wa-breadcrumb>
                <wa-breadcrumb-item>
                    {{ props.label }}
                </wa-breadcrumb-item>
                <wa-breadcrumb-item
                    :href="props.release.url"
                    target="_blank"
                >
                    {{ props.release.name }}
                </wa-breadcrumb-item>
            </wa-breadcrumb>
        </h3>
        <p class="wa-caption">
            <i class="hn hn-calender-solid"></i>
            {{ new Date(props.release.dateStr).toLocaleString() }}
            -
            <a :href="authorUrl" target="_blank">
                {{ props.release.author }}
            </a>
        </p>
        <div>
            <wa-badge
                appearance="outlined"
                v-for="r in releaseReaction(props.release)"
            >
                {{ r.icon }} {{ r.count }}
            </wa-badge>
        </div>
    </wa-card>
</template>

<script lang="ts" setup>
import { computed } from "vue"
import { showUserHistory } from "@/actions/App/Http/Controllers/GithubController"
import type { GithubRelease } from "@/types/main"

const props = defineProps<{
    release: GithubRelease
    label: string
}>()

const authorUrl = computed(() => {
    return showUserHistory.url({
        name: props.release.author,
    })
})

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
</script>

<style scoped>
.p-card img {
    max-height: 150px;
}
wa-avatar {
    width: 150px;
    height: 150px;
}
.p-card__media {
    display: flex;
    align-items: center;
    justify-content: center;
    box-sizing: border-box;
    padding: 10px;
}
wa-badge {
    margin-right: 20px;
}
</style>
