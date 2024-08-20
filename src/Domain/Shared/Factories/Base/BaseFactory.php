<?php

declare(strict_types=1);

namespace Domain\Shared\Factories\Base;

use Domain\Shared\Helpers\PHPHelper;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

abstract class BaseFactory extends Factory
{
    /**
     * Create a new model instance in the database if a duplicate entry error occurs.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createUntilNotTaken(array $attributes = [], $doNotTouchTheseProps = [], $debug = false)
    {
        try {
            $obj = $this->create($attributes);

            // if ($debug) {
            //     dump('Obj:');
            //     dump($obj);//exit;
            // }
            return $obj;
        } catch (\Illuminate\Database\QueryException $exception) {
            // if ($debug) {
            //     dump('Duplicate entry!!');
            // }
            if (PHPHelper::strContains($exception->getMessage(), 'Duplicate entry')) {
                if (! empty($attributes)) {
                    foreach ($attributes as $attribute => $value) {
                        if (is_array($value)) {
                            // Ez mégsem lesz jó így.
                            // $class = $attribute;
                            // $subFactory = new $class();
                            // if (is_object($subFactory)) {
                            //     $subFactory->createUntilNotTaken($value, $doNotTouchTheseProps);
                            // }
                        } else {
                            if (! PHPHelper::inArray($attribute, $doNotTouchTheseProps)) {
                                $existingModel = $$this->model::where($attributes)->first();
                                if ($existingModel) {
                                    $existingModel->$attribute = self::createRandomValue($value);
                                    $existingModel->save();
                                }
                            }
                        }
                    }
                }

                return $this->createUntilNotTaken($attributes);
            }
            // Rethrow other exceptions
            throw $exception;
        }
    }

    protected function createRandomValue($value)
    {
        // Implement logic here to generate a random value based on the provided value.
        // For example, if the value is a string, you could generate a random string.
        // Or, if the value is an integer, you could generate a random integer.
        // This implementation depends on your specific requirements.

        // Example implementation:
        if (is_string($value)) {
            return Str::random(10); // Generate a random string of length 10
        }

        if (is_int($value)) {
            return rand(1, 100); // Generate a random integer between 1 and 100
        }

        // Default behavior: return the provided value
        return $value;
    }

    // foreach ($attributes as $attribute => $value) {
    //     $$this->model::where([]);
    // }
    // $existingModel = $$this->model::where($attributes)->first();
}
