<template>
    <DataTable 
        :value="ipAddresses" 
        removableSort
        tableStyle="min-width: 50rem"
        class="p-datatable-sm shadow-m !bg-white"
    >
        <Column field="id" header="ID" sortable headerClass="custom-header"></Column>
        <Column field="ip_address" header="IP Address" sortable headerClass="custom-header"></Column>
        <Column field="ip_type" header="IP Type" sortable headerClass="custom-header"></Column>
        <Column field="label" header="Label" sortable headerClass="custom-header"></Column>
        <Column field="comment" header="Comment" sortable>
            <template #body="slotProps">
                {{ slotProps.data.comment ?? 'N/A' }}
            </template>
        </Column>

        <!-- Action Column -->
        <Column header="Actions" headerClass="custom-header" bodyClass="text-center">
            <template #body="slotProps">
                <Button 
                    icon="pi pi-pencil" 
                    class="p-button-rounded p-button-warning p-button-sm mr-2"
                    @click="$emit('edit', slotProps.data)" 
                    v-if="authStore.isAdmin || authStore.userId == slotProps.data.user_id"
                />
                <Button 
                    icon="pi pi-trash" 
                    class="p-button-rounded p-button-danger p-button-sm"
                    @click="$emit('delete', { id: slotProps.data.ip_id, ip: slotProps.data })"
                    v-if="authStore.isAdmin"
                />
            </template>
        </Column>
    </DataTable>
</template>

<script setup>
import { defineProps } from "vue";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from 'primevue/button';
import { useAuthStore } from '@/stores/AuthStore';

const authStore = useAuthStore();

defineProps({
    ipAddresses: {
        type: Array,
        default: () => []
    }
});

defineEmits(['edit', 'delete']);
</script>

<style>
.p-datatable .p-sortable-column {
    background-color: #1e293b !important; /* Dark Blue-Gray */
    color: white !important; /* White Text */
}

.p-datatable .p-sortable-column:hover {
    background-color: #334155 !important; /* Slightly lighter shade on hover */
}
</style>
