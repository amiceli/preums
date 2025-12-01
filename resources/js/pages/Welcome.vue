<template>
    <Head title="Welcome">
        <!-- <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" /> -->
    </Head>

    <nav class="nb-navbar" role="navigation" aria-label="Main navigation">
        <a href="/" class="nb-navbar-brand" aria-label="Go to homepage">
            <img src="logo.png" alt="logo" />
            Preums
        </a>
        <ul class="nb-navbar-nav" role="menubar">
            <li class="nb-navbar-item" role="none">
                <a
                    href="/"
                    class="nb-navbar-link active"
                    role="menuitem"
                    aria-current="page"
                    >Home</a
                >
            </li>
            <li class="nb-navbar-item" role="none">
                <a href="/about" class="nb-navbar-link" role="menuitem"
                    >About</a
                >
            </li>
            <li class="nb-navbar-item" role="none">
                <a href="/contact" class="nb-navbar-link" role="menuitem"
                    >Contact</a
                >
            </li>
        </ul>
    </nav>

    <main>
        <div class="p-title">
            <h1>Remind me that coding in</h1>
            <div class="nb-marquee green">
                <div class="nb-marquee-content">
                    <span>JavaScript</span>
                    <span>PHP</span>
                    <span>Rust</span>
                    <span>Go</span>
                    <span>Ruby</span>
                    <span>Python</span>
                    <span>Kotlin</span>
                    <span>Perl</span>
                    <span>Java</span>
                    <span>C#</span>
                </div>
            </div>
            <h1>is magical</h1>
        </div>

        <form @submit.prevent="submit">
            <input
                class="nb-input default"
                placeholder="Which repository"
                v-model="form.framework"
                :disabled="form.processing"
            />
            <button
                type="submit"
                class="nb-button blue"
                :disabled="form.processing"
            >
                Orange
            </button>
        </form>

        <div v-if="form.processing" class="nb-spinner dots"><span></span></div>

        <progress
            v-if="form.progress"
            :value="form.progress.percentage"
            max="100"
        >
            {{ form.progress.percentage }}%
        </progress>

        <div class="p-most">
            <h1>12 most starred repositories</h1>

            <div class="nb-grid-3">
                <div class="nb-card" v-for="p in props.oldestRepos" :key="p.id">
                    <img :src="p.owner.avatarUrl" class="nb-card-img" />
                    <div class="nb-card-content">
                        <h4 class="nb-card-title">
                            {{ p.name }}
                        </h4>
                        <p class="nb-card-text">
                            {{ p.description }}
                            <br />
                            {{ p.createdAt }}
                        </p>
                        <div class="nb-card-actions">
                            <a :href="p.url" target="_blank" class="p-link">
                                See repository
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script setup lang="ts">
// import { reactive } from 'vue';
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import type { GithubSearchResult } from "@/types/github";

const form = useForm({
    framework: null,
});

const props = defineProps<{
    oldestRepos: GithubSearchResult["items"];
}>();

function submit() {
    form.post("/github/search", {
        onSuccess: () => {
            console.debug("vier");
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
nav img {
    width: 50px;
    vertical-align: middle;
}

form {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 100px;
    margin-bottom: 100px;
}

.p-title {
    display: grid;
    grid-template-columns: auto 400px auto;
    justify-content: center;
    gap: 20px;
    margin-bottom: 20px;
    margin-top: 20px;
}
</style>
