import type { Location } from '~/utils/models/location'
import { z } from 'zod'
import { createLocationSchema, locationSchema } from '~/utils/models/location'
import { createFetch } from './fetch'

// eslint-disable-next-line antfu/top-level-function
export const createLocationsRepository = () => {
  const fetch = createFetch('/locations', {
    headers: {
      Accept: 'application/json',
      'Content-Type': 'application/json',
    },
  })

  return {
    async findAll() {
      const response = await fetch('/')
      const data = await response.json()

      return z.array(locationSchema).parse(data)
    },

    async findAllByParentId(parentId: Location['id']) {
      const response = await fetch(`/${parentId}/children`)
      const data = await response.json()

      return z.array(locationSchema).parse(data)
    },

    async create(location: unknown) {
      const input = createLocationSchema.parse(location)
      const response = await fetch('/', {
        method: 'POST',
        body: JSON.stringify(input),
      })

      const output = await response.json()
      return locationSchema.parse(output)
    },

    async createByParentId(parentId: Location['id'], location: unknown) {
      const input = createLocationSchema.parse(location)
      const response = await fetch(`/${parentId}/children`, {
        method: 'POST',
        body: JSON.stringify(input),
      })

      const output = await response.json()
      return locationSchema.parse(output)
    },
  }
}
