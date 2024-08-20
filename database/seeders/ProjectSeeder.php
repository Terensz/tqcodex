<?php

declare(strict_types=1);

namespace Database\Seeders;

use Domain\Finance\Models\CompensationItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

final class ProjectSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // CompensationItem::create([

        // ]);

        // \Domain\Finance\Models\CompensationItem::factory(10)->createUntilNotTaken();
    }

    // Később ezt fogjuk használni

    /**
     * Run the database seeds.
     */
    // public function run()
    // {
    //     // Recommended when importing larger CSVs
    //     DB::disableQueryLog();

    //     // Uncomment the below to wipe the table clean before populating
    //     Schema::disableForeignKeyConstraints();

    //     $this->getCompensation();

    //     Schema::enableForeignKeyConstraints();
    // }

    // protected function getCompensation(): void
    // {
    //     DB::table('compensationitems')->truncate();

    //     $csvFile = fopen(base_path('database/seeders/csv/compensationitems.csv'), 'r');
    //     /**
    //      * Fields:
    //      * 0 - id
    //      * 1 - ...
    //      */
    //     $firstline = true;
    //     while (($data = fgetcsv($csvFile, 512, ',')) !== false) {
    //         if (! $firstline) {
    //             //skip the first column, ID - it is automatic
    //             /*
    //             Compensation::create([
    //                 'field_int' => intval($data['1']),
    //                 'field_string' => strval($data['2']),
    //                 'field_string_nullable' => strlen($data['3']) > 1 ? strval($data['3']) : null,
    //                 'field_int_nullable' => strlen($data['4']) > 1 ? intval($data['4']) : null,
    //             ]);
    //             */
    //         }
    //         $firstline = false;
    //     }

    //     fclose($csvFile);
    // }
}
