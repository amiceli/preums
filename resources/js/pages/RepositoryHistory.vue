<template>
    <Layout>
        <wa-card orientation="horizontal" class="main__card">
            <div slot="media">
                <img
                    :src="props.repository.owner.avatarUrl"
                    alt="A kitten sits patiently between a terracotta pot and decorative grasses."
                />
            </div>

            <h1>
                {{ props.repository.fullName }}
            </h1>
            {{ props.repository.description }}

            <section>
                <RepositoryDetails :repository="props.repository" />
            </section>

            <wa-button
                :href="props.repository.url"
                slot="actions"
                variant="brand"
                size="small"
                appearance="plain"
                target="_blank"
            >
                See repository
            </wa-button>
        </wa-card>

        <div class="history__topics">
            <RepositoryTopics :topics="props.topics" />
            <RepositoryLanguages :languages="props.languages" />
        </div>
        <br />
        <h1>
            Commits
        </h1>
        <RepositoryCommits
            :diff="props.commits.diff"
            :last-commit="props.commits.lastCommit"
            :first-commit="props.commits.firstCommit"
            :total="props.commits.totalCommits"
            :activity="props.commits.activity"
        />
        <br />
        <h1>
            Releases
        </h1>
        <RepositoryRelease
            :diff="props.releases.diff"
            :last-release="props.releases.lastRelease"
            :first-release="props.releases.firstRelease"
            :total="props.releases.totalReleases"
        />
    </Layout>
</template>

<script setup lang="ts">
import RepositoryCommits from "@/components/commit/RepositoryCommits.vue"
import Layout from "@/components/Layout.vue"
import RepositoryDetails from "@/components/repository/RepositoryDetails.vue"
import RepositoryLanguages from "@/components/repository/RepositoryLanguages.vue"
import RepositoryRelease from "@/components/repository/RepositoryRelease.vue"
import RepositoryTopics from "@/components/repository/RepositoryTopics.vue"
import type { GithubCommit, GithubCommitActivity, GithubCommitDiff, GithubRelease, GithubSearchResultItem } from "@/types/github"

const props = defineProps<{
    repository: GithubSearchResultItem
    commits: {
        firstCommit: GithubCommit | null
        lastCommit: GithubCommit
        middleCommit: GithubCommit | null
        diff: GithubCommitDiff
        totalCommits: number
        activity: GithubCommitActivity
    }
    releases: {
        firstRelease: GithubRelease | null
        lastRelease: GithubRelease
        diff: GithubCommitDiff
        totalReleases: number
    }
    topics: string[]
    languages: Record<string, number>
}>()

// console.debug(JSON.stringify(props))
</script>

<style scoped>
.history__topics {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.main__card {
    display: grid;
    grid-template-columns: 250px 1fr auto;
    gap: 20px;
    margin-top: 40px;
    margin-bottom: 40px;
}

section {
    margin-top: 20px;
}
</style>
