<template>
  <div class="flex bg-gray-100 min-h-screen">
    <AdminSidebar />

    <div class="flex-1 flex flex-col ml-64">
      
      <AdminNavbar />

      <main class="flex-1 p-6 mt-16 overflow-y-auto">
        <router-view></router-view>
      </main>
      
    </div>
  </div>
</template>

<script setup>
import AdminSidebar from '../components/admin/AdminSidebar.vue';
import AdminNavbar from '../components/admin/AdminNavbar.vue';
</script>