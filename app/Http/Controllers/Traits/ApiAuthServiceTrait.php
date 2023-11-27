<?php

namespace App\Http\Controllers\Traits;

use App\Models\SecurityKey;

trait ApiAuthServiceTrait
{
    public function apiAuthValidation($securityKey)
    {
        $data = app(SecurityKey::class)->where('security_key', $securityKey)->first();

        if ($data) {
            $now = strtotime(now());
            $startDay = strtotime($data->start_day);
            $endDay = strtotime($data->end_day);

            if ($now >= $startDay && $now <= $endDay) {
                return true;
            }

            return false;
        }

        return false;
    }
}
