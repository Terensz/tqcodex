<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\File;

/*
Standalone run: 
php artisan test tests/Feature/TestSupervisorTest.php
*/

class TestSupervisorTest extends TestCase
{
    public $tests = [
        'tests/Feature/Project/Public/HomepageTest.php',
        'tests/Feature/Project/Public/AlmaPageTest.php',
        // Add other test files here...
    ];

    public $skipFilePath = [
        'tests',
        'tests/Datasets',
        // Add other directories to skip here...
    ];

    /**
     * @test Check if all necessary tests exist and there are no extra test files.
     */
    public function testAllTestsExist()
    {
        // Looping through the tests/ directory, collecting all existing test files.
        $allTestFiles = collect(File::allFiles(base_path('tests')))->map(function ($file) {
            return str_replace(base_path() . '/', '', $file->getPathname());
        })->filter(function ($file) {
            // We only collect .php files that are not inside skip directories.
            return substr($file, -4) === '.php' && !$this->isInSkippedDirectory($file);
        })->toArray();

        // Checking all configured ($this->tests) tests are available.
        foreach ($this->tests as $test) {
            $errorMessage = in_array($test, $allTestFiles) ? null : "Missing test: {$test}";
            $this->assertEquals(null, $errorMessage);
        }

        // Checks that no unnecessary (not configured) tests are existing.
        foreach ($allTestFiles as $file) {
            $errorMessage = in_array($file, $this->tests) ? null : "Unexpected test file found: {$file}";
            $this->assertEquals(null, $errorMessage);
        }
    }

    /**
     * Check if a file is inside a skipped directory.
     *
     * @param string $pathToFile
     * @return bool
     */
    private function isInSkippedDirectory(string $pathToFile): bool
    {
        $pathToFileParts = explode('/', $pathToFile);
        array_pop($pathToFileParts);
        $directoryPath = implode('/', $pathToFileParts);
        foreach ($this->skipFilePath as $skipFilePath) {
            if ($directoryPath == $skipFilePath) {
                return true;
            }
        }

        return false;
    }
}
