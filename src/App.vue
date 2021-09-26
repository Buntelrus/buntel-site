<template>
  <section class="section">
    <Navbar/>
  </section>
  <section class="section">
    <router-view />
  </section>
  <Footer :footer="footer"/>
</template>

<script lang="ts">
import { Options, Vue } from "vue-class-component"
import Navbar from "@/components/Navbar.vue"
import Footer from "@/components/Footer.vue";

@Options({
  components: {Footer, Navbar },
})
export default class App extends Vue {
  footer = {
    title: 'My Footer',
    columns: [
      {
        title: 'Column 1',
        links: [{
          text: 'Link 1',
          title: 'Link1',
          url: '#',
          newTab: false,
        },
        {
          text: 'Link 2',
          title: 'Link2',
          url: '#',
          newTab: false,
        }]
      },{
        title: 'Column 2',
        links: [{
          text: 'Link 1',
          title: 'Link1',
          url: '#',
          newTab: false,
        },
        {
          text: 'Link 2',
          title: 'Link2',
          url: '#',
          newTab: false,
        }]
      }
    ],
    socialMediaLinks: [
      {
        type: 'twitter',
        link: '#'
      },{
        type: 'youtube',
        link: '#'
      },{
        type: 'facebook',
        link: '#'
      },{
        type: 'instagram',
        link: '#'
      },
    ]
  }
}
</script>

<style lang="sass">
@import "./assets/sass/main"
</style>
