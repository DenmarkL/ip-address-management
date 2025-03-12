<template>
  <div>
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-2xl font-bold dark:text-white text-black">IP Address Manager</h2>

      <Button 
        label="Add IP" 
        icon="pi pi-plus" 
        class="p-button-success px-4 py-2 text-white"
        @click="handleAddNewIP"
      />
    </div>

    <!-- IP Table -->
    <Card class="shadow-lg w-full border-t border-green-500">
      <template #title>IP Table</template>
      <template #content>
        <IPTable 
          :ipAddresses="ipAddresses" 
          @edit="handleEdit" 
          @delete="handleDelete"
        />
      </template>
    </Card>

    <!-- Add/Edit IP Modal -->
    <AddIPModal 
      :visible="showDialog"
      :mode="modalMode"
      :ipData="selectedIP"
      :ipTypes="ipTypes"
      @update:visible="showDialog = $event"
      @ipAdded="handleIPAdded"
      @ipUpdated="handleIPUpdated"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import IPTable from '@/components/ipmanagement/IPTable.vue';
import AddIPModal from '@/components/ipmanagement/IPAddModal.vue';
import Card from 'primevue/card';
import Button from 'primevue/button';
import api from "@/services/auth";
import { useToast } from 'primevue/usetoast';
import { useConfirm } from 'primevue/useconfirm';
import ConfirmDialog from 'primevue/confirmdialog';

const toast = useToast();

const ipAddresses = ref([]);
const showDialog = ref(false);
const modalMode = ref('add'); // 'add' or 'edit'
const selectedIP = ref({});
const confirm = useConfirm();

const ipTypes = ref([
  { label: 'IPv4', value: 'IPv4' },
  { label: 'IPv6', value: 'IPv6' }
]);

// Fetch IP addresses
const fetchIPAddresses = async () => {
  try {
    const response = await api.get('/ip-addresses');
    ipAddresses.value = response.data.data;
  } catch (error) {
    console.error("Error fetching IP addresses:", error);
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: 'Failed to load IP addresses.',
      life: 4000
    });
  }
};

// Handle Add IP Logic
const handleAddNewIP = () => {
  selectedIP.value = { ip_address: '', ip_type: null, label: '', comment: '' };
  modalMode.value = 'add';
  showDialog.value = true;
};

// Handle Edit IP Logic
const handleEdit = (ip) => {
  selectedIP.value = { ...ip };
  modalMode.value = 'edit';
  showDialog.value = true;
};

// Handle New IP Added
const handleIPAdded = (newIP) => {
  ipAddresses.value.push(newIP);
};

// Handle Updated IP Data
const handleIPUpdated = (updatedIP) => {
  const index = ipAddresses.value.findIndex(ip => ip.id === updatedIP.id);
  if (index !== -1) {
    ipAddresses.value[index] = updatedIP;
  }
};

// Handle Deleting an IP
const handleDelete = async (data) => {
  confirm.require({
    message: `Are you sure you want to delete IP address ${data.ip.ip_address}?`,
    header: 'Confirm Deletion',
    icon: 'pi pi-exclamation-triangle',
    acceptLabel: 'Yes',
    rejectLabel: 'No',
    accept: async () => {
      const transformedData = { ...data.ip, id: data.id };
      try {
        await api.delete(`/ip-addresses/${data.ip.id}`, {
          data: transformedData
        });

        ipAddresses.value = ipAddresses.value.filter(ip => ip.id !== data.ip.id);

        toast.add({
          severity: 'success',
          summary: 'Deleted',
          detail: 'IP Address deleted successfully',
          life: 3000
        });
      } catch (error) {
        console.error('Error deleting IP address:', error);
        toast.add({
          severity: 'error',
          summary: 'Error',
          detail: 'Failed to delete IP address.',
          life: 4000
        });
      }
    },
    reject: () => {
      toast.add({
        severity: 'info',
        summary: 'Cancelled',
        detail: 'IP Address deletion cancelled',
        life: 3000
      });
    }
  });
};

onMounted(fetchIPAddresses);
</script>
