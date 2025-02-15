<script setup lang="ts">
import { TreeItem, TreeRoot } from 'radix-vue'

const items = [
  {
    title: 'North America',
    children: [
      {
        title: 'USA',
        children: [
          { title: 'California, CA' },
          { title: 'Texas, TX' },
          {
            title: 'Florida, FL',
            children: [
              { title: 'Miami' },
            ],
          },
        ],
      },
      {
        title: 'Canada',
      },
    ],
  },
  {
    title: 'Europe',
    children: [
      {
        title: 'Germany',
        children: [
          { title: 'Frankfurt' },
        ],
      },
    ],
  },
  {
    title: 'Asia',
  },
]
</script>

<template>
  <TreeRoot
    v-slot="{ flattenItems }"
    :items="items"
    :get-key="(item) => item.title"
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
    >
      <li class="list-group-item list-group-item-action focus-ring" :class="{ active: isSelected }">
        <div class="d-flex align-items-center justify-content-between gap-2">
          <div class="d-grid gap-2" :class="$style.container">
            <template v-if="item.hasChildren">
              <i v-if="isExpanded" class="bi bi-folder2-open" />
              <i v-else class="bi bi-folder2" />
            </template>
            <span class="fw-medium text-truncate" :class="$style.title">{{ item.value.title }}</span>
          </div>
          <span class="fw-normal text-end" :class="$style.price">20$</span>
        </div>
      </li>
    </TreeItem>
  </TreeRoot>
</template>

<style module>
.tree {
  font-size: 0.875rem;
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
