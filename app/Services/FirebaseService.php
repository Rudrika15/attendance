<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseService
{
    
    protected $messaging;
    
    public function __construct()
    {
        $serviceAccountPath = storage_path('attendance.json');
        $factory = (new Factory)->withServiceAccount($serviceAccountPath);
        $this->messaging = $factory->createMessaging();
    }

    public function sendNotification($tokens, $title, $body)
    {
        $message = CloudMessage::withTarget('token', $tokens)
            ->withNotification(Notification::create($title, $body));
        $this->messaging->send($message);

    }
}