<template>
    <wa-card>
        <h1>Topics</h1>
        <wa-badge
            v-for="lang in Object.keys(props.languages)"
            :key="lang"
            appearance="filled-outlined"
            variant="neutral"
            :style="getStyle(lang)"
        >
            {{ lang }}
        </wa-badge>
    </wa-card>
</template>

<script lang="ts" setup>
import Colors from "language-colors"

const props = defineProps<{
    languages: Record<string, number>
}>()

function getStyle(lang: string) {
    const color = Colors[lang.toLowerCase()] || "var(--wa-color-on-normal, var(--wa-color-brand-on-normal))"
    const borderColor = Colors[lang.toLowerCase()] || "var(--wa-color-border-normal, var(--wa-color-brand-border-normal))"

    return `
        color: ${color};
        border-color: ${borderColor};
    `
}
</script>

<style scoped>
wa-badge {
    margin-right: 10px;
    margin-bottom: 10px;
}
</style>
