<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\UserToken;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LinkLoginEmail extends Notification implements ShouldQueue
{
    use Queueable;

    protected $url;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->url = route('link.login', [$user->getLinkLoginToken(new UserToken)]);
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
        return (new MailMessage)
                    ->subject(__('form.login_to_app', ['app_name' => config('app.name')]))
                    ->line(__('form.login_press_button', ['app_name' => config('app.name')]))
                    ->action(__('form.sign_in'), $this->url)
                    ->line(
                        __('form.logout_instruction').' '.
                        __('form.thanks_for_using_app', ['app_name' => config('app.name')])
                    );
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
