<template>
    <div class="p-main">
        <div>
            <i class="hn hn-angellist"></i>
        </div>
        <div class="p-main__title">
            <h1>
                <b>Preums</b>, reminds me
                that coding with
                <b ref="for-lang" class="for--lang"></b>
                is
                <b class="for--magical">magical.</b>
            </h1>
            <h3>
                <a :href="langStats.url()">
                    See languages stats
                </a>
            </h3>
        </div>
    </div>
</template>

<script lang="ts" setup>
// @ts-expect-error
import Typewriter from "typewriter-effect/dist/core"
import { onMounted, useTemplateRef } from "vue"
import { langStats } from "@/actions/App/Http/Controllers/GithubController"

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

<style scoped>
.p-main {
    display: grid;
    grid-template-columns: 200px 1fr;
    gap: 20px;
    align-items: center;
    padding-top: 40px;
}

.p-main .hn-angellist {
    font-size: 200px;
}

.for--magical {
    color : #D04542;
}
.for--lang {
    color : #3278C1;
}
</style>
