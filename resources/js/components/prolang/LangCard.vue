<template>
    <wa-drawer
        :open="drawerOpen"
        @wa-hide="drawerOpen = false"
        :label="props.lang.name"
        style="--size: 50vw"
    >
        <div class="drawer__section" v-if="code !== null">
            <h2>
                {{ props.lang.codeTitle }}
            </h2>
            <div class="for--code">
                <div v-html="code || ''"></div>
            </div>
        </div>

        <div class="drawer__section" v-if="props.lang.link">
            <h2>Links</h2>

            <wa-button
                v-if="props.lang.link"
                :href="props.lang.link"
                target="_blank"
            >
                Wikipedia
            </wa-button>
            &nbsp;
            <wa-button
                :href="props.lang.mainRepository"
                target="_blank"
                v-if="props.lang.mainRepository"
            >
                Main repository
            </wa-button>
        </div>

        <!--  -->
        <wa-button slot="footer" variant="brand" @click="drawerOpen = false"
            >Close</wa-button
        >
    </wa-drawer>
    <wa-card class="card-overview">
        <div slot="media">
            <img
                v-if="props.lang.pictureUrl"
                :src="props.lang.pictureUrl"
                alt="A kitten sits patiently between a terracotta pot and decorative grasses."
            />
            <wa-avatar v-else shape="square" label="Square avatar"></wa-avatar>
        </div>

        <h3>
            {{ props.lang.name }}
        </h3>
        <h4>
            <i class="hn hn-calender-solid"></i>
            {{ castYears }}
        </h4>

        <h3>Authors</h3>
        <div v-for="a in props.lang.authors" :key="a.apiId" class="for--author">
            <wa-avatar
                :image="a.pictureUrl"
                label="Square avatar"
                v-if="a.pictureUrl"
            ></wa-avatar>
            <wa-avatar shape="square" label="Square avatar" v-else></wa-avatar>
            <a :href="a.link" target="_blank" v-if="a.link">
                {{ a.name }}
            </a>
            <span v-else>
                {{ a.name }}
            </span>
        </div>

        <wa-button slot="footer" variant="brand" pill @click="showCode()">
            See details
        </wa-button>
    </wa-card>
</template>

<script lang="ts" setup>
import { codeToHtml } from "shiki"
import { ref } from "vue"
import type { ProLangLanguage } from "@/types/main"

const drawerOpen = ref<boolean>(false)
const code = ref<string | null>(null)

const props = defineProps<{
    lang: ProLangLanguage
}>()

const castYears = JSON.parse(props.lang.years).join(", ")

async function setCodeHtml(codeStr: string, lang?: string) {
    const html = await codeToHtml(codeStr, {
        // lang: props.lang.name.toLowerCase(),
        lang: lang || "javascript",
        theme: "laserwave",
    })

    code.value = html
}

async function showCode() {
    if (code.value === null) {
        if (props.lang.rawCode) {
            await setCodeHtml(props.lang.rawCode)
        } else if (props.lang.rawCodeLink) {
            const resp = await fetch(props.lang.rawCodeLink)
            const text = await resp.text()

            setCodeHtml(text)
        }
    }

    drawerOpen.value = true
}
</script>

<style lang="scss" scoped>
wa-drawer {
    .for--code {
        width: 100%;
        overflow: scroll;

        pre {
            width: 100%;
        }
    }
}

.drawer__section {
    margin-bottom: 20px;
}

.card-overview {
    width: 300px;

    h4 + div {
        margin-bottom: 20px;

        wa-button + wa-button {
            margin-top: 10px;
        }
    }

    [slot="media"] {
        max-height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;

        wa-avatar {
            height: 150px;
            width: 100%;
            margin: 0;
        }

        img {
            height: 90%;
        }
    }

    .for--author {
        margin-bottom: 10px;

        wa-avatar {
            margin-right: 10px;
        }
    }

    h3 {
        margin-bottom: 5px;
    }

    i.hn {
        vertical-align: text-bottom;
        margin-top: 10px;
    }
}
</style>
