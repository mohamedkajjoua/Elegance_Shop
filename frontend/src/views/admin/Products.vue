<template>
  <div>
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Gestion des Produits</h1>
      <button 
        @click="openModal(false)" 
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow flex items-center">
        <span class="mr-2">+</span> Nouveau Produit
      </button>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
      <table class="min-w-full leading-normal">
        <thead>
          <tr>
            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Image</th>
            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nom</th>
            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Prix</th>
            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Stock</th>
            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="products.length === 0">
            <td colspan="5" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">Aucun produit trouvé.</td>
          </tr>
          <tr v-for="product in products" :key="product.id">
            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
              <div class="flex-shrink-0 w-10 h-10">
                <img class="w-full h-full rounded-full object-cover" 
                     :src="getImageUrl(product.image)" 
                     alt="Produit" />
              </div>
            </td>
            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
              <p class="text-gray-900 whitespace-no-wrap font-medium">{{ product.name }}</p>
            </td>
            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
              <p class="text-gray-900 whitespace-no-wrap">{{ product.price }} MAD</p>
            </td>
            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
              <span :class="product.stock > 0 ? 'bg-green-200 text-green-900' : 'bg-red-200 text-red-900'"
                    class="relative inline-block px-3 py-1 font-semibold leading-tight rounded-full text-xs">
                <span aria-hidden="true" class="absolute inset-0 opacity-50 rounded-full"></span>
                <span class="relative">{{ product.stock }}</span>
              </span>
            </td>
            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
              <button @click="openModal(true, product)" class="text-blue-600 hover:text-blue-900 mr-4">Modifier</button>
              <button @click="deleteProduct(product.id)" class="text-red-600 hover:text-red-900">Supprimer</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        
        <div class="flex justify-between items-center p-4 border-b">
          <h3 class="text-lg font-semibold">{{ isEditing ? 'Modifier le produit' : 'Nouveau produit' }}</h3>
          <button @click="closeModal" class="text-gray-500 hover:text-gray-700">&times;</button>
        </div>

        <form @submit.prevent="saveProduct" class="p-4 space-y-4">
          
          <div>
            <label class="block text-sm font-medium text-gray-700">Nom du produit</label>
            <input v-model="form.name" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2 focus:border-blue-500 focus:ring-blue-500" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <textarea v-model="form.description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2"></textarea>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Prix</label>
              <input v-model="form.price" type="number" step="0.01" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Stock</label>
              <input v-model="form.stock" type="number" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2" />
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Image</label>
            <input type="file" @change="handleFileUpload" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
            <p v-if="isEditing && !form.image" class="text-xs text-gray-500 mt-1">Laisser vide pour garder l'image actuelle.</p>
          </div>

          <div class="flex justify-end pt-4 space-x-2">
            <button type="button" @click="closeModal" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Annuler</button>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
              {{ isEditing ? 'Mettre à jour' : 'Enregistrer' }}
            </button>
          </div>
        </form>

      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted, reactive } from 'vue';
import productService from '../../services/productService'; // Adapte le chemin selon ta structure

// --- ETAT (STATE) ---
const products = ref([]);
const showModal = ref(false);
const isEditing = ref(false);
const currentId = ref(null);

// Formulaire réactif
const form = reactive({
  name: '',
  description: '',
  price: '',
  stock: '',
  image: null
});

// --- LOGIQUE CHARGEMENT ---
const loadProducts = async () => {
  try {
    const response = await productService.getAll();
    // Laravel paginate renvoie les données dans .data.data ou juste .data selon ta config controller
    // Si tu as fait return response()->json($products) et que c'est paginé, c'est souvent response.data.data
    // Si tu as fait return Product::all(), c'est response.data
    products.value = response.data.data ? response.data.data : response.data;
  } catch (error) {
    console.error("Erreur chargement:", error);
  }
};

// --- LOGIQUE MODAL ---
const openModal = (editMode = false, product = null) => {
  isEditing.value = editMode;
  showModal.value = true;

  if (editMode && product) {
    // Remplir le formulaire avec les données existantes
    currentId.value = product.id;
    form.name = product.name;
    form.description = product.description;
    form.price = product.price;
    form.stock = product.stock;
    form.image = null; // On reset l'image car on ne l'envoie que si on la change
  } else {
    // Reset formulaire pour ajout
    resetForm();
  }
};

const closeModal = () => {
  showModal.value = false;
  resetForm();
};

const resetForm = () => {
  currentId.value = null;
  form.name = '';
  form.description = '';
  form.price = '';
  form.stock = '';
  form.image = null;
};

// --- LOGIQUE FORMULAIRE ---
const handleFileUpload = (event) => {
  form.image = event.target.files[0];
};

const saveProduct = async () => {
  try {
    if (isEditing.value) {
      await productService.update(currentId.value, form);
      alert('Produit modifié !');
    } else {
      await productService.create(form);
      alert('Produit créé !');
    }
    closeModal();
    loadProducts(); // Rafraîchir la liste
  } catch (error) {
    console.error("Erreur sauvegarde:", error);
    alert("Une erreur est survenue.");
  }
};

// --- LOGIQUE SUPPRESSION ---
const deleteProduct = async (id) => {
  if (confirm("Êtes-vous sûr de vouloir supprimer ce produit ?")) {
    try {
      await productService.delete(id);
      loadProducts();
    } catch (error) {
      console.error("Erreur suppression:", error);
    }
  }
};

// --- HELPERS ---
const getImageUrl = (imagePath) => {
  if (!imagePath) return 'https://via.placeholder.com/150'; // Image par défaut
  // Assure-toi que ton dossier 'storage' est bien linké dans Laravel (php artisan storage:link)
  return `http://localhost:8000/storage/${imagePath}`;
};

// --- INITIALISATION ---
onMounted(() => {
  loadProducts();
});
</script>