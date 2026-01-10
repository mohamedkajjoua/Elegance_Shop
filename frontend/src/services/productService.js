import axios from 'axios';

// Assure-toi que cette URL correspond à ton backend (http://127.0.0.1:8000/api...)
const API_URL = 'http://localhost:8000/api/admin/products';

export default {
    // Récupérer la liste
    getAll() {
        return axios.get(API_URL);
    },

    // Récupérer un seul produit (pour l'édition détaillée si besoin)
    get(id) {
        return axios.get(`${API_URL}/${id}`);
    },

    // Créer (Nécessite FormData pour l'image)
    create(data) {
        let formData = new FormData();
        for (let key in data) {
            // On n'ajoute pas l'image si elle est null
            if (data[key] !== null) {
                formData.append(key, data[key]);
            }
        }
        return axios.post(API_URL, formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });
    },

    // Modifier
    // ASTUCE LARAVEL : Laravel ne gère pas bien les fichiers en PUT direct.
    // On utilise POST avec un champ "_method" = "PUT".
    update(id, data) {
        let formData = new FormData();
        for (let key in data) {
            if (data[key] !== null) {
                formData.append(key, data[key]);
            }
        }
        formData.append('_method', 'PUT'); 

        return axios.post(`${API_URL}/${id}`, formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });
    },

    // Supprimer
    delete(id) {
        return axios.delete(`${API_URL}/${id}`);
    }
};