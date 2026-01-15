<?php



namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\User\AddressService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AddressController extends Controller
{
    protected AddressService $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    /**
     * Store a newly created address
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'label' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'street' => 'required|string|max:255',
            'post_code' => 'required|string|max:20',
            'phone' => 'required|string|max:30',
        ]);

        $address = $this->addressService->createAddress(
            auth()->id(),
            $validated
        );

        return response()->json([
            'message' => 'Address created successfully',
            'data' => $address,
        ], 201);
    }

    /**
     * Get all addresses for authenticated user
     */
    public function index(): JsonResponse
    {
        $addresses = $this->addressService->getUserAddresses(auth()->id());

        return response()->json([
            'data' => $addresses,
        ]);
    }

    /**
     * Get a specific address
     */
    public function show(int $id): JsonResponse
    {
        $address = $this->addressService->getAddress($id);

        if (!$address) {
            return response()->json([
                'message' => 'Address not found',
            ], 404);
        }

        return response()->json([
            'data' => $address,
        ]);
    }

    /**
     * Update an address
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'label' => 'sometimes|string|max:100',
            'country' => 'sometimes|string|max:100',
            'city' => 'sometimes|string|max:100',
            'street' => 'sometimes|string|max:255',
            'post_code' => 'sometimes|string|max:20',
            'phone' => 'sometimes|string|max:30',
        ]);

        $address = $this->addressService->updateAddress($id, $validated);

        return response()->json([
            'message' => 'Address updated successfully',
            'data' => $address,
        ]);
    }

    /**
     * Delete an address
     */
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->addressService->deleteAddress($id);

        if (!$deleted) {
            return response()->json([
                'message' => 'Address not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Address deleted successfully',
        ]);
    }
}
