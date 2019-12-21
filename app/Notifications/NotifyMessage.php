<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyMessage extends Notification 
{
    use Queueable;
		public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
      return ['database','broadcast'];
    }

    public function toArray($notifiable)
    {
       return [
            'message' => $this->data['message'],
            'link' => $this->data['link'],
            'class' => $this->data['class'],
            'created_at' => now()
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'data' => array(
                'category' => 'DocsNotification',
                'message' => $this->data['message'],
                 'link' => $this->data['link'],
                'class' => $this->data['class'],
                'created_at' => date('Y-m-d H:i:s')
            )
        ]);
    }
}
