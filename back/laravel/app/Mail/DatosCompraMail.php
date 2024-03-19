<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DatosCompraMail extends Mailable
{
    use Queueable, SerializesModels;

    public $datosCompra;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($datosCompra)
    {
        $this->datosCompra = $datosCompra;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.datosCompra')->with(['datosCompra' => $this->datosCompra]);
    }
}
