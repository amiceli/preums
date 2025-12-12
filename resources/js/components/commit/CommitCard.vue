<template>
    <wa-card
        orientation="horizontal"
        class="p-card"
    >
        <div slot="media" class="p-card__media">
            <img :src="props.commit.authorImg" v-if="props.commit.authorImg" />
            <div class="skeleton-avatars" v-else>
                <wa-avatar label="Avatar with an image icon">
                    <wa-icon slot="icon" name="image" variant="solid"></wa-icon>
                </wa-avatar>
                <wa-badge variant="neutral" pill> No avatar </wa-badge>
            </div>
        </div>
        <h3>
            {{ props.label }} - <b>{{ props.commit.author }}</b>
        </h3>
        <p class="wa-caption">
            <i class="hn hn-calender-solid"></i>
            {{ new Date(props.commit.dateStr).toLocaleString() }}
        </p>
        <a :href="props.commit.url" target="_blank">
            {{ props.commit.message }}
        </a>
    </wa-card>
</template>

<script lang="ts" setup>
import type { GithubCommit } from "@/types/github"

const props = defineProps<{
    commit: GithubCommit
    label: string
}>()
</script>

<style scoped>
.p-card img {
    max-height: 150px;
}
.p-card__media {
    display: flex;
    align-items: center;
    justify-content: center;
    box-sizing: border-box;
    padding: 10px;
}
</style>
