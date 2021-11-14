<template>
  <div>
    <button :disabled="pending" @click="refresh">
      Refrash Data
    </button>
  </div>
</template>

<script setup>
const refresh = function () { console.log('refresh') }
</script>
