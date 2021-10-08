<template>
  <div class="modal" :class="{'is-active': isActive}">
    <input type="number" v-model="numberItems">
    <input type="number" v-model="iRotate1">
    <input type="number" v-model="iTranslate">
    <input type="number" v-model="iRotate2">
<!--    <div class="modal-background"></div>-->
    <div class="modal-content">
      <menu class="pi-menu has-text-centered" :style="{'--a': numberItems}">
<!--        <a class="button" :style="style1">Item 1x</a>-->
<!--        <a v-for="(item, i) in itmes.slice(1)" :key="item" class="button" :style="{'&#45;&#45;i': i + 2}"-->
        <a v-for="(item, i) in itmes" :key="item" class="button" :style="{'--i': i + 1}"
          @click="toggleHover">
          {{ item }}
        </a>
      </menu>
    </div>
  </div>

</template>

<script lang="ts">
import {Options, Vue} from "vue-class-component"

@Options({
})
export default class Card extends Vue {
  numberItems = 8
  isActive = true
//   rotate(
//   .25turn) translate(6.66em) rotate(
// -0.25turn);
  iRotate1 = -25
  iRotate2 = 25
  iTranslate = 666

  get itmes() {
    const arr = []
    for (let i = 0; i < this.numberItems; i++) {
      arr.push(`Item ${i + 1}`)
    }
    return arr
  }

  get rotate1() {
    return `${this.iRotate1 / 100}turn`
  }

  get rotate2() {
    return `${this.iRotate2 / 100}turn`
  }

  get translate() {
    return `${this.iTranslate / 100}em`
  }

  get style1() {
    return {
      '--i': 1,
      transform: `rotate(${this.rotate1}) translate(${this.translate}) rotate(${this.rotate2})`
    }
  }

  created() {
    window.addEventListener('keydown', event => {
      if (event.key === 'Escape') {
        this.toggleModal()
      }
    })
    const { width, height } = document.body.getBoundingClientRect()
    const x = width / 2, y = height / 2
    window.addEventListener('mousemove', event => {
      event.clientX
      event.clientY

      if (event.clientX > x) {
        console.log('left')
      } else {
        console.log('right')
      }
      if (event.clientY < y) {
        console.log('top')
      } else {
        console.log('bottom')
      }
    })
  }

  toggleModal() {
    this.isActive = !this.isActive
  }

  toggleHover(event: PointerEvent) {
    const element = event.target as HTMLElement
    element.classList.toggle('is-hovered')
  }

  // get tang() {
  //   // 2x radius of container
  //   // + 2 image size / 2
  // }
}
</script>

<style lang="sass">
  // help from: https://stackoverflow.com/questions/12813573/position-icons-into-circle

  $spaceBetween: 1
  $itemWidth: 6em
  $itemHeight: 2.5em
  $itemMax: max($itemWidth, $itemHeight)
  $itemMin: min($itemWidth, $itemHeight)

  .pi-menu
    --is: 2.5em /* item size */
    --iw: #{$itemWidth}
    --ih: #{$itemHeight}
    --imax: #{$itemMax}
    --imin: #{$itemMin}
    --rel: #{$spaceBetween} /* 1 = one item size */
    --r: calc(.5*(1 + var(--rel))*var(--imax)/(3.60/var(--a))) /* circle radius */
    --s: calc(2*var(--r) + var(--imax)) /* container size */

    position: relative
    width: var(--s)
    height: var(--s)
    //background: silver

    a
      position: absolute
      display: block
      top: 50%
      left: 50%
      margin: -.5*$itemHeight -.5*$itemWidth
      width: var(--iw)
      height: var(--ih)
      --turn: calc(var(--i) * 1turn / var(--a))
      transform: rotate(var(--turn)) translate(var(--r)) rotate(calc(-1*var(--turn)))
</style>
