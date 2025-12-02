<template>
    <Head title="Welcome"></Head>

    <NavBar />

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

        <slot></slot>
    </main>
</template>

<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import NavBar from "./NavBar.vue";
</script>

<style scoped>
.p-title {
    display: grid;
    grid-template-columns: auto 400px auto;
    justify-content: center;
    gap: 20px;
    margin-bottom: 20px;
    margin-top: 20px;
}

main {
    padding-bottom: 100px;
}
</style>
