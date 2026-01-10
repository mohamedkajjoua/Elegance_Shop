<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

// Variable pour stocker la liste des produits
const products = ref([]);
const loading = ref(true);

// Fonction pour récupérer les produits depuis ton API Laravel
const fetchProducts = async () => {
  try {
    // Assure-toi que l'URL correspond à ta route API Laravel (ex: api/products)
    const response = await axios.get('http://localhost:8000/api/products');
    products.value = response.data;
    loading.value = false;
  } catch (error) {
    console.error("Erreur lors du chargement des produits :", error);
    loading.value = false;
  }
};

// Fonction pour supprimer un produit
const deleteProduct = async (id) => {
  if (confirm("Es-tu sûre de vouloir supprimer ce produit ?")) {
    try {
      await axios.delete(`http://localhost:8000/api/products/${id}`);
      // On rafraîchit la liste après la suppression
      fetchProducts();
      alert("Produit supprimé avec succès !");
    } catch (error) {
      console.error("Erreur lors de la suppression :", error);
      alert("Erreur lors de la suppression.");
    }
  }
};

// On charge les produits dès que la page s'ouvre
onMounted(() => {
  fetchProducts();
});
</script>

<template>
  <div class="admin-container">
    <div class="header-actions">
      <h2> Gestion des Produits</h2>
      <button class="btn-add">Ajouter un produit</button>
    </div>

    <div v-if="loading" class="loading">Chargement des données...</div>

    <table v-else class="product-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Image</th>
          <th>Nom</th>
          <th>Prix</th>
          <th>Stock</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="product in products" :key="product.id">
          <td>#{{ product.id }}</td>
          <td>
            <img 
              :src="product.image || 'https://via.placeholder.com/50'" 
              alt="Produit" 
              class="product-img"
            />
          </td>
          <td>{{ product.name }}</td>
          <td>{{ product.price }} MAD</td>
          <td>{{ product.stock }}</td>
          <td class="actions">
            <button class="btn-edit">Modifier</button>
            <button class="btn-delete" @click="deleteProduct(product.id)">Supprimer</button>
          </td>
        </tr>
      </tbody>
    </table>
    
    <p v-if="!loading && products.length === 0">Aucun produit trouvé.</p>
  </div>
</template>

<style scoped>
/* Un peu de style pour rendre le tableau joli */
.admin-container {
  padding: 20px;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.header-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.product-table {
  width: 100%;
  border-collapse: collapse;
}

.product-table th, .product-table td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

.product-table th {
  background-color: #f8f9fa;
  font-weight: bold;
}

.product-img {
  width: 50px;
  height: 50px;
  object-fit: cover;
  border-radius: 4px;
}

.actions button {
  padding: 6px 12px;
  margin-right: 5px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  color: white;
}

.btn-add {
  background-color: #2ecc71;
  color: white;
  padding: 10px 15px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.btn-edit {
  background-color: #3498db;
}

.btn-delete {
  background-color: #e74c3c;
}

.loading {
  text-align: center;
  padding: 20px;
  color: #666;
}
</style>