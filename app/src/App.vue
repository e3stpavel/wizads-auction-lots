<script setup lang="ts">
import { useStorage } from '@vueuse/core'
import { Controls } from '~/components/controls'
import Tree from '~/components/Tree.vue'
import { createFetch } from '~/utils/fetch'

// this is really trivial implementation
//  without user sign up, email verification, password resets, and 2fa
const token = useStorage<string | null>('access-token', null)
const fetch = createFetch('/auth', {
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  },
})

async function handleSignIn() {
  const response = await fetch('/token', {
    method: 'POST',
    body: JSON.stringify({
      email: 'test@example.com',
      password: 'password',
    }),
  })
  token.value = await response.text()
}

function handleSignOut() {
  // TODO: an revocation can be also implemented on the backend
  token.value = null
}
</script>

<template>
  <div class="container py-5">
    <div class="row">
      <h1>
        Auction Lots
      </h1>
      <p class="text-body-secondary mw-prose">
        Explore our tree view list to see auction lots and their details, from land to baubles
      </p>
      <div>
        <div class="d-flex align-items-center gap-2">
          <button type="button" class="btn btn-outline-primary" @click="handleSignIn">
            Get access token
          </button>
          <button type="button" class="btn btn-outline-danger" @click="handleSignOut">
            Remove token
          </button>
        </div>
        <div class="mt-3">
          <p class="text-body-secondary fs-7">
            Refresh the page after clicking one of these buttons :)
          </p>
        </div>
      </div>
    </div>
    <div class="row py-3 gy-3">
      <div class="col-xl-4">
        <h3 class="fs-6">
          Filter by price
        </h3>
        <label for="filter-price" class="visually-hidden">Filter by price</label>
        <input id="filter-price" type="number" class="form-control fs-7" placeholder="Filter by price...">
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

.fs-7 {
  font-size: 0.875rem;
}

.btn {
  --bs-btn-font-size: 0.875rem;
  --bs-btn-font-weight: 500;
}
</style>
