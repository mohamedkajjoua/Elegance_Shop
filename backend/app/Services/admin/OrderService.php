<?php

namespace App\Services\Admin;

use App\Models\Order;
use Exception;

class OrderService
{
    //  Gardez les méthodes getAllOrders et getOrderById ici

    /**
     * ANNULER UNE COMMANDE
     * Change le statut à 'cancelled' si la commande n'est pas déjà expédiée.
     */
    public function cancelOrder($id)
    {
        $order = Order::findOrFail($id);

        // Règle métier : On ne peut pas annuler une commande déjà partie ou livrée
        if (in_array($order->status, ['Shipped', 'Delivered'])) {
            throw new Exception("Impossible d'annuler une commande déjà expédiée ou livrée.");
        }

        if ($order->status === 'cancelled') {
            throw new Exception("Cette commande est déjà annulée.");
        }

        $order->update(['status' => 'cancelled']);
        
        // (Optionnel) : Ici, vous pourriez ajouter une logique pour remettre les produits en stock
        
        return $order;
    }

    /**
     *  REMBOURSER UNE COMMANDE
     * Change le statut à 'Refunded'.
     */
    public function refundOrder($id)
    {
        $order = Order::findOrFail($id);

        // Règle métier : On ne rembourse pas ce qui l'est déjà
        if ($order->status === 'Refunded') {
            throw new Exception("Cette commande est déjà remboursée.");
        }

        $order->update(['status' => 'Refunded']);
        
        return $order;
    }

    /**
     *  LOGIQUE D'EXPORT CSV
     * Prépare les données pour le téléchargement
     */
    public function getCsvExportCallback()
    {
        // Récupérer les commandes avec les infos utilisateur pour le rapport
        $orders = Order::with('user')->orderBy('created_at', 'desc')->get();

        // Retourne une fonction anonyme (Callback) pour générer le fichier à la volée
        return function () use ($orders) {
            $file = fopen('php://output', 'w'); // Ouvre le flux de sortie direct

            //  Ajouter les en-têtes du fichier CSV
            fputcsv($file, ['ID', 'Client', 'Email', 'Total', 'Statut', 'Date Commande']);

            //  Parcourir les commandes et écrire les lignes
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->id,
                    $order->user ? $order->user->first_name . ' ' . $order->user->last_name : 'Invité',
                    $order->user ? $order->user->email : 'N/A',
                    $order->total_price,
                    $order->status,
                    $order->created_at->format('Y-m-d H:i')
                ]);
            }

            fclose($file);
        };
    }
}

