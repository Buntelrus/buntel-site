<template>
  <menu class="pi-menu">
    <ul class="menu-list">
      <li v-for="item in itmes" :key="item"><a>{{ item }}</a></li>
    </ul>
  </menu>
  <input type="number" v-model="tang">
  <div class="tcontainer has-text-centered" :style="{'--m': 8}">
<!--    <a href='#'>-->
<!--      <img src="image_mid.jpg" alt="alt text"/>-->
<!--    </a>-->
    <a v-for="(item, i) in itmes" :key="item" :style="{'--i': i + 1}">
      {{ item }}
    </a>
    <!-- the rest of those placed on the circle -->
  </div>
</template>

<script lang="ts">
import {Options, Vue} from "vue-class-component"

@Options({
})
export default class Card extends Vue {
  numberItems = 8
  tang = 45

  get itmes() {
    const arr = []
    for (let i = 0; i < this.numberItems; i++) {
      arr.push(`Item ${i + 1}`)
    }
    return arr
  }

  // get tang() {
  //   // 2x radius of container
  //   // + 2 image size / 2
  // }
}
</script>

<style lang="sass">
  //https://stackoverflow.com/questions/12813573/position-icons-into-circle

  .pi-menu
    //background: red

  .tcontainer
    --is: 5em /* item size */
    --rel: 1 /* how much extra space we want between images, 1 = one image size */
    --r: calc(.5*(1 + var(--rel))*var(--is)/(3.60/var(--m))) /* circle radius */
    --s: calc(2*var(--r) + var(--is)) /* container size */
    position: relative
    width: var(--s)
    height: var(--s)
    background: silver /* to show images perfectly fit in container */

  .tcontainer a
    position: absolute
    top: 50%
    left: 50%
    margin: calc(-.5*var(--is))
    width: var(--is)
    height: var(--is)
    --az: calc(var(--i)*1turn/var(--m))
    transform: rotate(var(--az)) translate(var(--r)) rotate(calc(-1 * var(--az)))

    display: block
    background: rgba(0,0,0,0.1)
  img
    max-width: 100%
</style>
