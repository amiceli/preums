<template>
    <wa-card>
        <h1>Commits</h1>
        {{ props.commirs }}
    </wa-card>
</template>

<script lang="ts" setup>
import type { GithubCommit } from "@/types/github"

const props = defineProps<{
    commirs: GithubCommit[]
}>()
</script>

<style scoped>
wa-badge {
    margin-right: 10px;
    margin-bottom: 10px;
}
</style>
