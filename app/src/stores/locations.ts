import type { Location } from '~/utils/models/location'
import { defineStore } from 'pinia'
import { reactive, ref } from 'vue'
import { createLocationsRepository } from '~/utils/repository'

export const useLocationsStore = defineStore('locations', () => {
  const repository = createLocationsRepository()

  const locations: Location[] = reactive([])
  const selectedLocation = ref<Location | null>()

  async function getRootLocations() {
    const rootLocations = await repository.findAll()
    locations.push(...rootLocations)
  }

  async function getLocationsByParent(parent: Location) {
    parent.locations = await repository.findAllByParentId(parent.id)
  }

  return {
    locations,
    selectedLocation,
    getRootLocations,
    getLocationsByParent,
  }
})
