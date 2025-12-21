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
                    </a>
                </h3>
            </div>
        </header>

        <div class="lang__stats">
            <div>
                <LocationStats
                    :current-iso="props.currentIso"
                    :stats="props.langs"
                    :iso-list="props.isoList"
                    @change-lang="selectedLang = $event"
                />
            </div>
            <wa-divider></wa-divider>
            <div>
                <WorldStates :lang="selectedLang" />
            </div>
        </div>
    </Layout>
</template>

<script setup lang="ts">
import { ref } from "vue"
import Layout from "@/components/Layout.vue"
import LocationStats from "@/components/stats/LocationStats.vue"
import WorldStates from "@/components/stats/WorldStates.vue"
import type { LangStats } from "@/types/github.d"

const props = defineProps<{
    langs: Record<number, LangStats[]>
    currentIso: string
    isoList: string[]
}>()

const selectedLang = ref<string | null>(null)
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
    grid-template-columns: 1fr auto 1fr;
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
