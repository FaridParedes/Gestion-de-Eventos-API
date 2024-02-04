<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventoProximoNotification extends Notification
{
    use Queueable;

    protected $evento;
    /**
     * Create a new notification instance.
     */
    public function __construct($evento)
    {
        $this->evento = $evento;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = url('http://localhost:8080/event/' . $this->evento->id);

        return (new MailMessage)
                    ->line('El evento ' . $this->evento->title . ' está próximo.')
                    ->action('Ver Evento', $url)
                    ->line('Gracias por asistir!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $url = url('http://localhost:8080/event/' . $this->evento->id);

        return [
            'eventoId' => $this->evento->id,
            'message' => 'El evento ' . $this->evento->title . ' está próximo.',
            'link' => $url,
        ];
    }
}
