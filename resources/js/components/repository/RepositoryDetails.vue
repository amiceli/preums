<template>
    <p class="wa-caption-s">
        <i class="hn hn-calender-solid"></i>
        {{ new Date(props.repository.createdAtStr).toLocaleDateString() }}
        &nbsp;-&nbsp;
        <i class="hn hn-star-solid"></i>
        {{ props.repository.stars }}
        &nbsp;-&nbsp;
        {{ props.repository.forks }} fork(s)
    </p>
</template>

<script lang="ts" setup>
import type { GithubRepository } from "@/types/main"

const props = defineProps<{
    repository: GithubRepository
}>()
</script>
