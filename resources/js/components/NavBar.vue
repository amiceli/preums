<template>
    <nav class="preums-nav" role="navigation" aria-label="Main navigation">
        <a href="/" class="preums-nav__brand" aria-label="Go to homepage">
            <img src="/logo.png" alt="logo" />
            Preums
        </a>
        <div>
            <wa-button href="/" variant="neutral" appearance="accent">
                <i class="hn hn-home" slot="start"></i>
                Home
            </wa-button>

            <wa-button href="/history" variant="neutral" appearance="filled">
                <i slot="start" class="hn hn-book-heart"></i>
                Project history
            </wa-button>

            <wa-button
                href="https://github.com/amiceli/preums"
                variant="neutral"
                appearance="filled"
                target="_blank"
            >
                <i slot="start" class="hn hn-github"></i>
                Check code
            </wa-button>
        </div>
    </nav>
</template>

<script setup lang="ts"></script>

<style scoped>
.preums-nav {
    color: #26314f;
    height: 80px;
    border-bottom: #26314f 1px solid;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-left: 40px;
    padding-right: 40px;
}
.preums-nav__brand {
    font-size: 30px;
}
.preums-nav__brand img {
    width: 50px;
    vertical-align: middle;
    margin-right: 20px;
}
.preums-nav__brand {
    text-decoration: none;
}
.preums-nav__brand + div {
    display: flex;
    gap : 20px;
}
</style>
