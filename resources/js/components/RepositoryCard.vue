<template>
    <wa-card class="card-overview">
        <img
            slot="media"
            :src="props.repository.owner.avatarUrl"
            alt="repo image"
        />
        <strong>
            {{ props.repository.owner.login}} /
            {{ props.repository.name }}
        </strong>
        <br />
        <p>
            {{ props.repository.description }}
        </p>

        <p class="wa-caption-s">
            <i class="hn hn-calender-solid"></i>
            {{ new Date(props.repository.createdAtStr).toLocaleDateString() }}
            &nbsp;-&nbsp;
            <i class="hn hn-star-solid"></i>
            {{ props.repository.stars }}
            &nbsp;-&nbsp;
            {{ props.repository.forks }} fork(s)
        </p>

        <wa-button
            :href="historyUrl"
            slot="footer"
            label="Rating"
            size="small"
            appearance="filled"
        >
            See history
        </wa-button>
        <wa-button
            :href="props.repository.url"
            target="_blank"
            slot="footer-actions"
            variant="brand"
            size="small"
            appearance="plain"
        >
            See repository
        </wa-button>
    </wa-card>
</template>

<script setup lang="ts">
import { computed } from "vue"
import { showRepositoryHistory } from "@/actions/App/Http/Controllers/GithubController"
import type { GithubSearchResultItem } from "@/types/github"

const props = defineProps<{
    repository: GithubSearchResultItem
}>()

const historyUrl = computed(() => {
    return showRepositoryHistory.url({
        org: props.repository.owner.login,
        repo: props.repository.name,
    })
})
</script>

<style scoped>
.nb-card-actions {
    display: flex;
    justify-content: space-between;
}
wa-card {
    width: 350px;
}
</style>
