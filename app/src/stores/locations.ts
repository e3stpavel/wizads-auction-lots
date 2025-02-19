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

  async function createLocation(newLocation: unknown) {
    if (!selectedLocation.value) {
      const location = await repository.create(newLocation)
      locations.push(location)
      return
    }

    const location = await repository.createByParentId(selectedLocation.value.id, newLocation)
    
    if (!selectedLocation.value.locations)
      selectedLocation.value.locations = []
    
    selectedLocation.value.locations.push(location)
  }

  return {
    locations,
    selectedLocation,
    getRootLocations,
    getLocationsByParent,
    createLocation,
  }
})
