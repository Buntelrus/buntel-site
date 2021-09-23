<template>
  <section class="section">
    <Navbar/>
  </section>
  <section class="section">
    <router-view />
  </section>
</template>

<script lang="ts">
import { Options, Vue } from "vue-class-component"
import Navbar from "@/components/Navbar.vue"

@Options({
  components: { Navbar },
})
export default class App extends Vue {}
</script>

<style lang="sass">
@import "./assets/sass/main"
</style>
