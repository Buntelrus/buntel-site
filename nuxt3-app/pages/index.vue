<template>
  <div>
    <h1>
      <p>Hello,</p>
      <p>I'm Julian,</p>
      <p><span class="intend">web developer</span></p>
    </h1>
    <Contact/>
  </div>
</template>

<script lang="ts">
import {Vue} from "vue-class-component"
import {state} from "~/mystore";

export default class index extends Vue {
  get state() {
    return state
  }
  mounted() {
    // this.log()
    // this.wow()
  }
  log() {
    console.log('something')
  }
  wow() {
    // console.log('wow')
  }
}
</script>

<style scoped lang="sass">
</style>
