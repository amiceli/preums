<template>
    <Layout>
        <header class="is--flex">
            <div>
                <i class="hn hn-analytics"></i>
            </div>
            <div>
                <div class="stats__title">
                    <h1>
                        <b>Preums</b>, show me languages
                        <b class="for--info">stats</b> and
                        <b class="for--error">history</b>.
                    </h1>
                </div>
                <h3>
                    Thanks to
                    <a
                        target="_blank"
                        href="https://github.com/github/innovationgraph"
                        class="for--info"
                    >
                        innovationgraph
                    </a> for stats.
                </h3>
            </div>
        </header>

        <LocationStats />
        <br>
        <WorldStates />
    </Layout>
</template>

<script setup lang="ts">
import { onBeforeMount } from "vue"
import Layout from "@/components/Layout.vue"
import LocationStats from "@/modules/langStats/components/LocationStats.vue"
import WorldStates from "@/modules/langStats/components/WorldStates.vue"
import { useLangStats } from "@/modules/langStats/store/useLangStats"
import type { LangStats } from "@/types/github.d"

const props = defineProps<{ langs: LangStats[] }>()
const { statsStore } = useLangStats()

onBeforeMount(() => {
    statsStore.updateLangStats(props.langs)

    const topProgrammingLang = props.langs.at(0)

    if (topProgrammingLang) {
        statsStore.selectType(topProgrammingLang.type)
        // statsStore.chooseLang(topProgrammingLang.name)
    }
})
</script>

<style scoped>
header {
    gap: 40px;
    margin-top: 40px;
    margin-bottom: 40px;
    display: flex;
    align-items: center;

    h1 {
        margin-bottom: 0;
    }
    i {
        font-size: 100px;
    }
}

.lang__stats {
    display: grid;
    grid-template-columns: auto 20px 1fr;
}

.stats__title {
    display: flex;
    align-items: center;
    gap: 30px;

    h1 {
        margin-bottom: 0;
    }
}
</style>
