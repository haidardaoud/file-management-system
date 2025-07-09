<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class FileStatusChanged extends Notification
{
    use Queueable;
    public $fileName;
    public $action;
    public $userName;
    private $data;
    /**
     * Create a new notification instance.
     */
    public function __construct(array  $data)
    {
        // $this->fileName = $fileName;
        // $this->action = $action;
        // $this->userName = $userName;
        $this->data = $data;
    }


    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */

     public function toMail($notifiable)
     {
         return (new MailMessage)
             ->line("The file '{$this->fileName}' was {$this->action} by {$this->userName}.")
             ->action('View Files', url('/files'))
             ->line('Thank you for using our application!');
     }

     public function toDatabase($notifiable)
     {
        // سجل البيانات للتحقق
        return [
            'data' => $this->data
        ];

     }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */

     public function toArray($notifiable)
     {
        //  $data = [
        //      'fileName' => $this->fileName,
        //      'action' => $this->action,
        //      'userName' => $this->userName,
        //  ];
        //  Log::info('Notification Data: ', $data);
          return $this->data;
        //return json_decode($this->data, true);
     }
}
