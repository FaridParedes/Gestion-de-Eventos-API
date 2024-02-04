<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventoActualizadoNotification extends Notification
{
    use Queueable;

    protected $evento;

    public function __construct($evento)
    {
        $this->evento = $evento;
    }

    public function via($notifiable)
    {
        return ['mail']; // Indica que esta notificación se enviará por correo electrónico
    }

    public function toMail(object $notifiable): MailMessage
    {

        $url = url('http://localhost:8080/event/' . $this->evento->id);

        return (new MailMessage)
            ->subject('Evento Actualizado: ' . $this->evento->title)
            ->line('El evento ' . $this->evento->title . ' ha sido actualizado con nueva información.')
            ->line('Fecha: ' . $this->evento->start)
            ->line('Ubicación: ' . $this->evento->ubication)
            ->action('Ver el evento', $url)
            ->line('Gracias por usar nuestra aplicación.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
