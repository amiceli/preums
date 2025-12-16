<template>
    <Layout>
        <wa-card orientation="horizontal" class="main__card">
            <div slot="media">
                <img
                    :src="props.org.avatarUrl"
                    alt="A kitten sits patiently between a terracotta pot and decorative grasses."
                />
            </div>
        </wa-card>
    </Layout>
</template>

<script setup lang="ts">
import Layout from "@/components/Layout.vue"
import type { GithubOrg } from "@/types/github"

const props = defineProps<{
    org: GithubOrg
}>()
</script>

<style scoped>
</style>
