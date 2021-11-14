<template>
  <p>Index view</p>
  <button @click="log">log</button>
  <button type="button" @click="alert('wow')">wow</button>
  <p>{{ test }}</p>
  <p>{{ fakeData }}</p>
</template>

<script lang="ts">

import {Options, Vue} from "vue-class-component"
import {useState} from "#app";
// const data = useState('fakeApiData')
const noData = 'non ono'

export default class index extends Vue {
  get test() {
    return 'test getter'
  }
  get fakeData() {
    return useState('fakeApiData')
  }
  mounted() {
    this.log()
    this.wow()
  }
  log() {
    console.log('something')
  }
  wow() {
    console.log('wow')
  }
}
</script>

<style scoped lang="sass">
</style>
