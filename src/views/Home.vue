<template>
  <h1>
    <p>Hello,</p>
    <p>I'm Julian,</p>
    <p><span class="intend">web developer</span></p>
  </h1>
</template>

<script lang="ts">
import { Options, Vue } from "vue-class-component"

@Options({
  components: {},
})
export default class Home extends Vue {}
</script>
