<template>
  <div>
    <div>Hello World</div>
    <p>{{ page.content }}</p>
<!--    <Content v-for="content in page.content" :content="content" :key="`${content.__component}-${content.id}`"/>-->
  </div>
</template>

<script setup lang="ts">
import {useMeta} from "#meta";
import {useRoute} from "vue-router";
import {useNuxtApp, useState} from "#app";
import {state} from "~/mystore";

const ssrState = useState('state', () => state)

const nuxt = useNuxtApp()
const route = useRoute()

await nuxt.$dataLoaded

const page = ssrState.value.pages.find(page => page.slug === route.params.slug)
if (!page) {
  console.error(`Page '${route.params.slug}' not found`)
  // nuxt.error({ statusCode: 404, message: `Page '${route.params.slug}' not found` })
}

useMeta({
  title: 'Page title',
  meta: [
    {name: 'test-meta', content: 'muchwow'}
  ]
})
</script>
