import { z } from 'zod'

const idSchema = z.number().int().positive()

const baseLocationSchema = z.object({
  id: idSchema,
  name: z.string().min(1).max(255),
  price: z.coerce.number().positive(),
  item_order: z.number().int().positive(),
  parent_location_id: idSchema.nullable(),
  locations_count: z.number().int().nonnegative(),
})

export type Location = z.infer<typeof baseLocationSchema> & {
  locations?: Location[]
}

export const locationSchema: z.ZodType<Location> = baseLocationSchema.extend({
  locations: z.lazy(() => locationSchema.array()).optional(),
})

export const createLocationSchema = baseLocationSchema.pick({
  name: true,
  price: true,
  // item_order: true,
})

export type LocationInit = z.infer<typeof createLocationSchema>
