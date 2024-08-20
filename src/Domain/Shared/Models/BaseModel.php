<?php

namespace Domain\Shared\Models;

use Domain\Shared\Helpers\StringHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

abstract class BaseModel extends Model
{
    use HasFactory;

    public $technicalProperties = [];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = [];

    protected static function newFactory()
    {
        $parts = Str::of(static::class)->explode('\\');
        $domain = strval($parts[1]);
        $model = strval($parts->last());

        return app("Database\\Factories\\{$domain}\\{$model}Factory");
    }

    public static function generateRandomPropertyValueUntilNotTaken(string $modelClass, string $propertyName, int $stringLength = 20): string
    {
        $randomPropertyValue = StringHelper::generateRandomString($stringLength);

        $existingPropertyValue = $modelClass::where([$propertyName => $randomPropertyValue])->first();

        return $existingPropertyValue ? self::generateRandomPropertyValueUntilNotTaken($modelClass, $propertyName, $stringLength) : $randomPropertyValue;
    }

    public function save(array $options = [])
    {
        $technicalProperties = [];

        // Removing the technical properties until saving.
        foreach ($this->technicalProperties as $technicalProperty) {
            if (array_key_exists($technicalProperty, $this->attributes)) {
                $technicalProperties[$technicalProperty] = $this->attributes[$technicalProperty];
                unset($this->attributes[$technicalProperty]);
            }
        }

        // Saving.
        $result = parent::save($options);

        // Restoring the technical properties.
        foreach ($technicalProperties as $technicalProperty => $value) {
            $this->attributes[$technicalProperty] = $value;
        }

        return $result;
    }
}
