<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\PDF;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $pdf;

    public function __construct($order, PDF $pdf)
    {
        $this->order = $order;
        $this->pdf = $pdf;
    }

    public function build()
    {
        return $this->subject("Commande - Elegance Shop")
            ->view('emails.order_confirmation') // Blade email
            ->attachData(
                $this->pdf->output(),
                "facture.pdf"
            );
    }
}
