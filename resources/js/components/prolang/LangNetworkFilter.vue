<template>
    <div class="lang-network-filter">
        <h1>Filters</h1>
        <p>
            Thanks you to
            <a
                href="https://github.com/dash14/v-network-graph"
                target="_blank"
            >
                dash14/v-network-graph
            </a> for network graph ❤️.
        </p>
        <wa-input
            label="Is in family of"
            :value="hasName"
            @change="graphStore.searchByName($event.target.value)"
            with-clear
        ></wa-input>
    </div>
</template>

<script setup lang="ts">
import { useLangGraph } from "./useLangGraph"

const { graphStore, hasName } = useLangGraph()
</script>

<style scoped lang="scss">
.lang-network-filter {
    height: 60hw;
    padding: 20px;
    box-sizing: border-box;
}
</style>
