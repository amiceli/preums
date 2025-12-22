<template>
    <div class="world__stats">
        <!-- <h1>
            Oldest and starred repository for
            <b class="for--info">{{ selectedLang }}</b>
        </h1> -->
        <div
            class="repo__stats"
            v-if="starredRepo && oldestRep && !isLoading"
        >
            <div>
                <h3>
                    Most starred repository
                </h3>
                <RepositoryCard
                    :repository="starredRepo"
                    show-avatar
                />
            </div>
            <div>
                <h3>
                    Oldest repository
                    <i class="hn hn-info-circle-solid" id="for-old"></i>
                </h3>
                <wa-tooltip for="for-old">
                    Based on <i>updated_at</i>
                </wa-tooltip>

                <RepositoryCard
                    :repository="oldestRep"
                    show-avatar
                />
            </div>
        </div>
        <div v-else-if="isLoading">
            <br>
            <wa-progress-bar indeterminate></wa-progress-bar>
        </div>
        <div v-else>
            <br>
            <Skeleton />
        </div>
    </div>
</template>

<script lang="ts" setup>
import axios from "axios"
import { ref, watch } from "vue"
import { useLangStats } from "@/modules/langStats/store/useLangStats"
import type { GithubRepository } from "@/types/github"
import Skeleton from "@/components/common/Skeleton.vue"
import RepositoryCard from "@/components/RepositoryCard.vue"

const { selectedLang } = useLangStats()
const oldestRep = ref<GithubRepository | null>(null)
const starredRepo = ref<GithubRepository | null>(null)
const isLoading = ref<boolean>(false)

async function getStareedRepo() {
    const { data } = await axios.post<GithubRepository>("/strred-stars", {
        lang: selectedLang.value,
    })
    starredRepo.value = data
}

async function getOldestRepo() {
    const { data } = await axios.post<GithubRepository>("/old-stars", {
        lang: selectedLang.value,
    })
    oldestRep.value = data
}

watch(
    () => selectedLang.value,
    async () => {
        isLoading.value = true

        await Promise.all([getOldestRepo(), getStareedRepo()])

        setTimeout(() => {
            isLoading.value = false
        }, 750)
    },
)
</script>

<style scoped>
wa-progress-bar {
    width: 400px;
}
.repo__stats {
    display: flex;
    margin-top: 40px;
    justify-content: center;
    gap: 100px;

    h3 {
        text-align: center;
    }

    h3 i {
        vertical-align: middle;
    }
}
</style>
