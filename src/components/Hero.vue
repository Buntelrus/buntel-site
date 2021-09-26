<template>
  <section class="hero is-fullheight">
    <div class="hero-head has-text-centered">
      <h1 class="title is-large">
        {{ hero.title }}
      </h1>
    </div>
    <div class="hero-body">
      <div class="container">
        <div class="columns">
          <div class="column box content has-text-centered">
            <ul>
              <li v-for="skill in hero.skillsLeft" :key="skill.id">
                {{ skill.name }}
              </li>
            </ul>
          </div>
          <div class="column has-text-centered is-half">
            <figure class="image is-square">
              <NuxtImage class="has-ratio is-rounded is-inline-block" :src="hero.image.url" :fit="true"/>
            </figure>
          </div>
          <div class="column box content has-text-centered">
            <ul>
              <li v-for="skill in hero.skillsRight" :key="skill.id">
                {{ skill.name }}
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="hero-footer has-text-centered">
      {{ hero.shortText }}
    </div>
  </section>
</template>

<script lang="ts">
import Vue from 'vue'

export default Vue.extend({
  props: ['hero'],
  data() {
    return {}
  }
})
</script>
