<template>
  <p>Index view</p>
  <button @click="log">log</button>
  <button type="button" @click="alert('wow')">wow</button>
  {{ test }}
</template>

<script lang="ts">

import {Options, Vue} from "vue-class-component"

export default class index extends Vue {
  get test() {
    return 'test getter'
  }
  mounted() {
    this.log()
    this.wow()
  }
  log() {
    console.log('something')
  }
  wow() {
    alert('wow')
    console.log('wow')
  }
}
</script>

<style scoped lang="sass">
</style>
