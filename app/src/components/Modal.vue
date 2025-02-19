<script setup lang="ts">
import { DialogClose, DialogContent, DialogDescription, DialogOverlay, DialogPortal, DialogRoot, DialogTitle, DialogTrigger } from 'radix-vue'
import { ref } from 'vue'
import { useLocationsStore } from '~/stores/locations'

const isOpen = ref(false)

const locationsStore = useLocationsStore()

async function handleSubmit(event: Event) {
  console.log('here')
  const formData = new FormData(event.target as HTMLFormElement)
  const data = Object.fromEntries(formData)

  await locationsStore.createLocation(data)
  isOpen.value = false
}
</script>

<template>
  <DialogRoot v-model:open="isOpen" :aria-describedby="undefined">
    <DialogTrigger
      type="button"
      class="btn btn-primary"
    >
      <i class="bi bi-plus-lg" />
      Add new
    </DialogTrigger>
    <DialogPortal>
      <div :class="[$style.overlay, { 'modal d-block bg-black': isOpen }]">
        <DialogContent class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <div>
                <DialogTitle class="modal-title fs-5">
                  Create new location
                </DialogTitle>
              </div>
              <DialogClose class="btn-close" aria-label="Close" />
            </div>
            <div class="modal-body">
              <div class="row g-4">
                <div>
                  <form id="create-form" class="row g-3" @submit.prevent="handleSubmit">
                    <div>
                      <label for="name" class="form-label fs-7">Name</label>
                      <input id="name" type="text" name="name" class="form-control fs-7">
                    </div>
                    <div>
                      <label for="price" class="form-label fs-7">Price</label>
                      <input id="price" type="number" name="price" class="form-control fs-7">
                    </div>
                  </form>
                </div>
                <div>
                  <div class="alert alert-warning fs-7" role="alert">
                    <span class="fw-bold">Tip!</span> To create new location at the root level, make sure you deselect your location selection in the tree view.
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <DialogClose class="btn btn-secondary">
                Cancel
              </DialogClose>
              <button type="submit" form="create-form" class="btn btn-primary">
                Save changes
              </button>
            </div>
          </div>
        </DialogContent>
      </div>
    </DialogPortal>
  </DialogRoot>
</template>

<style module>
.overlay {
  --bs-bg-opacity: .5;
}
</style>
