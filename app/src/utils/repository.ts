import type { Location } from '~/utils/models/location'
import { z } from 'zod'
import { locationSchema } from '~/utils/models/location'
import { createFetch } from './fetch'

// eslint-disable-next-line antfu/top-level-function
export const createLocationsRepository = () => {
  const fetch = createFetch('/locations', {
    headers: {
      Accept: 'application/json',
    },
  })

  return {
    async findAll() {
      const response = await fetch('/')
      const data = await response.json()

      return z.array(locationSchema).parse(data)
    },

    async findAllByParentId(parentId: Location['id']) {
      const response = await fetch(`/${parentId}`)
      const data = await response.json()

      return z.array(locationSchema).parse(data)
    },
  }
}
