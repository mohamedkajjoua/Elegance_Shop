<template>
  <aside class="w-64 bg-gray-800 text-white h-screen flex flex-col fixed left-0 top-0">
    <div class="h-16 flex items-center justify-center border-b border-gray-700">
      <h1 class="text-2xl font-bold text-blue-400">Elegance Admin</h1>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-2">
      <router-link
        to="/admin/dashboard"
        class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 rounded transition-colors"
        active-class="bg-gray-700 text-white"
      >
        <span class="mr-3">🏠</span> Dashboard
      </router-link>

      <router-link
        to="/admin/products"
        class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 rounded transition-colors"
        active-class="bg-gray-700 text-white"
      >
        <span class="mr-3">📦</span>
        Products
      </router-link>

      <router-link
        to="/admin/orders"
        class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 rounded transition-colors"
        active-class="bg-gray-700 text-white"
      >
        <span class="mr-3">🛒</span>
        Commandes
      </router-link>
    </nav>

    <div class="p-4 border-t border-gray-700">
      <button class="w-full flex items-center px-4 py-2 text-red-400 hover:bg-gray-700 rounded">
        <span class="mr-3">🚪</span>
        Déconnexion
      </button>
    </div>
  </aside>
</template>

<script setup>
// Pas de logique JS complexe pour l'instant
</script>
