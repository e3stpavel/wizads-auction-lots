<script setup lang="ts">
import { DialogClose } from 'radix-vue'
</script>

<template>
  <div class="modal-footer">
    <DialogClose class="btn btn-secondary">
      Cancel
    </DialogClose>
    <slot />
  </div>
</template>
