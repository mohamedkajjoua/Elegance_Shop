<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\OrderService;            // Nécessaire pour injecter le service
use App\Http\Requests\UpdateOrderStatusRequest; // Nécessaire pour la validation du statut
use Illuminate\Http\JsonResponse;               // Nécessaire pour le type de retour
use Illuminate\Http\Request;                    // Utile pour des requêtes standards

class OrderController extends Controller
{
    protected $orderService;

    // Constructeur : Injection du Service OrderService
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * GET /api/admin/orders
     * Liste toutes les commandes
     */
    public function index(): JsonResponse
    {
        $orders = $this->orderService->getAllOrders();
        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * GET /api/admin/orders/{id}
     * Détails d'une commande spécifique
     */
    public function show($id): JsonResponse
    {
        $order = $this->orderService->getOrderById($id);
        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    /**
     * PATCH /api/admin/orders/{id}/status
     * Met à jour le statut (ex: Shipped, Delivered)
     */
    public function updateStatus(UpdateOrderStatusRequest $request, $id): JsonResponse
    {
        $order = $this->orderService->updateOrderStatus($id, $request->status);
        
        return response()->json([
            'success' => true,
            'message' => 'Statut de la commande mis à jour avec succès',
            'data' => $order
        ]);
    }

    /**
     * PATCH /api/admin/orders/{id}/cancel
     * Annule une commande (si non expédiée)
     */
    public function cancel($id): JsonResponse
    {
        try {
            $order = $this->orderService->cancelOrder($id);
            return response()->json([
                'success' => true,
                'message' => 'Commande annulée avec succès.',
                'data' => $order
            ]);
        } catch (\Exception $e) {
            // Renvoie une erreur 400 (Bad Request) si la règle métier échoue
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    /**
     * PATCH /api/admin/orders/{id}/refund
     * Rembourse une commande
     */
    public function refund($id): JsonResponse
    {
        try {
            $order = $this->orderService->refundOrder($id);
            return response()->json([
                'success' => true,
                'message' => 'Commande marquée comme remboursée.',
                'data' => $order
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    /**
     * GET /api/admin/orders/export/csv
     * Télécharge la liste des commandes en CSV
     */
    public function exportCsv()
    {
        $callback = $this->orderService->getCsvExportCallback();
        $filename = 'commandes_export_' . date('Y-m-d_H-i') . '.csv';

        // En-têtes HTTP pour forcer le téléchargement du fichier
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->stream($callback, 200, $headers);
    }
}



