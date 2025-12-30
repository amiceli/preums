<template>
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
        <div
            v-for="a in props.lang.authors"
            :key="a.apiId"
            class="for--author"
        >
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

        <wa-button
            slot="footer"
            variant="brand"
            pill
            v-if="props.lang.link"
            :href="props.lang.link"
            target="_blank"
        >
            <i class="hn hn-wikipedia"></i>
            More Info
        </wa-button>
    </wa-card>
</template>

<script lang="ts" setup>
import type { ProLangLanguage } from "@/types/main"

const props = defineProps<{
    lang: ProLangLanguage
}>()

const castYears = JSON.parse(props.lang.years).join(", ")
</script>

<style lang="scss" scoped>
.card-overview {
    width: 300px;

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
