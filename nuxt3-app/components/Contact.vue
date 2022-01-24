<template>
      <div class="container">
        Contact me:
        <ul>
          <li v-if="props.mail">
            <a :href="`mailto:${props.mail}`" target="_blank">write a mail</a>
          </li>
          <li>
            <a :href="`tel:${props.phone}`">{{ props.phone }}</a>
          </li>
        </ul>
      </div>
</template>
<script lang="ts" setup>
export interface ContactProps {
  mail?: string,
  phone?: string
}

const props =  withDefaults(defineProps<ContactProps>(), {
  mail: 'julianbantel+site@gmail.com',
  phone: '+49 15736197434'
})

</script>
