<template>
      <Hero v-if="content.__component === 'sections.hero'" :hero="content"/>
      <section v-else-if="content.__component === 'sections.card-row'" class="section tile">
        <Card v-for="card in content.cards" :key="card.id" :card="card" class="m-1"/>
      </section>
      <Richtext v-else-if="content.__component === 'sections.richtext'" :markdown="content.richText"/>
      <section v-else class="section">
        <div class="message is-danger">
          <div class="message-header">Unkown ContentType:</div>
          <div class="message-body">{{ content.__component }} not found</div>
        </div>
      </section>
</template>

<script lang="ts">
import Vue from 'vue'

export default Vue.extend({
  props: ['content'],
  data() {
    return {}
  },
})
</script>
