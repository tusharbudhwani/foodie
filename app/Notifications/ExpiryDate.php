<?php

namespace App\Notifications;

use App\Models\Storeroom;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExpiryDate extends Notification
{
    use Queueable;

    public User $user;
    public $products;

    public function __construct(User $user, $products)
    {
        $this->user = $user;
        $this->products = $products;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)->markdown('mail.expiry.date', ['user' => $this->user, 'products' =>$this->products]);
    }

    public function toArray($notifiable)
    {
        return $this->products;
    }
}
