<?php
namespace App\Service;

use App\Entity\PortalClient;

class HelperService
{
    public function clientCheckAndSaveSignUpDate($database, $userId, array $notificationParameters , string $redirectUri, bool $sendNotif = true): void
    {
        $realtorLinkedPortalClients = $database->fetchPortalClients($userId);
        /** @var PortalClient $database */
        $allMobileAppClients = $database->fetchMobileAppClients();
        foreach ($realtorLinkedPortalClients as $portalClient)
        {
            $portalClientOneEmail = $portalClient->email_1;
            $portalClientTwoEmail = $portalClient->email_2;
            /*$counter = 0;*/
            foreach ($allMobileAppClients as $mobileAppClient)
            {
                $mobileAppClientEmail = $mobileAppClient->email;
                if(isset($mobileAppClientEmail) && trim($mobileAppClientEmail) != "")
                {
                    if(($portalClientOneEmail == $mobileAppClientEmail) || ($portalClientTwoEmail == $mobileAppClientEmail))
                    {
                        if(isset($mobileAppClient->created_date))
                        {
                            $database->update($portalClient->doc_id, [
                                ["path" => "mobile_app_signed_up_at", "value" => $mobileAppClient->created_date],
                                ["path" => "notification_token", "value" => $mobileAppClient->notification_token],
                                ]);
                            /*$counter++;*/
                            break;
                        }
                    }
                }
            }
            /*if($counter === 0)
            {
                $database->update($userClient->doc_id, [
                    ["path" => "mobile_app_signed_up_at", "value" => ""],
                    ["path" => "notification_token", "value" => ""],
                ]);
            }*/
        }
        if($sendNotif)
        {
            $realtorLinkedMobileClientsTokens = [];
            foreach ($realtorLinkedPortalClients as $portalClient)
            {
                if(isset($portalClient->notification_token) && trim($portalClient->notification_token) !== "")
                {
                    $realtorLinkedMobileClientsTokens[] = $portalClient->notification_token;
                }
            }
            $notifiedClientsCount = count($realtorLinkedMobileClientsTokens);
            if($notifiedClientsCount > 0) {
                $this->sendFCM($realtorLinkedMobileClientsTokens, $notificationParameters, $redirectUri);
            } else {
                $_SESSION['story_success_flash_message'] = "Your story has just been created successfully ! (No clients notified)";
                header("Location: $redirectUri");
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
        ];
        // Create API Body
        $notifBody = [
            "notification" => $notifData,
            "time_to_live" => 3600,
            "registration_ids" => $tokensArray
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
        $notifiedClientsCount = count($tokensArray);
        if(json_decode(curl_exec($ch), true)["success"] > 0)
        {
            curl_close($ch);
            $_SESSION['story_success_flash_message'] = "Your story has just been created successfully ! ($notifiedClientsCount Clients notified)";
            header("Location: $redirectUrl");
            die();
        }
        header("Location: $redirectUrl");
    }

    public function templateDownload($filename): void
    {
        if (file_exists($filename)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filename));
            readfile($filename);
            exit;
        }
    }
}