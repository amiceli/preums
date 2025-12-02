<template>
    <nav class="nb-navbar" role="navigation" aria-label="Main navigation">
        <a href="/" class="nb-navbar-brand" aria-label="Go to homepage">
            <img src="/logo.png" alt="logo" />
            Preums
        </a>
        <ul class="nb-navbar-nav" role="menubar">
            <li class="nb-navbar-item" role="none">
                <a
                    href="/"
                    class="nb-navbar-link active"
                    role="menuitem"
                    aria-current="page"
                >
                    Home
                </a>
            </li>
            <li class="nb-navbar-item" role="none">
                <a
                    href="https://github.com/amiceli/preums"
                    class="nb-navbar-link"
                    role="menuitem"
                    target="_blank"
                >
                    <i class="hn hn-github"></i>
                    Check code
                </a>
            </li>
            <li class="nb-navbar-item" role="none">
                <a href="/history" class="nb-navbar-link" role="menuitem">
                    <i class="hn hn-book-heart"></i>
                    Project history
                </a>
            </li>
        </ul>
    </nav>
</template>

<script setup lang="ts">
// import test from '../../../node_modules/@hackernoon/pixel-icon-library/icons/SVG/regular/git'
</script>

<style scoped>
nav img {
    width: 50px;
    vertical-align: middle;
}
</style>
