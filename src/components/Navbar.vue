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
      <router-link v-for="item in menu" :key="item.url"
           class="navbar-item" active-class="is-active"
           :to="item.url" @click="collapseMenu" :target="item.newTab ? '_blank' : ''">
        {{ item.text }}
      </router-link>
    </div>
  </nav>
</template>

<script setup lang="ts">
import {IMenu} from "@/utils/api"
import {ref} from "vue";

const menuOpen = ref<boolean>(false)
const {menu} = defineProps<{menu: IMenu}>()
function collapseMenu() {
    menuOpen.value = false
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass">
  .navbar-menu.open
    display: block
</style>
