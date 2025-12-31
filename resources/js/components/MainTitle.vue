<template>
    <div class="main__title">
        <div>
            <i class="hn hn-angellist"></i>
        </div>
        <div>
            <h1>
                <b>Preums</b>, reminds me
                that coding with
                <b ref="for-lang" class="for--info"></b>
                is
                <b class="for--error">magical.</b>
            </h1>
            <h3>
                <a :href="langHistory.url()">
                    See language histories
                </a>
            </h3>
        </div>
    </div>
</template>

<script lang="ts" setup>
// @ts-expect-error
import Typewriter from "typewriter-effect/dist/core"
import { onMounted, useTemplateRef } from "vue"
import { langHistory } from "@/actions/App/Http/Controllers/GithubController"

const props = defineProps<{ langs: string[] }>()
const forLang = useTemplateRef("for-lang")

onMounted(() => {
    new Typewriter(forLang.value, {
        strings: props.langs,
        autoStart: true,
        delay: 200,
        pauseFor: 3000,
    })
})
</script>

<style lang="scss" scoped>
.main__title {
    display: grid;
    grid-template-columns: 200px 1fr;
    gap: 20px;
    align-items: center;
    padding-top: 40px;

    .hn-angellist {
        font-size: 200px;
    }
}
</style>
