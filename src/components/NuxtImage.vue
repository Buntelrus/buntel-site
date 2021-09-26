<template>
  <img :src="imageUrl" :alt="alt" :class="{'image-fit': fit}">
</template>

<script lang="ts">
import {Options, Vue} from "vue-class-component"

@Options({
  props: {
    src: String,
    alt: String,
    fit: Boolean,
  },
})
export default class NuxtImage extends Vue {
  src!: string
  alt!: string
  fit: undefined | boolean

  get imageUrl() {
    const base = process.env.API_URL || 'http://localhost:1337'
    return base + this.src
  }
}
</script>

<style lang="sass">
.image-fit
  object-fit: cover
</style>
