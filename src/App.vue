<template>
  <PiMenu :menu="piMenu"/>
  <section class="section">
    <Navbar :menu="navMenu"/>
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
import PiMenu from "@/components/PiMenu.vue"

@Options({
  components: {PiMenu, Footer, Navbar }
})
export default class App extends Vue {
  pages: IPage[] = []
  global: IGlobal|null = null
  navMenu: IMenu[] = []
  piMenu: IMenu[] = []

  async created() {
    this.pages = await fakeApi('pages')
    this.global = await fakeApi('global')
    const makeIMenu = (page: IPage) => { return { url: '/' + page.slug, text: page.title, newTab: false }}

    this.navMenu = this.pages
        .filter(page => page.slug !== 'home')
        .map(makeIMenu)
    this.piMenu = this.pages.map(makeIMenu)
  }
}
</script>

<style lang="sass">
@import "./assets/sass/main"
</style>
