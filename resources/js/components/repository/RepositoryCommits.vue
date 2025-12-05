<template>
    <wa-card>
        <h2>
            Commits
            <small>How starts project</small>
        </h2>
        <table>
            <thead>
                <tr>
                    <th>When</th>
                    <th>For What</th>
                    <th>How made this</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="commit in props.commirs" :key="commit.sha">
                    <td>
                        {{ new Date(commit.dateStr).toLocaleString() }}
                    </td>
                    <th>
                        <a
                            :href="commit.url"
                            target="_blank"
                        >
                            {{ commit.message }}
                        </a>
                    </th>
                    <th>
                        {{ commit.author }}
                    </th>
                </tr>
            </tbody>
        </table>
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
