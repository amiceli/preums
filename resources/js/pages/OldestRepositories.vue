<template>
    <Layout>
        <Marquee />

        <form @submit.prevent="submit">
            <wa-input
                label="Get history of"
                placeholder="A repository who changes your like"
                v-model="name"
                appearance="filled-outlined"
                required
            ></wa-input>
            <wa-button
                type="submit"
                :loading="isLoading"
            >Search</wa-button>
        </form>

        <div class="page__title">
            <h1>
                <i class="hn hn-book-heart"></i>
                Some parent and starred repositories
            </h1>
            <p>
                This list ranks among the oldest repositories with the most
                stars. It reminds us that it is possible to <br />
                code and improve the tech field without AI.
            </p>
        </div>

        <div class="p-most">
            <template
                v-for="p in Object.keys(props.oldestRepos)"
                :key="`year-${p}`"
            >
                <h2>
                    {{ p }}
                </h2>
                <div class="p-most__grid">
                    <div v-for="item in props.oldestRepos[p]" :key="item.id">
                        <RepositoryCard :repository="item" />
                    </div>
                </div>
            </template>
        </div>
    </Layout>
</template>

<script setup lang="ts">
import { router } from "@inertiajs/vue3"
import { ref } from "vue"
import Layout from "@/components/Layout.vue"
import Marquee from "@/components/Marquee.vue"
import RepositoryCard from "@/components/RepositoryCard.vue"
import type { GithubSearchResult } from "@/types/github"

const name = ref<string>("")
const isLoading = ref(false)

const props = defineProps<{
    oldestRepos: {
        [key: string]: GithubSearchResult["items"]
    }
}>()

function submit() {
    isLoading.value = true

    router.visit("/github/search", {
        data: {
            name: name.value,
        },
    })
}
</script>

<style scoped>
.p-most {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding-bottom: 100px;
}
.p-most__grid {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 30px;
}

h2 {
    display: block;
    margin-top: 40px;
    margin-bottom: 40px;
}

form {
    display: grid;
    grid-template-columns: 400px auto;
    align-items: end;
    justify-content: center;
    gap: 30px;
    margin-top: 100px;
    margin-bottom: 100px;
}
</style>
