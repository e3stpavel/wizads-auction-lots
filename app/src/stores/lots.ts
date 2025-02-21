import type { Lot } from '~/domain/lot'
import { defineStore } from 'pinia'
import { ref } from 'vue'
import { createLotsRepository } from '~/utils/repository'

export const useLotsStore = defineStore('lots', () => {
  const repository = createLotsRepository()

  const lots = ref<Lot[]>([])
  const selectedLot = ref<Lot | null>(null)

  async function getRootLots() {
    lots.value = await repository.findAll()
  }

  async function getContainedLots(lot: Lot) {
    lot.containedLots = await repository.findAllById(lot.id)
  }

  async function createLot(data: unknown) {
    const lot = await repository.create(data, selectedLot.value?.id)

    if (!selectedLot.value) {
      lots.value.push(lot)
      return
    }

    if (!selectedLot.value.containedLots)
      selectedLot.value.containedLots = []

    selectedLot.value.containedLots.push(lot)
  }

  async function updateLot(data: unknown) {
    if (!selectedLot.value)
      return

    const lot = await repository.update(selectedLot.value.id, data)
    // this is a bit hacky, but it allows me
    //  to avoid from finding and updating the item in the tree
    Object.assign(selectedLot.value, lot)
  }

  async function deleteLot() {
    if (!selectedLot.value)
      return

    const lotId = selectedLot.value.id
    await repository.remove(lotId)

    function treeRemove(lots: Lot[]): Lot[] {
      return lots
        .filter(lot => lot.id !== lotId)
        .map((lot) => {
          const children = lot.containedLots ?? []
          if (children.length > 0) {
            const containedLots = treeRemove(children)
            return {
              ...lot,
              containedLots: containedLots.length === 0 ? undefined : containedLots,
            }
          }

          return lot
        })
    }

    lots.value = treeRemove(lots.value)
    selectedLot.value = null
  }

  // in ideal world we would like to update the state first,
  //  then using some sort of throttling or debouncing send request to API
  async function moveLot(direction: 'up' | 'down') {
    if (!selectedLot.value)
      return

    const lotId = selectedLot.value.id

    function treeFindParent(lots: Lot[], parentLot?: Lot): Lot | undefined {
      for (const lot of lots) {
        if (lot.id === lotId)
          return parentLot

        const children = lot.containedLots ?? []
        if (children.length > 0)
          return treeFindParent(children, lot)
      }
    }

    let children = lots.value
    const parent = treeFindParent(lots.value)
    if (parent) {
      children = parent.containedLots ?? []
    }

    const childrenIds = children.map(lot => lot.id)

    const index = childrenIds.indexOf(lotId)

    let newIndex = index
    if (direction === 'up' && index > 0)
      newIndex = newIndex - 1

    if (direction === 'down' && index < childrenIds.length - 1)
      newIndex = newIndex + 1

    ;[childrenIds[index], childrenIds[newIndex]] = [childrenIds[newIndex], childrenIds[index]]

    const updated = await repository.reorderContainedLotsBy(childrenIds, parent?.id)

    // TODO: this will close all the children and user need to reload them
    if (parent) {
      parent.containedLots = updated
      return
    }

    lots.value = updated
  }

  return {
    lots,
    selectedLot,
    getRootLots,
    getContainedLots,
    createLot,
    updateLot,
    deleteLot,
    moveLot,
  }
})
