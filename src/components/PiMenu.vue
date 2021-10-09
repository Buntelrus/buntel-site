<template>
  <div class="modal" :class="{'is-active': isActive}">
    <div class="modal-content has-text-centered">
      <menu class="pi-menu has-text-centered" :style="{'--a': numberItems}">
        <router-link class="button" v-for="(item, i) in menu" :key="item.url"
           :to="item.url" :target="item.newTab ? '_blank' : ''"
           :class="{'is-hovered': hoveredItem === i}"
           :style="{'--i': i + 1}">
          {{ item.text }}
        </router-link>
      </menu>
    </div>
  </div>

</template>

<script lang="ts">
import {Options, Vue} from "vue-class-component"
import {IMenu} from "@/utils/api"

@Options({
  props: ['menu']
})
export default class Card extends Vue {
  menu: IMenu[] = []
  isActive = true
  hoveredItem: number|null = null

  onMouseMove!: (event: MouseEvent) => void
  onMouseDown!: (event: MouseEvent) => void

  get numberItems() {
    return this.menu.length
  }

  created() {
    window.addEventListener('keydown', event => {
      if (event.key === 'Escape') {
        this.toggleModal()
      }
    })
    this.onMouseMove = event => this.hoveredItem = this.calculateItemIndex(event)
    this.onMouseDown = event => {
      this.toggleModal()
      const url = this.menu[this.calculateItemIndex(event)].url
      this.$router.push(url)
    }

    this.activate()
  }

  calculateItemIndex(event: MouseEvent) {
    const width = document.documentElement.clientWidth,
        height = document.documentElement.clientHeight
    const x = width / 2, y = height / 2
    const bDeg = 90 / 2
    let chapter = 0 //there are 8 chapters each 45°

    const a = x - event.clientX,
        b = y - event.clientY

    const ap = Math.abs(a) / x * 100,
        bp = Math.abs(b) / y * 100

    let apGtBp = false

    let p = ap / bp
    if (p > 1) {
      p = bp / ap
      apGtBp = true
    }

    //top-right is default
    if (a < 0 && b < 0) {
      //bottom-right
      if (apGtBp) {
        chapter = 2
      } else {
        chapter = 3
      }
    } else if (a > 0 && b < 0) {
      //bottom-left
      if (apGtBp) {
        chapter = 5
      } else {
        chapter = 4
      }
    } else if (a > 0 && b > 0) {
      //top-left
      if (apGtBp) {
        chapter = 6
      } else {
        chapter = 7
      }
    } else {
      //top-right is default
      if (apGtBp) {
        chapter = 1
      }
    }

    const subtract = chapter % 2 === 1
    const tmpDeg = p * bDeg
    const deg = subtract ? chapter * bDeg + bDeg - tmpDeg : chapter * bDeg + tmpDeg

    const dPerItem = 360 / this.numberItems
    return Math.round(deg / dPerItem)
  }

  toggleModal() {
    this.isActive = !this.isActive
    if (this.isActive) {
      this.activate()
    } else {
      this.deactivate()
    }
  }

  activate() {
    console.log('activate')
    window.addEventListener('mousemove', this.onMouseMove)
    window.addEventListener('mousedown', this.onMouseDown)
  }
  deactivate() {
    console.log('deactivate')
    window.removeEventListener('mousemove', this.onMouseMove)
    window.removeEventListener('mousedown', this.onMouseDown)
  }
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
    --p: calc(1turn / var(--a)) /* turn per item */

    position: relative
    display: inline-block
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
      --turn: calc(var(--i) * var(--p) - var(--p) - .25turn)
      transform: rotate(var(--turn)) translate(var(--r)) rotate(calc(1turn - var(--turn)))
</style>
