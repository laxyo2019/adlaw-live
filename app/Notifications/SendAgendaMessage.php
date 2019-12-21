<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;

class SendAgendaMessage extends Notification implements ShouldQueue
{
    use Queueable;

    public $agenda;
    public $team;

    public function __construct($agenda, $team)
    {
      $this->agenda = $agenda;
      $this->team = $team;
    }

    public function via($notifiable)
    {
      return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        if($this->team == null){
            return [
                'title' => 'Add your response to the agenda',
                'module' => 'Agenda',
                'priority' => 'Low',
                'color' => 'alert-warning',
                'icon' => 'fa fa-list-alt',
                'message' => 'Add your response to "'. $this->agenda->title.'"',
                'link' => '/agendaAddReminder/'.$this->agenda->id,
                'created_at' => now()
            ];
        }
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'data' => array(
                'title' => 'Add your response to the agenda',
                'module' => 'Agenda',
                'priority' => 'Low',
                'color' => 'alert-warning',
                'icon' => 'fa fa-list-alt',
                'message' => 'Add your response to "'. $this->agenda->title.'"',
                'link' => '/agendaAddReminder/'.$this->agenda->id,
                'created_at' => now()
            )
        ]);
    }

}
