<script setup lang="ts">
import { CreateLotDialog, MoveLotButtons, RemoveLotButton, UpdateLotDialog } from '~/components/controls'
</script>

<template>
  <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
    <div class="d-flex align-items-center flex-wrap gap-2">
      <CreateLotDialog />
      <MoveLotButtons />
    </div>
    <div class="d-flex align-items-center gap-2">
      <UpdateLotDialog />
      <RemoveLotButton />
    </div>
  </div>
</template>
