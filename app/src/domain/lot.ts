import { z } from 'zod'

const baseSchema = z.object({
  id: z.number().positive(),
  name: z.string().min(1).max(255),
  price: z.coerce.number().positive(),
  createdAt: z.coerce.date(),
  updatedAt: z.coerce.date(),
})

export type Lot = z.infer<typeof baseSchema> & {
  containedLots?: Lot[]
}

export const lotSchema: z.ZodType<Lot> = baseSchema.extend({
  containedLots: z.lazy(() => lotSchema.array().optional()),
})

export const partialLotSchema = baseSchema.pick({
  name: true,
  price: true,
})

export type PartialLot = z.infer<typeof partialLotSchema>

export type PartialLotErrors = z.inferFlattenedErrors<typeof partialLotSchema>
