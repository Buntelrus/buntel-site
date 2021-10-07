<template>
  <div class="about">
    <h1>I'm a full stack developer based in Germany. I have good knowledge of Javascript, Vue.js, strapi. I love new technologies</h1>
  </div>
  <Contact/>
</template>
<script>
import { Options, Vue } from "vue-class-component"
import Contact from "@/components/Contact.vue";

@Options({
  components: {Contact},
})
export default class About extends Vue {
}
</script>
