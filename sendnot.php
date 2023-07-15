<?php

$token = "c0a_ErWPTu2j8ZDR_B4EEm:APA91bFezAmcEoa0dj56Q8_iu-udwSJVEIgD51ZOr_B3O4ZeRXwfoLdBEGY1NHbKMBepP5e-LggCtoZgXRFbOvVbd5VYwDzG8Fkrg7ssBL8Ps8PB1UZB7649oqgio1R9QddULKO94OeI";
$notification = [
            'title' =>'title',
            'body' => 'body of message.',
            'sound' => 'mySound'
        ];
        $extraNotificationData = ["message" => $notification,"moredata" =>'dd'];
         $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
    define('API_ACCESS_KEY','AAAAbfqYWl8:APA91bGX3_J1KXH3LX9qeN21FDCFV01Ojmuw5M1pJP5A6kdcFa58tn3cGKKUhkhfqQcIYGjzfcn2gvu_1iopT_ajPGDG0ybqz-Ju79OG1q34tqY9eXIQeq9c4mDByT1eKPx4hQ64YHvW');
        $fcmNotification = [
            //'registration_ids' => $tokenList, //multple token array
            'to'        => $token, //single token
            'notification' => $notification,
            'data' => $extraNotificationData
        ];

        $headers = [
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);


        echo $result;