<script setup lang="ts">
  import {computed} from "vue";
  import {state, dispatch} from "~/mystore";
  import {IPage} from "~/utils/api";

  const makeIMenu = (page: IPage) => { return { url: '/' + page.slug, text: page.title, newTab: false }}

  const navMenu = computed(() => state.pages
      .filter(page => page.slug !== 'home')
      .map(makeIMenu))

  const piMenu = computed(() => state.pages.map(makeIMenu))
</script>
<template>
  <h1>Default Layout</h1>
<!--  <p>{{ navMenu }}</p>-->
  <PiMenu v-if="piMenu.length" :menu="piMenu"/>
  <section v-if="navMenu.length" class="section">
    <Navbar :menu="navMenu"/>
  </section>
  <section class="section">
    <slot/>
  </section>
  <button @click="log">log</button>
  <Footer v-if="state.global && state.global.footer" :footer="state.global.footer"/>
</template>

<script lang="ts">
import {dispatch} from "~/mystore";

export default {
  name: "default",
  async created() {
    dispatch('fetchData')
    if (process.client) {
      console.log('client')
    }
  },
  methods: {
    log() {
      console.log(this.state)
    }
  }
}
</script>

<style scoped>

</style>
