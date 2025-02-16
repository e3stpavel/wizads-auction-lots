<script setup lang="ts">
import Controls from '~/components/Controls.vue'
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
    <div class="row py-3 gy-3">
      <div class="col-xl-4">
        <h3 class="fs-6">
          Filter by price
        </h3>
        <label for="filter-price" class="visually-hidden">Filter by price</label>
        <input id="filter-price" type="number" class="form-control" placeholder="Filter by price...">
      </div>
      <div class="col-xl-8">
        <div class="row g-3">
          <div class="">
            <Controls />
          </div>
          <div class="">
            <Tree />
          </div>
        </div>
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
