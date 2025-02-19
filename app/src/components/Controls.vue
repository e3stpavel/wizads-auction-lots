<script setup lang="ts">
import { storeToRefs } from 'pinia'
import { useLocationsStore } from '~/stores/locations'
import Modal from './Modal.vue'

const locationsStore = useLocationsStore()
const { selectedLocation } = storeToRefs(locationsStore)
</script>

<template>
  <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
    <div class="d-flex align-items-center flex-wrap gap-2">
      <!-- <button
        type="button"
        class="btn btn-primary"
      >
        <i class="bi bi-plus-lg" />
        Add new
      </button> -->
      <Modal />
      <!-- <div class="modal d-block" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">
                Modal title
              </h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" />
            </div>
            <div class="modal-body">
              <p>Modal body text goes here.</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                Close
              </button>
              <button type="button" class="btn btn-primary">
                Save changes
              </button>
            </div>
          </div>
        </div>
      </div> -->

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
