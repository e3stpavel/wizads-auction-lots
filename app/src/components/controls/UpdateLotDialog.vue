<script setup lang="ts">
import type { PartialLot, PartialLotErrors } from '~/domain/lot'
import { storeToRefs } from 'pinia'
import { reactive, ref } from 'vue'
import { ZodError } from 'zod'
import { Dialog, DialogFooter, DialogHeader, DialogTrigger } from '~/components/common/dialog'
import { useLotsStore } from '~/stores/lots'

const isDialogOpen = ref(false)
const lotsStore = useLotsStore()
const { selectedLot } = storeToRefs(lotsStore)

const errorState: PartialLotErrors = reactive({
  formErrors: [],
  fieldErrors: {},
})

async function handleSubmit(event: Event) {
  errorState.formErrors = []
  errorState.fieldErrors = {}

  const formData = new FormData((event.target as HTMLFormElement))
  const data = Object.fromEntries(formData)

  try {
    await lotsStore.updateLot(data)
    isDialogOpen.value = false
  }
  catch (error) {
    if (error instanceof ZodError) {
      const flattenedError = (error as ZodError<PartialLot>).flatten()

      errorState.formErrors = flattenedError.formErrors
      errorState.fieldErrors = flattenedError.fieldErrors
    }
  }
}
</script>

<template>
  <Dialog v-model:open="isDialogOpen">
    <template #trigger>
      <DialogTrigger>
        <button type="button" class="btn btn-secondary" :disabled="!selectedLot">
          Edit entry
        </button>
      </DialogTrigger>
    </template>
    <template #header>
      <DialogHeader :title="`Change lot '${selectedLot?.name}' details`" />
    </template>
    <div class="row g-4">
      <div>
        <form id="update-form" class="row g-3" @submit.prevent="handleSubmit">
          <div>
            <label for="name" class="form-label fs-7">Name</label>
            <input
              id="name"
              type="text"
              name="name"
              class="form-control fs-7"
              :class="{ 'is-invalid': errorState.fieldErrors.name }"
              required
              :value="selectedLot?.name"
            >
            <p class="invalid-feedback">
              {{ errorState.fieldErrors.name?.[0] }}
            </p>
          </div>
          <div>
            <label for="price" class="form-label fs-7">Price</label>
            <input
              id="price"
              type="number"
              name="price"
              class="form-control fs-7"
              :class="{ 'is-invalid': errorState.fieldErrors.price }"
              required
              :value="selectedLot?.price"
            >
            <p class="invalid-feedback">
              {{ errorState.fieldErrors.price?.[0] }}
            </p>
          </div>
        </form>
      </div>
    </div>
    <template #footer>
      <DialogFooter>
        <button type="submit" form="update-form" class="btn btn-primary">
          Update lot
        </button>
      </DialogFooter>
    </template>
  </Dialog>
</template>
