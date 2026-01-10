<template>
    <Layout full-widht>
        <div class="lang-family">
            <LangNetworkFilter />
            <div class="lang-family__graph">
                <v-network-graph
                    :zoom-level="0.5"
                    :nodes="graphData.nodes"
                    :edges="graphData.edges"
                    :configs="configs"
                />
            </div>
        </div>
    </Layout>
</template>

<script setup lang="ts">
import * as vNG from "v-network-graph"
import { type ForceEdgeDatum, ForceLayout, type ForceNodeDatum } from "v-network-graph/lib/force-layout"
import { onMounted, reactive } from "vue"
import Layout from "@/components/Layout.vue"
import LangNetworkFilter from "@/components/prolang/LangNetworkFilter.vue"
import { useLangGraph } from "@/components/prolang/useLangGraph"
import type { ProLangLanguage } from "@/types/main"

const props = defineProps<{
    langs: ProLangLanguage[]
}>()
const { graphStore, graphData } = useLangGraph()

onMounted(() => {
    graphStore.initGraph(props.langs)
})

const configs = reactive(
    vNG.defineConfigs({
        view: {
            layoutHandler: new ForceLayout({
                positionFixedByDrag: true,
                positionFixedByClickWithAltKey: true,
                createSimulation: (d3, nodes, edges) => {
                    // d3-force parameters
                    const forceLink = d3.forceLink<ForceNodeDatum, ForceEdgeDatum>(edges).id((d: any) => d.id)
                    return d3
                        .forceSimulation(nodes)
                        .force("edge", forceLink.distance(40).strength(0.5))
                        .force("charge", d3.forceManyBody().strength(-800))
                        .force("center", d3.forceCenter().strength(0.05))
                        .alphaMin(0.001)
                },
            }),
        },
        node: {
            normal: {
                color: (n) => {
                    return n.name === "Human" ? "#ff0000" : "#4466cc"
                },
            },
            label: {
                color: "white",
                fontSize: 20,
            },
        },
        edge: {
            // margin : 1000
        },
        path: {
            margin: 1,
        },
    }),
)
</script>

<style scoped lang="scss">
.lang-family {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap : 20px;
    height : calc(100vh - 80px);

    // &__graph {}
}
</style>
