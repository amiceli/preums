<template>
    <wa-card>
        <h2>
            Languages
        </h2>
        <wa-badge
            v-for="lang in Object.keys(props.languages)"
            :key="`badge-${lang}`"
            appearance="filled-outlined"
            variant="neutral"
            :style="getStyle(lang)"
        >
            {{ lang }}
        </wa-badge>
        <br>
        <div class="lang__charts">
            <div
            v-for="(lang, index) in Object.keys(props.languages)"
            :key="`line-${lang}`"
            :style="getLineStyle(lang, index)"
            >

            </div>
        </div>
    </wa-card>
</template>

<script lang="ts" setup>
import Chart from "chart.js/auto"
import Colors from "language-colors"
import { onMounted, useTemplateRef } from "vue"

const props = defineProps<{
    languages: Record<string, number>
}>()

function getStyle(lang: string) {
    const borderColor = Colors[lang.toLowerCase()] || "var(--wa-color-border-normal, var(--wa-color-brand-border-normal))"

    return `border-color: ${borderColor};`
}

function getLineStyle(lang: string, index: number) {
    const width = Math.round(props.languages[lang])
    const maxIndex = Object.values(props.languages).reduce((iMax, x, i, arr) => (x > arr[iMax] ? i : iMax), 0)

    console.debug(lang, index, maxIndex === index)

    if (maxIndex === index) {
        return {
            flex: "1",
            backgroundColor: Colors[lang.toLowerCase()].toString(),
        }
    }

    return {
        width: `${width > 0 ? width : 1}%`,
        backgroundColor: Colors[lang.toLowerCase()].toString(),
    }
}
</script>

<style scoped>
wa-badge {
    margin-right: 10px;
    margin-bottom: 10px;
}
small {
    opacity: 0.7;
}
.lang__charts {
    display: flex;
    margin-top: 20px;

    div {
        height: 10px;
    }
}
</style>
