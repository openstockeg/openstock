<?php

namespace App\Enums;

enum OTPType: string
{
    case VERIFICATION = 'verification';
    case LOGIN = 'login';
    case FORGET_PASSWORD = 'forget_password';
    case PHONE_OWNERSHIP = 'phone_ownership';
    case UPDATE_PHONE = 'update_phone';
    case UPDATE_EMAIL = 'update_email';
}
