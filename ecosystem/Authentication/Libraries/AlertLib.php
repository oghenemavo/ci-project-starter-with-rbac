<?php

namespace Ecosystem\Authentication\Libraries;

use Ecosystem\Authentication\Models\PendingNotifications;
use Ecosystem\Authentication\Models\User;

class AlertLib
{

    /**
     * Store a Failed Notification data
     *
     * @param string $for
     * @return void
     */
    public function store_notification(array $data)
    {
        $alert = new PendingNotifications();
        return $alert->save($data);
    }

    public function dispatch_notifications()
    {
        $address = $set = $sender = [];
        $alert = new PendingNotifications();
        $pending = $alert->findAll();

        $user = new User();
        $mailer = service('mailerLib');

        if ($pending) {
            foreach ($pending as $value) {
                if ($value->notification_type == '0') { // email
                    $sender = json_decode($value->sender, true);
                    $address['from'] = $sender['email'];
                    $address['from_name'] = $sender['name'] ?? '';

                    $set['subject'] = $value->notification_subject;
                    $set['html'] = $value->notification_body;
                    $set['text'] = strip_tags($value->notification_body);

                    $address['to'] = $user->find($value->user_id)->user_email;

                    // send mail
                    $case = $mailer->send_stored_mail($address, $set);
                    if ($case) {
                        // delete ref
                        $alert->delete($value->id);
                    } 
                }
            }
        }
        return true;
    }

}
