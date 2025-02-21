<script setup lang="ts">
import type { DialogRootEmits, DialogRootProps } from 'radix-vue'
import { DialogContent, DialogPortal, DialogRoot, useForwardPropsEmits } from 'radix-vue'

const props = defineProps<DialogRootProps>()
const emits = defineEmits<DialogRootEmits>()

const forwarded = useForwardPropsEmits(props, emits)
</script>

<template>
  <DialogRoot v-slot="{ open }" v-bind="forwarded">
    <slot name="trigger" />
    <DialogPortal>
      <div :class="[$style.overlay, { 'modal d-block bg-black': open }]">
        <DialogContent class="modal-dialog modal-dialog-centered" v-bind="forwarded" :aria-describedby="undefined">
          <div class="modal-content">
            <slot name="header" />
            <div class="modal-body">
              <slot />
            </div>
            <slot name="footer" />
          </div>
        </DialogContent>
      </div>
    </DialogPortal>
  </DialogRoot>
</template>

<style module>
.overlay {
  --bs-bg-opacity: .5;
}
</style>
