<template>
  <div class="root">
    <p>Index view</p>
    <button @click="log">log</button>
    <button type="button" @click="alert('wow')">wow</button>
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
