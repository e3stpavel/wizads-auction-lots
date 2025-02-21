<script setup lang="ts">
import { useLotsStore } from '~/stores/lots'

const lotsStore = useLotsStore()

async function handleClick() {
  try {
    await lotsStore.deleteLot()
  }
  catch {
    console.error('Failed to delete')
  }
}
</script>

<template>
  <button
    type="button"
    class="btn btn-danger"
    :disabled="!lotsStore.selectedLot"
    @click="handleClick"
  >
    Remove
  </button>
</template>
