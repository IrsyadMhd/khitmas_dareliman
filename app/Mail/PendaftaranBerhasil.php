<?php

namespace App\Mail;

use App\Models\Pendaftaran;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PendaftaranBerhasil extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The QR code file path for attachment.
     */
    public ?string $qrPath;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Pendaftaran $pendaftaran,
        ?string $qrPath = null,
    ) {
        $this->qrPath = $qrPath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bukti Pendaftaran Khitanan Massal - ' . $this->pendaftaran->nama_lengkap,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.pendaftaran-berhasil',
            with: [
                'pendaftaran' => $this->pendaftaran,
                'qrPath' => $this->qrPath,
            ],
        );
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $mail = $this->view('emails.pendaftaran-berhasil')
            ->subject('Bukti Pendaftaran Khitanan Massal - ' . $this->pendaftaran->nama_lengkap);

        // Attach QR code if available
        if ($this->qrPath && file_exists($this->qrPath)) {
            $mail->attach($this->qrPath, [
                'as' => 'qrcode-' . $this->pendaftaran->kode_registrasi . '.png',
                'mime' => 'image/png',
            ]);
        }

        return $mail;
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
