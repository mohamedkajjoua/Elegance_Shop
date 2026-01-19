<?php

namespace App\Services\User;


use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

class ContactService
{
    public function sendContactEmail($data)
    {

        $adminEmail = Setting::first()->email_us ?? 'mohamed0koo20@gmail.com';


        Mail::to($adminEmail)->send(new ContactFormMail($data));

        return true;
    }
}
