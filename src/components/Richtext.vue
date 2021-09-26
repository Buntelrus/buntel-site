<template>
  <section class="section content" v-html="renderedMarkdown"></section>
</template>

<script lang="ts">
import Vue from 'vue'
const marked = require('marked')

export default Vue.extend({
  props: ['markdown'],
  data() {
    return {}
  },
  computed: {
    renderedMarkdown() {
      return marked(this.markdown)
    }
  }
})
</script>
