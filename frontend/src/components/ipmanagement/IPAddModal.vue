<template>
    <div>
      <!-- Header Section -->
      <div class="flex justify-between items-center mb-4">
        <!-- Title -->
        <h2 class="text-2xl font-bold dark:text-white text-black">
          IP Address Manager
        </h2>
        
        <!-- Add Button (Opens Modal) -->
        <Button 
          label="Add IP" 
          icon="pi pi-plus" 
          class="p-button-success px-4 py-2 text-white"
          @click="showDialog = true"
        />
      </div>
  
      <!-- Card with IP Table -->
      <Card class="shadow-lg w-full border-t border-green-500">
        <template #title>IP Table</template>
        <template #content>
          <IPTable :ipAddresses="ipAddresses"/>
        </template>
      </Card>
  
      <!-- Add IP Modal -->
      <Dialog v-model:visible="showDialog" header="Add New IP" modal class="w-1/3">
        <div class="flex flex-col gap-4">
          <div>
            <label class="font-medium">IP Address</label>
            <InputText v-model="newIP.ip_address" class="w-full mt-1" placeholder="Enter IP Address"/>
          </div>
  
          <div>
            <label class="font-medium">IP Type</label>
            <Select v-model="newIP.ip_type">
                <SelectOption v-for="type in ipTypes" :key="type.value" :value="type.value">
                    {{ type.label }}
                </SelectOption>
            </Select>
          </div>
  
          <div>
            <label class="font-medium">Label</label>
            <InputText v-model="newIP.label" class="w-full mt-1" placeholder="Optional Label"/>
          </div>
  
          <div>
            <label class="font-medium">Comment</label>
            <Textarea v-model="newIP.comment" class="w-full mt-1" rows="2" placeholder="Optional Comment"/>
          </div>
        </div>
  
        <template #footer>
          <Button label="Cancel" icon="pi pi-times" class="p-button-text" @click="showDialog = false"/>
          <Button label="Add IP" icon="pi pi-check" class="p-button-success" @click="addIPAddress"/>
        </template>
      </Dialog>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  import IPTable from '@/components/ipmanagement/IPTable.vue';
  import Card from 'primevue/card';
  import Button from 'primevue/button';
  import Dialog from 'primevue/dialog';
  import InputText from 'primevue/inputtext';
  import Textarea from 'primevue/textarea';
  import Select from "primevue/select";
  import SelectOption from "primevue/selectoption";
  
  const ipAddresses = ref([]);
  const showDialog = ref(false); // Controls modal visibility
  const newIP = ref({
    ip_address: '',
    ip_type: null,
    label: '',
    comment: ''
  });
  
  const ipTypes = ref([
    { label: 'IPv4', value: 'IPv4' },
    { label: 'IPv6', value: 'IPv6' }
  ]);
  
  </script>
  