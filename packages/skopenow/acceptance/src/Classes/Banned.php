<?php

namespace Skopenow\Acceptance\Classes;

use Cache;
use App\Models\BannedDomains;
use App\Models\UserBannedDomains;

class Banned implements BannedInterface
{
    /**
     * [getUserBanned description]
     *
     * @return array [description]
     */
    public function getUserBanned()
    {
        $data = [];
        if (config("state.user_id")) {
            $userId = config("state.user_id");
            $data = UserBannedDomains::where("user_id", $userId)->get();
        }
        return $data;
    }

    /**
     * [getBannedDomains description]
     *
     * @return array [description]
     */
    public function getBannedDomains()
    {
        $panned = Cache::get("BannedDomains");
        if (empty($panned)) {
            $panned = BannedDomains::all();

            Cache::put("BannedDomains", $panned, 60*60*24*7);
        }

        return $panned;
    }

}