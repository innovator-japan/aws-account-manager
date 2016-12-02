<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    const DEFAULT_POLICY = '
        {
            "Version": "2012-10-17",
            "Statement": [
                {
                    "Effect": "Allow",
                    "Action": "*",
                    "Resource": "*"
                }
            ]
        }
    ';
    const SIGNIN_URL = 'https://signin.aws.amazon.com/federation';
    const CONSOLE_URL = 'https://console.aws.amazon.com/';

    protected $fillable = [
        'name',
        'access_key_id',
        'secret_access_key',
    ];
}
