<template>
    <div class="p-main">
        <div>
            <i class="hn hn-angellist"></i>
        </div>
        <div class="p-main__title">
            <h1>
                <b>Preums</b>, reminds me
                that coding with
                <b class="for--lang">
                    {{ currentText }}
                </b>
                is
                <b class="for--magical">magical.</b>
            </h1>
            <h3>
                <a href="/languages">
                    See languages stats
                </a>
            </h3>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { onMounted, ref } from "vue"

const langs = ["JavaScript", "PHP", "Rust", "Go", "Ruby", "Python", "Kotlin", "Perl", "Java", "C#"]
const currentText = ref<string>(langs.at(0) || '')

onMounted(() => {
    setInterval(() => {
        const item = langs[Math.floor(Math.random() * langs.length)]
        currentText.value = item
    }, 2000)
})

langs.at(0)
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
