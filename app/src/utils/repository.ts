import type { Lot, PartialLot } from '~/domain/lot'
import { useStorage } from '@vueuse/core'
import { lotSchema, partialLotSchema } from '~/domain/lot'
import { createFetch } from './fetch'

// as I have only one entity there is no point to abstract repository further
// eslint-disable-next-line antfu/top-level-function
export const createLotsRepository = () => {
  const token = useStorage('access-token', '')

  const fetch = createFetch('/lots', {
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${token.value}`,
    },
  })

  async function findAll() {
    const response = await fetch('/')
    const json = await response.json()

    return lotSchema.array().parse(json.data)
  }

  async function findAllById(lotId: Lot['id']) {
    const response = await fetch(`/${lotId}`)
    const json = await response.json()

    return lotSchema.array().parse(json.data)
  }

  function create(lot: PartialLot, lotId?: Lot['id']): Promise<Lot>
  function create(lot: unknown, lotId?: Lot['id']): Promise<Lot>
  async function create(lot: unknown, lotId?: Lot['id']) {
    const input = partialLotSchema.parse(lot)
    const response = await fetch(`/${lotId ?? ''}`, {
      method: 'POST',
      body: JSON.stringify(input),
    })

    const output = await response.json()
    return lotSchema.parse(output.data)
  }

  function update(lotId: Lot['id'], updatedLot: PartialLot): Promise<Lot>
  function update(lotId: Lot['id'], updatedLot: unknown): Promise<Lot>
  async function update(lotId: Lot['id'], updatedLot: unknown) {
    const input = partialLotSchema.parse(updatedLot)
    const response = await fetch(`/${lotId}`, {
      method: 'PUT',
      body: JSON.stringify(input),
    })

    const output = await response.json()
    return lotSchema.parse(output.data)
  }

  async function reorderContainedLotsBy(reorderedLotIds: number[], lotId?: Lot['id']) {
    const response = await fetch(`/${lotId ?? ''}`, {
      method: 'PATCH',
      body: JSON.stringify({
        operation: 'replace',
        path: '/containedLots',
        value: reorderedLotIds,
      }),
    })

    const output = await response.json()
    return lotSchema.array().parse(output.data)
  }

  async function remove(lotId: Lot['id']) {
    await fetch(`/${lotId}`, {
      method: 'DELETE',
    })
  }

  return {
    findAll,
    findAllById,
    create,
    update,
    reorderContainedLotsBy,
    remove,
  }
}
