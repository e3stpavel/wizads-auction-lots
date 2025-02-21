<script setup lang="ts">
import { DialogClose, DialogTitle } from 'radix-vue'

interface Props {
  title: string
}

defineProps<Props>()
</script>

<template>
  <div class="modal-header">
    <div>
      <DialogTitle class="modal-title fs-5">
        {{ title }}
      </DialogTitle>
    </div>
    <DialogClose class="btn-close" aria-label="Close" />
  </div>
</template>
