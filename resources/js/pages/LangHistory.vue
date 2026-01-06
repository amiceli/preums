<template>
    <Layout>
        <div class="main__title">
            <div>
                <i class="hn hn-code"></i>
            </div>
            <div>
                <h1>
                    <b class="for--info">Preums</b>, tell me the history of
                    programming languages since the dawn of time.
                </h1>
                <p>
                    Thanks to
                    <a
                        target="_blank"
                        href="/github/leachim6/hello-world"
                    >
                        leachim6/hello-world
                    </a>
                    and
                    <a
                        target="_blank"
                        href="/github/acmeism/RosettaCodeData"
                    >
                        acmeism/RosettaCodeData
                    </a>
                    for all code samples ❤️ !
                </p>
            </div>
        </div>
        <div class="lang__form">
            <wa-input
                label="Specific year ?"
                v-model="searchYear"
                placeholder="Like 1993 form OM foot club ?"
                with-clear
                type="number"
                min="0"
            ></wa-input>
            <wa-input
                label="Search a lang"
                v-model="searchLang"
                placeholder="An awesome lang like PHP ?"
                with-clear
            ></wa-input>
        </div>
        <div class="lang__history" v-for="g in showGroups" :key="g.apiId">
            <div class="history__title">
                <h2>
                    {{ g.name }}
                </h2>
            </div>
            <div>
                <MasonryWall :items="g.languages" :column-width="300" :gap="20">
                    <template #default="{ item }">
                        <LangCard :lang="item" :key="item.apiId" />
                    </template>
                </MasonryWall>
            </div>
        </div>
    </Layout>
</template>

<script setup lang="ts">
import MasonryWall from "@yeger/vue-masonry-wall"
import { computed, ref } from "vue"
import { search } from "@/actions/App/Http/Controllers/MainController"
import Layout from "@/components/Layout.vue"
import LangCard from "@/components/prolang/LangCard.vue"
import type { YearGroup } from "@/types/main"

const searchYear = ref<string>("")
const searchLang = ref<string>("")

const props = defineProps<{
    groups: YearGroup[]
}>()

const showGroups = computed(() => {
    return props.groups
        .map((g) => {
            return {
                ...g,
                languages: g.languages.filter((l) => {
                    return l.years.includes(searchYear.value) && l.name.toLowerCase().includes(searchLang.value.toLowerCase())
                }),
            }
        })
        .filter((g) => g.languages.length > 0)
})
</script>

<style lang="scss" scoped>
.main__title {
    display: grid;
    grid-template-columns: 200px 1fr;
    gap: 20px;
    align-items: center;
    padding-top: 40px;
}

.main__title .hn-code {
    font-size: 200px;
}

.lang__form {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    align-items: center;
    justify-content: space-between;
}

.lang__history {
    display: grid;
    grid-template-columns: 200px 1fr;
    align-items: center;
    margin-top: 50px;
    margin-bottom: 50px;

    .history__title {
        display: flex;
        height : 100%;
        justify-content: center;
    }
}
</style>
