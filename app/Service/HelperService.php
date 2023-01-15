<?php
namespace App\Service;

class HelperService
{
    public function setProfilePic($realtor): string
    {
        if(isset($realtor["realtor_photo"]) && $realtor["realtor_photo"] != "")
        {
            return $realtor["realtor_photo"];
        } else {
            return "../Ressources/assets/img/illustrations/profiles/profile-4.png";
        }
    }
}