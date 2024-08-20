<?php

namespace Domain\Project\PipesSaveBulkComp;

use Closure;
use Illuminate\Support\Facades\Log;

class SaveDataToDb
{
    public function handle($data, Closure $next)
    {
        // Log::info('SaveDataToDb->handle()'); //exit;
        $this->saveModels($data['models']);

        return $next($data);
    }

    public function saveModels($models)
    {
        foreach ($models as $key => $model) {
            $model->save();
        }
    }
}
