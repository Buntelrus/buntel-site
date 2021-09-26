<template>
  <footer class="footer">
    <h3 class="title is-3">{{ footer.title }}</h3>
    <div class="columns">
      <div class="column" v-for="column in footer.columns" :key="column.title">
        <div class="menu">
          <p class="menu-label">
            {{ column.title }}
          </p>
          <ul class="menu-list">
            <li v-for="link in column.links" :key="link.url">
              <a :href="link.url" :title="link.title" :target="link.newTab ? '_blank' : '_self'">{{ link.text }}</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="has-text-right">
      <span class="icon is-large" v-for="link in footer.socialMediaLinks" :key="link.type">
        <a :href="link.url">
          <i class="mdi mdi-36px" :class="`mdi-${link.type}`"></i>
        </a>
      </span>
    </div>
  </footer>
</template>

<script lang="ts">
import { Options, Vue } from "vue-class-component"
interface IFooter {}

@Options({
  props: ['footer'],
})
export default class Navbar extends Vue {
  footer!: IFooter
}
</script>

<style lang="sass">
$footer-background-color: black
$footer-color: black
//$footer-padding: 3rem

@import "~bulma/sass/layout/footer"
</style>
