<script setup lang="ts">
import type { TreeItemSelectEvent, TreeItemToggleEvent } from 'radix-vue'
import type { Lot } from '~/domain/lot'
import { storeToRefs } from 'pinia'
import { TreeItem, TreeRoot } from 'radix-vue'
import { onMounted, reactive, ref } from 'vue'
import { useLotsStore } from '~/stores/lots'

const nf = new Intl.NumberFormat('en', {
  style: 'currency',
  currency: 'USD',
})

const expanded = ref<string[]>([])
const loadingState: Record<Lot['id'], boolean> = reactive({})

const lotsStore = useLotsStore()
const { lots, selectedLot } = storeToRefs(lotsStore)

onMounted(async () => await lotsStore.getRootLots())

async function handleToggle(event: TreeItemToggleEvent<Lot>) {
  const treeItem = event.detail
  if (!treeItem.value)
    return

  const item = treeItem.value
  if (!treeItem.isExpanded && item.containedLots && item.containedLots.length === 0) {
    loadingState[item.id] = true
    await lotsStore.getContainedLots(item)
    loadingState[item.id] = false

    expanded.value.push(`${item.id}`)
  }
}

async function handleSelect(event: TreeItemSelectEvent<Lot>) {
  const treeItem = event.detail
  if (!treeItem.value)
    return

  const item = treeItem.value

  if (!treeItem.isSelected) {
    selectedLot.value = item
    return
  }

  selectedLot.value = null
}
</script>

<template>
  <TreeRoot
    v-if="lots.length !== 0"
    v-slot="{ flattenItems }"
    v-model:expanded="expanded"
    :items="lots"
    :get-key="(item) => `${item.id}`"
    :get-children="(item) => item.containedLots"
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
            <template v-else-if="item.hasChildren">
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
      <h2 class="card-title fs-6">
        No deals were found
      </h2>
      <p class="card-text text-body-secondary fs-7">
        Start by adding new auction lots to the list...
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
