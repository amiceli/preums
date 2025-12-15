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
// @ts-expect-error
import Colors from "language-colors"

const props = defineProps<{
    languages: Record<string, number>
}>()

function getColor(lang: string) {
    return Colors[lang.toLowerCase().replace("-", "_")]?.toString() || "white"
}

function getStyle(lang: string) {
    return `border-color: ${getColor(lang)};`
}

function getLineStyle(lang: string, index: number) {
    const width = Math.round(props.languages[lang])
    const maxIndex = Object.values(props.languages).reduce((iMax, x, i, arr) => (x > arr[iMax] ? i : iMax), 0)

    return {
        [maxIndex === index ? "flex" : "width"]: maxIndex === index ? 1 : `${width > 0 ? width : 1}%`,
        backgroundColor: getColor(lang),
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
