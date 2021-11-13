<script setup lang="ts">
  import {useAsyncData} from "#app";
  import fakeApi, {IGlobal, IPage, IMenu} from "@/utils/api"

  // const { data } = await useAsyncData('pages', () => fakeApi('pages'))
  const {data} = await useAsyncData('test', () => Promise.resolve('some data'))
</script>
<template>
  <h1>Default Layout</h1>
<!--  <PiMenu :menu="piMenu"/>-->
<!--  <section class="section">-->
<!--    <Navbar :menu="navMenu"/>-->
<!--  </section>-->
<!--  <Footer v-if="global" :footer="global.footer"/>-->
  <section class="section">
    <slot/>
  </section>
  <button @click="log">log</button>
</template>

<script lang="ts">
export default {
  name: "default",
  created() {
    if (process.client) {
      console.log(data)
    }
  },
  methods: {
    log() {
      console.log('test')
        console.log(data)
    }
  }
}
</script>

<style scoped>

</style>
