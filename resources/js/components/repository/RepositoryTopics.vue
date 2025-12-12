<template>
    <wa-card>
        <h1>
            Topics
        </h1>
        <wa-badge
            v-for="topic in props.topics"
            :key="topic"
            appearance="filled-outlined"
            variant="neutral"
        >
            {{ topic }}
        </wa-badge>
    </wa-card>
</template>

<script lang="ts" setup>
const props = defineProps<{
    topics: string[]
}>()
</script>

<style scoped>
wa-badge {
    margin-right: 10px;
    margin-bottom: 10px;
}
small {
    opacity: 0.7;
}
</style>
