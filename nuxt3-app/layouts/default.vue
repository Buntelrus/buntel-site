<script setup lang="ts">
  import {computed} from "vue";
  import {state, dispatch} from "~/mystore";
  import {IPage} from "~/utils/api";
  import {useNuxtApp} from "#app";

  const ssrState = useState('state', () => state)

  const nuxt = useNuxtApp()
  const makeIMenu = (page: IPage) => { return { url: '/' + page.slug, text: page.title, newTab: false }}

  const navMenu = computed(() => ssrState.value.pages
      .filter(page => page.slug !== 'home')
      .map(makeIMenu))

  const piMenu = computed(() => ssrState.value.pages.map(makeIMenu))

  // const navMenu = computed(() => mystate.pages
  //     .filter(page => page.slug !== 'home')
  //     .map(makeIMenu))
  //
  // const piMenu = computed(() => mystate.pages.map(makeIMenu))


  nuxt.provide('dataLoaded', dispatch('fetchData'))
</script>
<template>
  <div class="layout">
<!--    <h1>Default Layout</h1>-->
    <PiMenu v-if="piMenu.length" :menu="piMenu"/>
    <section v-if="navMenu.length" class="section">
      <Navbar :menu="navMenu"/>
    </section>
    <section class="section">
      <slot/>
    </section>
    <Footer v-if="ssrState.global && ssrState.global.footer" :footer="ssrState.global.footer"/>
  </div>
</template>

<style scoped>

</style>
