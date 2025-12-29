<template>
    <Layout>
        <div class="lang__history" v-for="g in props.groups" :key="g.apiId">
            <div class="history__title">
                <h2>
                    {{ g.name }}
                    <br>
                    <small>
                        {{ g.languages.length }} languages
                    </small>
                </h2>
            </div>
            <div class="history__wall">
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
.lang__history {
    display: grid;
    grid-template-columns: 200px 1fr;
    align-items: center;
    margin-top: 50px;
    margin-bottom: 50px;

    .history__title {
        display: flex;
        height : 100%;
        align-items: center;
        justify-content: center;
    }
    .history__wall {}
}
</style>
