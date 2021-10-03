<template>
  <div v-if="!page">loading...</div>
  <div v-else>
    <h1>{{ page.title }}</h1>
    <component v-if="pageComponent" :is="pageComponent.default"></component>
    <div v-else>{{ page.content }}</div>
  </div>
</template>

<script lang="ts">
import { defineAsyncComponent } from "vue"
import { Options, Vue } from "vue-class-component"
import fakeApi, {IPage} from "@/utils/api"

@Options({
  watch: {'$route.params.slug': 'load'},
})
export default class Navbar extends Vue {
  page: IPage|null = null
  pageComponent = null

  get slug() {
    return this.$route.params.slug
  }

  created() {
    this.load()
  }

  async load() {
    if (this.page) {
      this.page = null
    }
    this.page = (await fakeApi('pages')).find(page => page.slug === this.slug)!
    const componentName = this.page.slug.slice(0, 1).toUpperCase() + this.page.slug.slice(1)
    try {
      this.pageComponent = await import(/* webpackChunkName: "pages" */ `./${componentName}.vue`)
      console.log(this.pageComponent)
    } catch (e) {
      this.pageComponent = null
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass">
</style>
