<script setup lang="ts">
import { storeToRefs } from 'pinia'
import { useLocationsStore } from '~/stores/locations'

const locationsStore = useLocationsStore()
const { selectedLocation } = storeToRefs(locationsStore)
</script>

<template>
  <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
    <div class="d-flex align-items-center flex-wrap gap-2">
      <button
        type="button"
        class="btn btn-primary"
      >
        <i class="bi bi-plus-lg" />
        Add new
      </button>
      <div class="btn-group" role="group" aria-label="Move entry in a tree">
        <button
          type="button"
          class="btn btn-outline-secondary"
          :disabled="!selectedLocation"
        >
          Move up
        </button>
        <button
          type="button"
          class="btn btn-outline-secondary"
          :disabled="!selectedLocation"
        >
          Move down
        </button>
      </div>
    </div>
    <div class="d-flex align-items-center gap-2">
      <button
        type="button"
        class="btn btn-secondary"
        :disabled="!selectedLocation"
      >
        Edit entry
      </button>
      <button
        type="button"
        class="btn btn-danger"
        :disabled="!selectedLocation"
      >
        Remove
      </button>
    </div>
  </div>
</template>
