<template>
    <wa-breadcrumb>
        <wa-breadcrumb-item :href="ownerUrl">
            {{ props.repo.owner.login }}
        </wa-breadcrumb-item>
        <wa-breadcrumb-item>
            {{ props.repo.name }}
        </wa-breadcrumb-item>
    </wa-breadcrumb>
</template>

<script lang="ts" setup>
import { computed } from "vue"
import { showOrganizationHistory, showUserHistory } from "@/actions/App/Http/Controllers/GithubController"
import type { GithubRepository } from "@/types/github"

const props = defineProps<{
    repo: GithubRepository
}>()

const ownerUrl = computed(() => {
    return props.repo.ownerIsOrganization
        ? showOrganizationHistory.url({
              name: props.repo.owner.login,
          })
        : showUserHistory.url({
              name: props.repo.owner.login,
          })
})
</script>
