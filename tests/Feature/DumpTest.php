<?php

/*
Standalone run:
php artisan test tests/Feature/DumpTest.php
*/

/** @phpstan-ignore-next-line  */
arch('globals no dump in the code')
    ->expect(['dd', 'dump'])
    ->not->toBeUsed();
