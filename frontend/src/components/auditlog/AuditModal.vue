<template>
    <Dialog 
      :visible="visible" 
      @update:visible="emit('update:visible', $event)" 
      :header="'Changes for Log ID: ' + logId"
      modal 
      class="w-1/2"
    >
      <pre class="bg-gray-800 text-white p-4 rounded-md overflow-auto">
  {{ formattedChanges }}
      </pre>
    </Dialog>
  </template>
  
  <script setup>
  import { computed } from 'vue';
  import Dialog from 'primevue/dialog';
  
  const props = defineProps({
    visible: Boolean,
    changes: {
      type: [String, Object],
      required: true
    },
    logId: {
      type: [String, Number, null],
      required: true
    }
  });
  
  const emit = defineEmits(['update:visible']);
  
  // Format changes for readability
  const formattedChanges = computed(() => {
    try {
      return JSON.stringify(
        typeof props.changes === 'string' ? JSON.parse(props.changes) : props.changes, 
        null, 
        2
      );
    } catch {
      return props.changes;
    }
  });
  </script>
  