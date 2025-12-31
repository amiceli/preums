<template>
    <wa-card class="card-overview">
        <template v-if="showAvatar">
            <img
                slot="media"
                :src="props.repository.owner.avatarUrl"
                alt="repo image"
            />
            <h3>
                <RepositoryBreadCrumb :repo="props.repository" />
            </h3>
            <br />
        </template>
        <h3 slot="header" v-if="!props.showAvatar">
            <RepositoryBreadCrumb :repo="props.repository" />
        </h3>

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
            @click="goToRepository()"
            slot="footer"
            label="Rating"
            size="small"
            appearance="filled"
            :loading="loading"
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
import { router } from "@inertiajs/vue3"
import { ref } from "vue"
import { showRepositoryHistory } from "@/actions/App/Http/Controllers/MainController"
import type { GithubRepository } from "@/types/main"
import RepositoryBreadCrumb from "./repository/RepositoryBreadCrumb.vue"

const loading = ref<boolean>(false)
const props = defineProps<{
    repository: GithubRepository
    showAvatar?: boolean
}>()

function goToRepository() {
    loading.value = true

    router.visit(
        showRepositoryHistory.url({
            org: props.repository.owner.login,
            repo: props.repository.name,
        }),
    )
}
</script>

<style scoped>
.nb-card-actions {
    display: flex;
    justify-content: space-between;
}
.card-overview h3 {
    margin-bottom: 0;
}
wa-card {
    width: 350px;
}
</style>
