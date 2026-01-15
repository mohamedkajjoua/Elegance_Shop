<?php

namespace App\Services\User;

use App\Models\Addresse;
use Illuminate\Database\Eloquent\Collection;

class AddressService
{
    /**
     * Create a new address
     */
    public function createAddress(int $userId, array $data): Addresse
    {
        return Addresse::create([
            'user_id' => $userId,
            'label' => $data['label'],
            'country' => $data['country'],
            'city' => $data['city'],
            'street' => $data['street'],
            'post_code' => $data['post_code'],
            'phone' => $data['phone'],
        ]);
    }

    /**
     * Get all addresses for a user
     */
    public function getUserAddresses(int $userId): Collection
    {
        return Addresse::where('user_id', $userId)->get();
    }

    /**
     * Get a specific address
     */
    public function getAddress(int $addressId): ?Addresse
    {
        return Addresse::find($addressId);
    }

    /**
     * Update an address
     */
    public function updateAddress(int $addressId, array $data): Addresse
    {
        $address = Addresse::findOrFail($addressId);
        $address->update($data);
        return $address;
    }

    /**
     * Delete an address
     */
    public function deleteAddress(int $addressId): bool
    {
        return Addresse::destroy($addressId) > 0;
    }
}
