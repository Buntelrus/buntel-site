<template>
  <section class="section">
    <Navbar :menu="menu"/>
  </section>
  <section class="section">
    <router-view />
  </section>
  <Footer v-if="global" :footer="global.footer"/>
</template>

<script lang="ts">
import { Options, Vue } from "vue-class-component"
import Navbar from "@/components/Navbar.vue"
import Footer from "@/components/Footer.vue"
import fakeApi, {IGlobal, IPage, IMenu} from "@/utils/api"

@Options({
  components: { Footer, Navbar }
})
export default class App extends Vue {
  pages: IPage[] = []
  global: IGlobal|null = null
  menu: IMenu[] = []

  async created() {
    this.pages = await fakeApi('pages')
    this.global = await fakeApi('global')
    this.menu = this.pages
        .filter(page => page.slug !== 'home')
        .map(page => { return { url: '/' + page.slug, text: page.title, newTab: false }})
  }
}
</script>

<style lang="sass">
@import "./assets/sass/main"
</style>
