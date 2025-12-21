<template>
    <div class="location__stats">
        <div class="stats__title">
            <wa-select
                v-model="selectedYear"
                @change="selectedYear = $event.target.value"
            >
                <wa-option
                    v-for="y in years"
                    :key="`option-${y}`"
                    :value="y"
                    :selected="y === selectedYear"
                >
                    {{ y }}
                </wa-option>
            </wa-select>

            <h2>stats in</h2>

            <span :class="`fi fi-${props.currentIso.toLowerCase()}`"></span>

            <wa-select
                :value="props.currentIso"
                @change="goToIso($event.target.value)"
            >
                <wa-option
                    v-for="l in props.isoList"
                    :key="l"
                    :value="l"
                    :selected="l === props.currentIso"
                >
                    <span
                        slot="start"
                        :class="`fi fi-${l.toLowerCase()}`"
                    ></span>
                    {{ l }}
                </wa-option>
            </wa-select>
        </div>
        <br>
        <wa-radio-group
            label="Select a language"
            @input="$emit('change-lang', $event.target.value)"
        >
            <wa-radio
                v-for="l in props.stats[selectedYear!]"
                :key="l.name"
                :value="l.name"
            >
                {{ l.name }}
            </wa-radio>
        </wa-radio-group>
    </div>
</template>

<script lang="ts" setup>
import { router } from "@inertiajs/vue3"
import { computed, onMounted, ref } from "vue"
import { languages } from "@/actions/App/Http/Controllers/GithubController"
import type { LangStats } from "@/types/github"

const props = defineProps<{
    stats: Record<number, LangStats[]>
    currentIso: string
    isoList: string[]
}>()

const years = computed(() => {
    return Object.keys(props.stats)
        .map((u) => Number.parseInt(u, 10))
        .sort((a, b) => b - a)
})

const selectedYear = ref<number>(0)

onMounted(() => {
    setTimeout(() => {
        // fix stupid wa-select, value not work
        selectedYear.value = years.value.at(0)!
    }, 100)
})

function goToIso(iso: string) {
    router.visit(
        languages.url({
            iso,
        }),
    )
}
</script>

<style scoped>
.location__stats {
}
.stats__title {
    display: flex;
    align-items: center;
    gap: 20px;

    h2 {
        margin-bottom: 0;
    }

    wa-select {
        display: inline-block;
        width: 150px;
    }
}
</style>
