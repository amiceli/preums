<template>
    <div class="location__stats">
        <div class="stats__title">
            <h2>Show me stats for</h2>
            <wa-select
                :value="selectedType"
                @input="statsStore.selectType($event.target.value)"
            >
                <wa-option
                    v-for="t in typeList"
                    :key="t"
                    :value="t"
                    :selected="selectedType === t"
                >
                    {{ t }}
                </wa-option>
            </wa-select>
            <wa-select
                :value="selectedLang"
                @input="statsStore.chooseLang($event.target.value)"
            >
                <wa-option
                    v-for="t in langList"
                    :key="t"
                    :value="t"
                    :selected="selectedLang === t"
                >
                    {{ t }}
                </wa-option>
            </wa-select>
            <h2>language.</h2>
        </div>
        <!-- <wa-radio-group
            :value="selectedLang"
            @input="statsStore.chooseLang( $event.target.value)"
        >
            <wa-radio
                v-for="l in langList"
                :key="l"
                :value="l"
                :selected="selectedLang === l"
            >
                {{ l }}
            </wa-radio>
        </wa-radio-group> -->
    </div>
</template>

<script lang="ts" setup>
import { useLangStats } from "@/modules/langStats/store/useLangStats"

const { langList, statsStore, selectedLang, selectedType, typeList } = useLangStats()
</script>

<style scoped>
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
