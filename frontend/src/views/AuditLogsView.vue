<template>
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-2xl font-bold dark:text-white text-black">Audit Logs</h2>
    </div>
    <Card class="shadow-lg w-full border-t border-green-500">
      <template #title>Audit Logs Table</template>
      <template #content>
        <AuditTable/>
      </template>
    </Card>
</template>

<script setup>
import AuditTable from '@/components/auditlog/AuditTable.vue';
import Card from 'primevue/card';
</script>