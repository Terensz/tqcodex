<?php

namespace Domain\Customer\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $email
 * @property string $token
 * @property string $token_type
 */
class ContactToken extends Model
{
    public $timestamps = false;

    public const TOKEN_TYPE_PASSWORD_RESET = 'PasswordReset';

    public const TOKEN_TYPE_EMAIL_CHANGE = 'EmailChange';

    protected $fillable = [
        'email',
        'token',
        'token_type',
    ];

    protected $attributes = [
        'token_type' => self::TOKEN_TYPE_PASSWORD_RESET,
    ];

    public function delete()
    {
        if (! empty($this->email) && ! empty($this->token_type)) {
            parent::where('email', $this->email)->where('token_type', $this->token_type)->delete();
        }
    }

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::saving(function ($model) {
    //         if (empty($model->token_type)) {
    //             $model->token_type = ContactToken::TOKEN_TYPE_PASSWORD_RESET;
    //         }
    //     });
    // }

    // public function __construct(array $attributes = [])
    // {
    //     parent::__construct($attributes);
    //     $this->attributes['token_type'] = self::TOKEN_TYPE_PASSWORD_RESET;
    // }
}
