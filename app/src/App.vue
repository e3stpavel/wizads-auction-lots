<script setup lang="ts">
import Tree from '~/components/Tree.vue'
</script>

<template>
  <div class="container py-5">
    <div class="row">
      <h1>
        Pricing per region
      </h1>
      <p class="text-body-secondary mw-prose">
        Explore our tree view list to see regional pricing details, from continents to cities
      </p>
    </div>
    <div class="row py-3">
      <div class="col-xl-4">
        <h3 class="fs-6">
          Filter by price
        </h3>
        <label for="filter-price" class="visually-hidden">Filter by price</label>
        <input id="filter-price" type="number" class="form-control" placeholder="Filter by price...">
      </div>
      <div class="col-xl-8">
        <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between mb-3">
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
              >
                Move up
              </button>
              <button
                type="button"
                class="btn btn-outline-secondary"
              >
                Move down
              </button>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <button
              type="button"
              class="btn btn-secondary"
            >
              Edit entry
            </button>
            <button
              type="button"
              class="btn btn-danger"
            >
              Remove
            </button>
          </div>
        </div>
        <Tree />
      </div>
    </div>
  </div>
</template>

<style>
.mw-prose {
  max-width: 48ch;
}

.btn {
  --bs-btn-font-size: 0.875rem;
  --bs-btn-font-weight: 500;
}
</style>
