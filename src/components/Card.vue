<template>
  <div class="card">
    <div class="card-header">
      <p class="card-header-title">{{ card.title }}</p>
    </div>
    <div class="card-image">
      <figure class="image is-4by3">
        <NuxtImage :src="card.image.url" alt="Placeholder image" :fit="true" />
      </figure>
    </div>
    <content class="card-content">
      <div class="mx-2">
        {{ card.shortText }}
      </div>
    </content>
  </div>
</template>

<script lang="ts">
import { Options, Vue } from "vue-class-component"
interface ICard {}

@Options({
  props: ['card'],
})
export default class Card extends Vue {
  card!: ICard
}
</script>
