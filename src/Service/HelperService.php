<?php
namespace App\Service;

class HelperService
{
    public function clientCheckAndSaveSignUpDate($userClients, $allMobileAppClients, $database): void
    {
        foreach ($userClients as $userClient)
        {
            $clientOneEmail = $userClient->email_1;
            $clientTwoEmail = $userClient->email_2;
            $counter = 0;
            foreach ($allMobileAppClients as $mobileAppClient)
            {
                $mobileAppClientEmail = $mobileAppClient->email;
                if(isset($mobileAppClientEmail) && trim($mobileAppClientEmail) != "")
                {
                    if(($clientOneEmail == $mobileAppClientEmail) || ($clientTwoEmail == $mobileAppClientEmail))
                    {
                        if(isset($mobileAppClient->created_date))
                        {
                            $database->update($userClient->doc_id, [
                                ["path" => "mobile_app_signed_up_at", "value" => $mobileAppClient->created_date],
                                ["path" => "notification_token", "value" => $mobileAppClient->notification_token],
                                ]);
                            $counter++;
                            break;
                        }
                    }
                }
            }
            if($counter === 0)
            {
                $database->update($userClient->doc_id, [
                    ["path" => "mobile_app_signed_up_at", "value" => ""],
                    ["path" => "notification_token", "value" => ""],
                ]);
            }
        }
    }

    public function sendFCM(array $tokensArray, array $parameters, string $redirectUrl)
    {
        $url = "https://fcm.googleapis.com/fcm/send";
        // SERVER KEY
        $apiKey = "AAAAc6AHlLw:APA91bEZAO-NSozL_IbxiRnk7ixCHWWMxAViU-mX8fvzmLWflGFQk6QFayyv8QCcqZFWOavmv25wKraQhF3W3fXRljMAV1_GB6OKjVsUnFFLqAoURV9F8SqJqnOCePhtGHuBvXgdXyyU";
        $headers = [
            "Authorization:key=" . $apiKey,
            "Content-Type:application/json"
        ];
        // Notification Content
        $notifData = [
            "title" => $parameters["title"],
            "body" => $parameters["body"],
            /*"image" => "IMAGE - URL",
            "click-action" => "activities.notifhandler"*/
        ];

        // Optional
        /*$dataPayload = [
            "to" => "VIP",
            "date" => "2023-02-12",
            "other_data" => "dummy data just for testing purposes"
        ];*/

        // Create API Body
        $notifBody = [
            "notification" => $notifData,
            // data payload is optional
            /*"data" => $dataPayload,*/
            // optional - in seconds, max_time = 4 weeks
            "time_to_live" => 3600,
            // "to" => "token or Reg_id",
            /*"to" => "dIDaijXaREqaJ-S1InPc7H:APA91bGm7rqvUDCeIC4dVqf7bL2opxDjg4RzgbQMidOrqc4HWEmthWPqVbFPxFbjZQDEHIEfiu8l3GVx0_BkUVGxbT5ucrKdH4WP8XEiHFej3yyf2_68RZQ5jMO7-E3qN-5LRCBsp2Ju",*/
            "registration_ids" => $tokensArray
            /*"registration_ids" => ["dIDaijXaREqaJ-S1InPc7H:APA91bGm7rqvUDCeIC4dVqf7bL2opxDjg4RzgbQMidOrqc4HWEmthWPqVbFPxFbjZQDEHIEfiu8l3GVx0_BkUVGxbT5ucrKdH4WP8XEiHFej3yyf2_68RZQ5jMO7-E3qN-5LRCBsp2Ju"],*/
            /*"registration_ids" => array of Registration_ids or tokens JSON*/
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notifBody));
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute
        if(json_decode(curl_exec($ch), true)["success"] > 0)
        {
            curl_close($ch);
            header("Location: $redirectUrl");
            die();
        }

        header("Location: $redirectUrl");
    }
}