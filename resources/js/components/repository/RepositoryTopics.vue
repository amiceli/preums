<template>
    <wa-card>
        <h1>
            {{ props.topics.length > 0 ? 'Topics' : 'No topics' }}
        </h1>
        <template v-if="props.topics.length > 0">
            <wa-badge
                v-for="topic in props.topics"
                :key="topic"
                appearance="filled-outlined"
                variant="neutral"
            >
                {{ topic }}
            </wa-badge>
        </template>
        <div v-else>
            <wa-skeleton ></wa-skeleton>
            <wa-skeleton ></wa-skeleton>
        </div>
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
wa-skeleton {
    height: 1rem;
    margin-bottom: 10px;
}
wa-skeleton:last-of-type {
    width: 70%;
}
</style>
