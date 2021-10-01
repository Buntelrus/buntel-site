<template>
  <nav class="navbar" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
      <router-link to="/" class="navbar-item">Home</router-link>

      <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" @click="menuOpen = !menuOpen">
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
      </a>
    </div>
    <div class="navbar-menu" :class="{open: menuOpen}">
      <router-link to="/about" class="navbar-item" @click="collapseMenu">About</router-link>
    </div>
  </nav>
</template>

<script lang="ts">
import { Options, Vue } from "vue-class-component"

@Options({
  props: {
    msg: String,
  },
})
export default class Navbar extends Vue {
  msg!: string;
  menuOpen = true

  collapseMenu() {
    this.menuOpen = false
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass">
  .navbar-menu.open
    display: block
</style>
