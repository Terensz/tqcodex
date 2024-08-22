<?php

namespace Domain\Admin\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $token_type
 */
class AdminToken extends Model
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
}
