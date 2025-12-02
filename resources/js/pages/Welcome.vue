<template>
    <Layout>
        <form @submit.prevent="submit">
            <input
                class="nb-input default"
                placeholder="Which repository"
                v-model="name"
            />
            <button type="submit" class="nb-button blue">Orange</button>
        </form>

        <div class="p-most">
            <h1>Some most starred repositories</h1>

            <template
                v-for="p in Object.keys(props.oldestRepos)"
                :key="`year-${p}`"
            >
                <h2>
                    {{ p }}
                </h2>
                <div class="nb-grid-3">
                     <RepositoryCard
                        v-for="item in props.oldestRepos[p]"
                        :key="item.id"
                        :repostiory="item"
                    />
                </div>
            </template>
        </div>
    </Layout>
</template>

<script setup lang="ts">
import { router } from "@inertiajs/vue3";
import { ref } from "vue";
import Layout from "@/components/Layout.vue";
import RepositoryCard from "@/components/RepositoryCard.vue";
import type { GithubSearchResult } from "@/types/github";

const name = ref<string>("");

const props = defineProps<{
    oldestRepos: {
        [key: string]: GithubSearchResult["items"];
    };
}>();

function submit() {
    router.visit("/github/search", {
        data: {
            name: name.value,
        },
    });
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

h2 {
    display: block;
    margin-top: 40px;
    margin-bottom: 40px;
}
form {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 100px;
    margin-bottom: 100px;
}
</style>
