<script setup lang="ts">
import type { PartialLot, PartialLotErrors } from '~/domain/lot'
import { reactive, ref } from 'vue'
import { ZodError } from 'zod'
import { Dialog, DialogFooter, DialogHeader, DialogTrigger } from '~/components/common/dialog'
import { useLotsStore } from '~/stores/lots'

const isDialogOpen = ref(false)
const lotsStore = useLotsStore()

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
    await lotsStore.createLot(data)
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
        <button type="button" class="btn btn-primary">
          <i class="bi bi-plus-lg" />
          Add new
        </button>
      </DialogTrigger>
    </template>
    <template #header>
      <DialogHeader title="Create new lot" />
    </template>
    <div class="row g-4">
      <div>
        <form id="create-form" class="row g-3" @submit.prevent="handleSubmit">
          <div>
            <label for="name" class="form-label fs-7">Name</label>
            <input id="name" type="text" name="name" class="form-control fs-7" :class="{ 'is-invalid': errorState.fieldErrors.name }" required>
            <p v-if="errorState.fieldErrors.name" class="invalid-feedback">
              {{ errorState.fieldErrors.name?.[0] }}
            </p>
          </div>
          <div>
            <label for="price" class="form-label fs-7">Price</label>
            <input id="price" type="number" name="price" class="form-control fs-7" :class="{ 'is-invalid': errorState.fieldErrors.price }" required>
            <p v-if="errorState.fieldErrors.price" class="invalid-feedback">
              {{ errorState.fieldErrors.price?.[0] }}
            </p>
          </div>
        </form>
      </div>
      <div>
        <div class="alert alert-warning fs-7" role="alert">
          <span class="fw-bold">Tip!</span> To create new location at the root level, make sure you deselect your location selection in the tree view.
        </div>
      </div>
    </div>
    <template #footer>
      <DialogFooter>
        <button type="submit" form="create-form" class="btn btn-primary">
          Add to list
        </button>
      </DialogFooter>
    </template>
  </Dialog>
</template>
