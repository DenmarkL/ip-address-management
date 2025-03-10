<template>
  <Dialog 
    :visible="visible"
    @update:visible="emit('update:visible', $event)"
    :header="mode === 'edit' ? 'Edit IP Address' : 'Add New IP'"
    modal
    class="w-1/3"
  >
    <div class="flex flex-col gap-4">
      <div>
        <label class="font-medium">IP Address</label>
        <InputText v-model="ipData.ip_address" class="w-full mt-1" placeholder="Enter IP Address"/>
      </div>

      <div>
        <label class="font-medium">IP Type</label>
        <Dropdown v-model="ipData.ip_type" :options="ipTypes" class="w-full mt-1" placeholder="Select IP Type"/>
      </div>

      <div>
        <label class="font-medium">Label</label>
        <InputText v-model="ipData.label" class="w-full mt-1" placeholder="Optional Label"/>
      </div>

      <div>
        <label class="font-medium">Comment</label>
        <Textarea v-model="ipData.comment" class="w-full mt-1" rows="2" placeholder="Optional Comment"/>
      </div>
    </div>

    <template #footer>
      <Button 
        label="Cancel" 
        icon="pi pi-times" 
        class="p-button-text" 
        @click="emit('update:visible', false)" 
      />
      <Button 
        :label="mode === 'edit' ? 'Update IP' : 'Add IP'"
        :icon="mode === 'edit' ? 'pi pi-check' : 'pi pi-plus'"
        class="p-button-success"
        @click="handleSubmit"
      />
    </template>
  </Dialog>
</template>

<script setup>
import { ref, watch } from 'vue';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import Dropdown from 'primevue/dropdown';
import Button from 'primevue/button';
import api from "@/services/auth";
import { useToast } from 'primevue/usetoast';

const props = defineProps({
  visible: Boolean,
  mode: {
    type: String,
    default: 'add' // 'add' or 'edit'
  },
  ipData: {
    type: Object,
    default: () => ({ ip_address: '', ip_type: null, label: '', comment: '' })
  },
  ipTypes: Array
});

const emit = defineEmits(['update:visible', 'ipAdded', 'ipUpdated']);
const toast = useToast();

const handleSubmit = async () => {
  try {
    if (props.mode === 'add') {
      const response = await api.post('/ip-addresses', props.ipData);
      emit('ipAdded', response.data.data);
      toast.add({ severity: 'success', summary: 'Success', detail: 'IP Address added successfully!', life: 3000 });
    } else {
      const response = await api.put(`/ip-addresses/${props.ipData.id}`, props.ipData);
      emit('ipUpdated', response.data.data);
      toast.add({ severity: 'success', summary: 'Success', detail: 'IP Address updated successfully!', life: 3000 });
    }
    emit('update:visible', false);
  } catch (error) {
    if (error.response && error.response.data) {
      const { errors, message } = error.response.data;

      // Display each error in a separate toast
      if (errors) {
        Object.entries(errors).forEach(([field, messages]) => {
          messages.forEach((msg) => {
            toast.add({
              severity: 'error',
              summary: `Error in ${field}`,
              detail: msg,
              life: 5000
            });
          });
        });
      } else if (message) {
        toast.add({
          severity: 'error',
          summary: 'Error',
          detail: message,
          life: 5000
        });
      }
    } else {
      toast.add({
        severity: 'error',
        summary: 'Error',
        detail: 'An unexpected error occurred',
        life: 5000
      });
    }
  }
};
</script>
