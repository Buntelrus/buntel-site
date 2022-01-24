<template>
  <div>
    <h1>Three JS Demo:</h1>
    <canvas id="webgl"></canvas>
  </div>
</template>

<script setup lang="ts">
import {AmbientLight, BoxGeometry, Mesh, MeshBasicMaterial, PerspectiveCamera, Scene, WebGLRenderer, Clock} from 'three'
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js'
import {useNuxtApp} from "#app";
import {onMounted} from "vue";

declare const dat: any

const nuxt = useNuxtApp()

if (process.client) {
  const canvas = document.getElementById('webgl')
  if (!canvas) {
    throw 'No webgl canvas element found!'
  }

  const loader = new GLTFLoader()
  loader.load( '/three/castle_hallway/scene.gltf', function (gltf) {
    scene.add(gltf.scene)
  }, undefined, function (error) {
    console.error(error)
  })

  const { width, height } = canvas.getBoundingClientRect()
  const scene = new Scene()
  const camera = new PerspectiveCamera( 75, width / height, 0.1, 1000 )
  camera.position.y = 5

  const renderer = new WebGLRenderer({ canvas, alpha: true })
  renderer.setSize(width, height)

  const geometry = new BoxGeometry()
  const material = new MeshBasicMaterial( {color: 0x00ff00 })
  // const cube = new Mesh(geometry, material)
  // scene.add(cube)

  camera.position.y = 70

  const light = new AmbientLight( 0x404040 ) // soft white light
  light.intensity = 5
  scene.add( light )

  const pressedKeys: Array<string> = []

  window.addEventListener('keydown', event => {
    if (!pressedKeys.includes(event.key)) {
      pressedKeys.push(event.key)
    }
  })
  window.addEventListener('keyup', event => {
    if (pressedKeys.includes(event.key)) {
      pressedKeys.splice(pressedKeys.indexOf(event.key), 1)
    }
  })

  const clock = new Clock()
  function tick() {
    const timeElapsed = clock.getElapsedTime()
    const step = 0.6

    if (pressedKeys.includes('w')) {
      camera.position.z += step * timeElapsed
    }
    if (pressedKeys.includes('a')) {
      camera.position.x -= step * timeElapsed
    }
    if (pressedKeys.includes('s')) {
      camera.position.z -= step * timeElapsed
    }
    if (pressedKeys.includes('d')) {
      camera.position.x += step * timeElapsed
    }

    renderer.render(scene, camera)
    requestAnimationFrame(tick)
  }

  tick()

  onMounted(() => {
    // can't load data.gui via webpack :(
    const script = document.createElement('script')
    script.src = 'https://cdn.jsdelivr.net/gh/dataarts/dat.gui/build/dat.gui.js'
    script.type = 'text/javascript'
    document.head.append(script)

    script.addEventListener('load', () => {
      const gui = new dat.GUI()
      console.log(camera)
      gui.addFolder('Light')
      gui.addColor(light, 'color')
      gui.add(light, 'intensity')

      gui.addFolder('camera')
      gui.add(camera.position, 'x')
      gui.add(camera.position, 'y')
      gui.add(camera.position, 'z')
    })
  })
}

</script>

<style scoped lang="sass">
  #webgl
    width: 100%
</style>
