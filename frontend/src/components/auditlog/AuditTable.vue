<template>
  <div>
    <!-- Filter Controls -->
    <div class="flex gap-4 mb-4">
      <InputText v-model="filters.user_id" placeholder="User ID" />
      <InputText v-model="filters.ip_id" placeholder="IP ID" />

      <!-- Date Range Filter -->
      <Calendar 
        v-model="filters.date_range" 
        selectionMode="range"
        placeholder="Select Date Range"
        showIcon 
      />

      <Button label="Filter" @click="fetchAuditLogs" class="p-button-primary" />
      <Button 
        label="Clear Filter" 
        @click="clearFilters"
        class="p-button-secondary"
      />
    </div>

    <!-- Data Table -->
    <DataTable
      :value="auditLogs"
      removableSort
      paginator
      :rows="20"
      :rowsPerPageOptions="[10, 20, 50]"
      tableStyle="min-width: 60rem"
      class="p-datatable-sm shadow-md"
    >
      <Column field="user_id" header="User ID" sortable></Column>
      <Column field="username" header="User Name" sortable></Column>
      <Column field="action" header="Action" sortable></Column>
      <Column field="ip_id" header="IP ID" sortable></Column>
      <Column field="ip_address" header="IP Address" sortable></Column>

      <!-- Changes Column with View Button -->
      <Column field="changes" header="Changes">
        <template #body="slotProps">
          <Button 
            label="View" 
            icon="pi pi-eye" 
            class="p-button-info p-button-sm"
            @click="openChangesModal(slotProps.data)"
          />
        </template>
      </Column>

      <Column field="created_at" header="Created At" sortable>
        <template #body="slotProps">
          {{ formatDateTime(slotProps.data.created_at) }}
        </template>
      </Column>
    </DataTable>

    <!-- Modal for Viewing Changes -->
    <AuditChangesModal 
      :visible="showChangesModal"
      @update:visible="showChangesModal = $event"
      :changes="selectedChanges"
      :logId="selectedLogId"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '@/services/auth';
import AuditChangesModal from '@/components/auditlog/AuditModal.vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputText from 'primevue/inputtext';
import Calendar from 'primevue/calendar';
import Button from 'primevue/button';

const auditLogs = ref([]);
const filters = ref({
  user_id: '',
  ip_id: '',
  date_range: null
});

// Modal State
const showChangesModal = ref(false);
const selectedChanges = ref({});
const selectedLogId = ref(null);

// Fetch Data
const fetchAuditLogs = async () => {
  const [date_from, date_to] = filters.value.date_range || [null, null];

  try {
    const response = await api.get('/audit-logs', {
      params: {
        user_id: filters.value.user_id,
        ip_id: filters.value.ip_id,
        date_from: date_from ? formatDate(date_from) : null,
        date_to: date_to ? formatDate(date_to) : null
      }
    });

    auditLogs.value = response.data;
  } catch (error) {
    console.error('Error fetching audit logs:', error.response?.data || error);
  }
};

// Open Changes Modal
const openChangesModal = (log) => {
  selectedChanges.value = log.changes;
  selectedLogId.value = log.id;
  showChangesModal.value = true;
};

// Clear Filters
const clearFilters = () => {
  filters.value = {
    user_id: '',
    ip_address: '',
    date_range: null
  };
  fetchAuditLogs();
};

// Format Date to 'YYYY-MM-DD' for API compatibility
const formatDate = (date) => {
  const d = new Date(date);
  return d.toLocaleDateString('en-CA'); 
};

const formatDateTime = (date) => {
  const d = new Date(date);
  return d.toLocaleString('en-US', {
    month: '2-digit',
    day: '2-digit',
    year: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
    hour12: true,
    timeZone: 'Asia/Manila'
  });
};

onMounted(fetchAuditLogs);
</script>
