<template>
  <div class="modal" :class="{'is-active': isActive}">
<!--    <input type="number" v-model="numberItems">-->
<!--    <input type="number" v-model="iRotate1">-->
<!--    <input type="number" v-model="iTranslate">-->
<!--    <input type="number" v-model="iRotate2">-->
<!--    <div class="modal-background"></div>-->
    <div class="modal-content has-text-centered">
      <menu class="pi-menu has-text-centered" :style="{'--a': numberItems}">
<!--        <a class="button" :style="style1">Item 1x</a>-->
<!--        <a v-for="(item, i) in itmes.slice(1)" :key="item" class="button" :style="{'&#45;&#45;i': i + 2}"-->
        <a class="button" v-for="(item, i) in itmes" :key="item"
           :class="{'is-hovered': hoveredItem === i}"
           :style="{'--i': i + 1}"
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

  hoveredItem: number|null = null

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

    const width = document.documentElement.clientWidth,
        height = document.documentElement.clientHeight
    const x = width / 2, y = height / 2
    window.addEventListener('mousemove', event => {
      const bTurn = 25 / 2
      const bDeg = 90 / 2

      let chapter = 0 //there are 8 chapters each 12.5 turn or 45°

      const a = x - event.clientX,
          b = y - event.clientY

      const ap = Math.abs(a) / x * 100,
          bp = Math.abs(b) / y * 100

      let apGtBp = false

      console.log('ap, bp:', ap, bp)
      let p = ap / bp
      if (p > 1) {
        p = bp / ap
        apGtBp = true
      }

      const log = {
        x,
        y,
        a,
        b,
        width,
        height,
        clientX: event.clientX,
        clientY: event.clientY
      }
      // console.log(log)
      //top-right is default
      if (a < 0 && b < 0) {
        //bottom-right
        console.log('bottom-right')
        if (apGtBp) {
          chapter = 2
        } else {
          chapter = 3
        }
      } else if (a > 0 && b < 0) {
        //bottom-left
        console.log('bottom-left')
        if (apGtBp) {
          chapter = 5
        } else {
          chapter = 4
        }
      } else if (a > 0 && b > 0) {
        //top-left
        console.log('top-left')
        if (apGtBp) {
          chapter = 6
        } else {
          chapter = 7
        }
      } else {
        console.log('top-right')
        if (apGtBp) {
          chapter = 1
        }
      }

      const subtract = chapter % 2 === 1
      console.log('chapter', chapter)
      const tmpTurn = p * bTurn
      const tmpDeg = p * bDeg
      const turn = subtract ? chapter * bTurn + bTurn - tmpTurn : chapter * bTurn + tmpTurn
      const deg = subtract ? chapter * bDeg + bDeg - tmpDeg : chapter * bDeg + tmpDeg
      console.log('turn:', turn)
      console.log('deg:', deg)

      const dPerItem = 360 / this.numberItems
      this.hoveredItem = Math.round(deg / dPerItem)
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
