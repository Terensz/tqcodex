<?php

namespace Domain\System\Builders;

use Domain\Shared\Builders\Base\BaseBuilder;
use Domain\System\Models\VisitLog;
use Illuminate\Support\Facades\DB;

class VisitLogBuilder extends BaseBuilder
{
    // Deprecated method
    // public static function getListQuery()
    // {
    //     $query = VisitLog::query()
    //         ->leftJoin('users', 'users.id', '=', 'visitlogs.user_id')
    //         ->leftJoin('contacts', 'contacts.id', '=', 'visitlogs.contact_id')
    //         ->leftJoin('contactprofiles', 'contactprofiles.contact_id', '=', 'contacts.id')
    //         ->select(
    //             'visitlogs.id as id',
    //             DB::raw("CONCAT(users.lastname, ' ', users.firstname) as user_name"),
    //             DB::raw("CONCAT(contactprofiles.lastname, ' ', contactprofiles.firstname) as contact_name"),
    //             'visitlogs.url as url',
    //             'visitlogs.ip_address as ip_address',
    //             'visitlogs.host as host',
    //             'visitlogs.user_agent as user_agent',
    //             'visitlogs.count_of_visits as count_of_visits',
    //         );

    //     return $query;
    // }
}
