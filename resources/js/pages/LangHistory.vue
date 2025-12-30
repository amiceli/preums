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
            </div>
        </div>

        <div class="lang__history" v-for="g in props.groups" :key="g.apiId">
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
import Layout from "@/components/Layout.vue"
import LangCard from "@/components/prolang/LangCard.vue"
import type { YearGroup } from "@/types/main"

const props = defineProps<{
    groups: YearGroup[]
}>()
</script>

<style lang="scss" scoped>
.main__title {
    display: grid;
    grid-template-columns: 200px 1fr;
    gap: 20px;
    align-items: center;
    padding-top: 40px;
}

.main__title .hn {
    font-size: 200px;
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
