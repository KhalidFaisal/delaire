<?php

namespace App\Mail;

use App\Models\UserOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public UserOrder $order)
    {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = 'Order Status Updated - #' . $this->order->order_number;

        // Customise subject based on status
        switch (ucfirst($this->order->status)) {
            case 'Processing':
                $subject = 'Order Accepted & Processing - #' . $this->order->order_number;
                break;
            case 'Shipped':
                $subject = 'Order Shipped - #' . $this->order->order_number;
                break;
            case 'Cancelled':
                $subject = 'Order Cancelled - #' . $this->order->order_number;
                break;
            case 'Delivered':
                $subject = 'Order Delivered - #' . $this->order->order_number;
                break;
        }

        return new Envelope(
            subject: $subject,
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            replyTo: [new Address(config('mail.from.address'), config('mail.from.name'))],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order_status_updated',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('backend.pages.order.invoice', ['order' => $this->order]);
        return [
            Attachment::fromData(fn () => $pdf->output(), 'invoice-' . $this->order->order_number . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
