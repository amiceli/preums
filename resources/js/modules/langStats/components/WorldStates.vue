<template>
    <div class="world__stats">
        <!-- <h1>
            Oldest and starred repository for
            <b class="for--info">{{ selectedLang }}</b>
        </h1> -->
        <div
            class="repo__stats"
            v-if="starredRepository && oldestRepository && recentRepository && !isLoading"
        >
            <div>
                <h3>
                    Most starred repository
                </h3>
                <RepositoryCard
                    :repository="(starredRepository as GithubRepository)"
                    show-avatar
                />
            </div>
            <div>
                <h3>
                    Most recent repository
                </h3>
                <RepositoryCard
                    :repository="(recentRepository as GithubRepository)"
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
                    :repository="(oldestRepository as GithubRepository)"
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
import Skeleton from "@/components/common/Skeleton.vue"
import RepositoryCard from "@/components/RepositoryCard.vue"
import { useLangStats } from "@/modules/langStats/store/useLangStats"
import { GithubRepository } from "@/types/github"

const { oldestRepository, starredRepository, isLoading, recentRepository } = useLangStats()
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
