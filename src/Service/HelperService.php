<?php
namespace App\Service;

use App\Entity\MobileAppClient;
use App\Entity\PortalClient;

class HelperService
{
    public function clientCheckAndSaveSignUpDate($database, $userId, array $notificationParameters , string $redirectUri, string $context, bool $sendNotif = true, MobileAppClient $mobileAppClientEntity = null, string $flashMessage = null): void
    {
        $realtorLinkedPortalClients = $database->fetchPortalClients($userId);
        /** @var PortalClient $database */
        $allMobileAppClients = $database->fetchMobileAppClients();
        foreach ($realtorLinkedPortalClients as $portalClient)
        {
            $portalClientOneEmail = $portalClient->email_1;
            $portalClientTwoEmail = $portalClient->email_2;
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
                                /*["path" => "notification_token", "value" => $mobileAppClient->notification_token],*/
                                ]);
                            /*$counter++;*/
                            break;
                        }
                    }
                }
            }
        }
        if($sendNotif)
        {
            $realtorLinkedMobileAppUsers = $mobileAppClientEntity->fetchRealtorMobileAppClients($userId);
            $realtorLinkedMobileClientsTokens = [];
            foreach ($realtorLinkedMobileAppUsers as $mobileAppUser)
            {
                if(isset($mobileAppUser->notification_token) && trim($mobileAppUser->notification_token) !== "")
                {
                    $realtorLinkedMobileClientsTokens[] = $mobileAppUser->notification_token;
                }
            }
            $notifiedClientsCount = count($realtorLinkedMobileClientsTokens);
            if($notifiedClientsCount > 0) {
                $this->sendFCM($realtorLinkedMobileClientsTokens, $notificationParameters, $redirectUri, $context, $flashMessage);
            } else {
                if($context === "story")
                {
                    $_SESSION["story_success_flash_message"] = "$flashMessage just been published successfully! (No clients notified)";
                } elseif ($context === "create_pro_service")
                {
                    $_SESSION['pro_service_success_flash_message'] = "Your pro service has just been created successfully! (No clients notified)";
                } elseif ($context === "import_pro_services")
                {
                    $_SESSION['pro_service_success_flash_message'] = "Your pro services have just been imported successfully! (No clients notified)";
                } elseif ($context === "delete_pro_service")
                {
                    $_SESSION['pro_service_success_flash_message'] = "Your pro service has just been deleted successfully! (No clients notified)";
                }
                header("Location: $redirectUri");
            }
        }
    }

    public function sendFCM(array $tokensArray, array $parameters, string $redirectUrl, string $context, $flashMessage = null): void
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
            if($context === "story")
            {
                $_SESSION["story_success_flash_message"] = "$flashMessage just been published successfully! ($notifiedClientsCount Clients notified)";
            } elseif ($context === "create_pro_service")
            {
                $_SESSION['pro_service_success_flash_message'] = "Your pro service has just been created successfully! ($notifiedClientsCount Clients notified)";
            } elseif ($context === "import_pro_services")
            {
                $_SESSION['pro_service_success_flash_message'] = "Your pro services have just been imported successfully! ($notifiedClientsCount Clients notified)";
            } elseif ($context === "delete_pro_service")
            {
                $_SESSION['pro_service_success_flash_message'] = "Your pro service has just been deleted successfully! ($notifiedClientsCount Clients notified)";
            }
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