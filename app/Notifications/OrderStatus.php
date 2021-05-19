<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatus extends Notification
{
    use Queueable;

    /**
     * Esse e-mail possui 4 status diferentes. São eles:
     *
     * 1: Email para confirmação de compra
     * 2: Email para confirmação de pagamento
     * 3: Email para compra cancelada
     * 4: Email para informar que o pedido saiu para entrega
     */

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($order, $user, $status)
    {
        $this->order = $order;
        $this->user = $user;
        $this->status = $status;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        switch($this->status){
            case 1:

                return (new MailMessage)
                            ->subject('Pedido efetuado com sucesso.')
                            ->greeting('Olá, ' . $this->user->name . '!')
                            ->line('Seu pedido #' .$this->order->id. ' foi gerado com sucesso.')
                            ->line('Fique de olho na sua conta Sk8Shop e no seu email para saber os status do pedido.')
                            ->line('Assim que o pagamento for confirmado você receberá um email.')
                            ->line('Obrigado por comprar conosco!')
                            ->salutation('Att, Sk8Shop.');
                break;

            case 2:

                return (new MailMessage)
                            ->subject('Pagamento confirmado.')
                            ->greeting('Olá, ' . $this->user->name . '!')
                            ->line('O pagamento do seu pedido #' .$this->order->id. ' foi confirmado.')
                            ->line('Estamos separando e embalando ele.')
                            ->line('Assim que for para os correios o seu código de rastreio será disponibilizado.')
                            ->salutation('Att, Sk8Shop.');
                break;

            case 3:

                return (new MailMessage)
                            ->subject('Pedido cancelado.')
                            ->greeting('Olá, ' . $this->user->name . '!')
                            ->line('Não foi possivel confirmar o pagamento do pedido #' .$this->order->id. '.')
                            ->salutation('Att, Sk8Shop.');
                break;

            case 4:

                return (new MailMessage)
                            ->subject('Pedido entregue aos correios.')
                            ->greeting('Olá, ' . $this->user->name . '!')
                            ->line('O seu pedido #' .$this->order->id. ' foi entregue aos correios.')
                            ->line('O código de rastreio é.' . $this->order->tracking_code)
                            ->line('Obrigado por comprar conosco!')
                            ->salutation('Att, Sk8Shop.');
                break;

        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
