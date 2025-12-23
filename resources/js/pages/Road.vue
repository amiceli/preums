<template>
    <Layout>
        <div class="main__title">
            <div>
                <img src="/logo.png" alt="logo" />
            </div>
            <div>
                <h1>
                    <b class="for--info"> Preums </b>
                    history, framework, discover
                </h1>
            </div>
        </div>
        <br />
        <h1>Built on</h1>
        <p>
            I have already worked with several frameworks. But, for me, Laravel and Vue are best. <br>
            And Inertia is a beautiful fusion ❤️. <br>
            Finish with Sqlite, best solution when we need a little database.
        </p>
        <div class="road__repos">
            <div v-for="b in props.builtOn" :key="b.name">
                <RepositoryCard :repository="b" show-avatar />
            </div>
        </div>
        <h1>Tools to make beautiful code</h1>
        <p>
            After lot of years with Eslint, Biome has appeared. <br>
            As long as I can get rid of the semicolons and use four spaces, it's perfect. <br>
            First project with Pint, perfect choice.
        </p>
        <div class="road__repos">
            <div v-for="b in props.beautifulCode" :key="b.name">
                <RepositoryCard :repository="b" show-avatar />
            </div>
        </div>
        <h1>Make beautiful UI / UX</h1>
        <div class="road__repos">
            <div v-for="b in props.UiUx" :key="b.name">
                <RepositoryCard :repository="b" show-avatar />
            </div>
        </div>
        <h1>Keep clean repository</h1>
        <div class="road__repos">
            <div v-for="b in props.cleanRepo" :key="b.name">
                <RepositoryCard :repository="b" show-avatar />
            </div>
        </div>
    </Layout>
</template>

<script setup lang="ts">
import Layout from "@/components/Layout.vue"
import RepositoryCard from "@/components/RepositoryCard.vue"
import type { Road } from "@/types/github"

const props = defineProps<Road>()
</script>

<style scoped>
.road__repos {
    display: grid;
    grid-template-columns: auto auto auto;
    justify-content: space-between;
    gap: 30px;
}

h1 {
    margin-top: 30px;
    margin-bottom: 30px;
}

.main__title {
    display: grid;
    grid-template-columns: 200px 1fr;
    gap: 40px;
    align-items: center;
    padding-top: 40px;

    img {
        border-radius: 10px;
        width: 200px;
    }
}

h1 + p {
    font-size: 22px;
}
</style>
