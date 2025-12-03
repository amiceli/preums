<template>
    <div class="nb-card">
        <img :src="props.repostiory.owner.avatarUrl" class="nb-card-img" />
        <div class="nb-card-content">
            <h4 class="nb-card-title">
                {{ props.repostiory.name }}
            </h4>
            <p class="nb-card-text">
                {{ props.repostiory.description }}
            </p>
            <p class="nb-card-text">
                <i class="hn hn-calender-solid"></i>
                {{ (new Date(props.repostiory.createdAtStr)).toLocaleDateString() }}
                &nbsp;/&nbsp;
                <i class="hn hn-star-solid"></i>
                {{ props.repostiory.stars }}
                &nbsp;/&nbsp;
                {{ props.repostiory.forks }} fork(s)
            </p>
            <div class="nb-card-actions">
                <a :href="props.repostiory.url" target="_blank" class="p-link">
                    See repository
                </a>
                <a :href="props.repostiory.url" target="_blank" class="p-link">
                    See history
                </a>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import type { GithubSearchResultItem } from "@/types/github";

const props = defineProps<{
    repostiory: GithubSearchResultItem;
}>();
</script>

<style scoped>
.nb-card-actions {
    display: flex;
    justify-content: space-between;
}
</style>
