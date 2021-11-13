<script setup lang="ts">
  function doSomethingComposition() {
    console.log('something composition')
  }
</script>

<template>
  <div>
    <h2>Test</h2>
    <button type="button" @click="doSomethingComposition()">do something</button>
    <a href="#" type="button" @click="doSomethingComposition()">do something composition</a>
    <a href="#" type="button" @click="doSomething()">do something class</a>
  </div>
</template>

<script lang="ts">
import { Options, Vue } from "vue-class-component"

export default class test extends Vue {
  doSomething() {
    console.log('something class')
  }
}
</script>

