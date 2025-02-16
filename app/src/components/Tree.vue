<script setup lang="ts">
import type { TreeItemSelectEvent, TreeItemToggleEvent } from 'radix-vue'
import type { Location } from '~/utils/models/location'
import { storeToRefs } from 'pinia'
import { TreeItem, TreeRoot } from 'radix-vue'
import { onMounted, reactive, ref } from 'vue'
import { useLocationsStore } from '~/stores/locations'

const nf = new Intl.NumberFormat('en', {
  style: 'currency',
  currency: 'USD',
})

const expanded = ref<string[]>([])
const loadingState: Record<Location['id'], boolean> = reactive({})

const locationsStore = useLocationsStore()
const { locations, selectedLocation } = storeToRefs(locationsStore)

onMounted(async () => await locationsStore.getRootLocations())

async function handleToggle(event: TreeItemToggleEvent<Location>) {
  const treeItem = event.detail
  if (!treeItem.value)
    return

  const item = treeItem.value
  if (!treeItem.isExpanded && !item.locations && item.locations_count > 0) {
    loadingState[item.id] = true
    await locationsStore.getLocationsByParent(item)
    loadingState[item.id] = false

    expanded.value.push(`${item.id}`)
  }
}

async function handleSelect(event: TreeItemSelectEvent<Location>) {
  const treeItem = event.detail
  if (!treeItem.value)
    return

  const item = treeItem.value

  if (!treeItem.isSelected) {
    selectedLocation.value = item
    return
  }

  selectedLocation.value = null
}
</script>

<template>
  <!-- {{ JSON.stringify(locations) }} -->
  <TreeRoot
    v-if="locations.length !== 0"
    v-slot="{ flattenItems }"
    v-model:expanded="expanded"
    :items="locations"
    :get-key="(item) => `${item.id}`"
    :get-children="(item) => item.locations"
    class="list-group"
    :class="$style.tree"
  >
    <TreeItem
      v-for="item in flattenItems"
      v-slot="{ isExpanded, isSelected }"
      :key="item._id"
      :style="{ paddingLeft: `${item.level - 0.5}rem` }"
      v-bind="item.bind"
      as-child
      @toggle="handleToggle"
      @select="handleSelect"
    >
      <li class="list-group-item list-group-item-action focus-ring" :class="{ active: isSelected }">
        <div class="d-flex align-items-center justify-content-between gap-2">
          <div class="d-grid gap-2" :class="$style.container">
            <i v-if="loadingState[item.value.id]" class="bi bi-arrow-repeat" :class="$style.loadingIndicator" />
            <template v-else-if="item.value.locations_count > 0">
              <i v-if="isExpanded" class="bi bi-folder2-open" />
              <i v-else class="bi bi-folder2" />
            </template>
            <span class="fw-medium text-truncate" :class="$style.title">{{ item.value.name }}</span>
          </div>
          <span class="fw-normal text-end" :class="$style.price">{{ nf.format(item.value.price) }}</span>
        </div>
      </li>
    </TreeItem>
  </TreeRoot>
  <div v-else class="card text-center">
    <div class="card-body">
      <i class="bi bi-exclamation-triangle-fill fs-1 text-warning" />
      <h2 class="card-title fs-5">
        Nothing was found
      </h2>
      <p class="card-text text-body-secondary">
        Start by adding some locations to the list...
      </p>
    </div>
  </div>
</template>

<style module>
.tree {
  font-size: 0.875rem;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

.loadingIndicator {
  animation: spin 1s ease-in-out infinite;
}

.container {
  grid-template-columns: 0.875rem 1fr;
}

.title {
  grid-column-start: 2;
}

.price {
  font-variant-numeric: slashed-zero tabular-nums;
}
</style>
