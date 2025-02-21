<script setup lang="ts">
import { storeToRefs } from 'pinia'
import { computed } from 'vue'
import { useLotsStore } from '~/stores/lots'

const lotsStore = useLotsStore()
const { selectedLot } = storeToRefs(lotsStore)

const isDisabled = computed(() => !selectedLot.value)

async function handleClick(direction: 'up' | 'down') {
  await lotsStore.moveLot(direction)
}
</script>

<template>
  <div class="btn-group" role="group" aria-label="Move entry in a tree">
    <button
      type="button"
      class="btn btn-outline-secondary"
      :disabled="isDisabled"
      @click="handleClick('up')"
    >
      Move up
    </button>
    <button
      type="button"
      class="btn btn-outline-secondary"
      :disabled="isDisabled"
      @click="handleClick('down')"
    >
      Move down
    </button>
  </div>
</template>
