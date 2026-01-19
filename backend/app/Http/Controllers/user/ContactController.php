<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Services\User\ContactService;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    protected ContactService $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    public function send(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        try {
            $this->contactService->sendContactEmail($validatedData);
            return response()->json([
                'status' => 'success',
                'message' => 'Your message has been send successfully'

            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send email .Please try again later'

            ], 500);
        }
    }
}
