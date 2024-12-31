<?php


namespace App\Models\Helper;

use App\Enums\OTPThrough;
use App\Enums\OTPType;
use App\Models\Device;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

trait AuthBaseHelper
{
    /**
     * Send the verification code.
     * @param  string  $identifierType
     * @param  string  $type
     * @return array
     */
    public function sendVerificationCode($identifierType, $type, $request = null): array
    {
        // get identifier according to identifier type
        if ($identifierType == OTPThrough::PHONE) {
            $identifier = $request->phone ?? $this->phone;
            $country_code = $request->country_code ?? $this->country_code;
            $final = $country_code . $identifier;
        } else {
            $identifier = $request->email ?? $this->email;
            $country_code = null;
            $final = $identifier;
        }

        $otp = $this->otps()->updateOrCreate([
            'otpable_id' => $this->id,
            'otpable_type' => $this->getMorphClass(),
            'identifier' => $identifier,
            'country_code' => $country_code,
            'type' => $type,
        ], [
            'code' => $this->activationCode(),
        ]);

        // Send otp based on identifier type
        //event(new OtpRequested($otp, $identifierType, $final));

        return ['user' => $this];
    }

    /**
     * check if entity is user.
     * @return boolean
     */
    public function isUser(): bool
    {
        return $this->getMorphClass() == User::class;
    }


    /**
     * Check the verification code.
     * @param string $code
     * @param string $type
     * @param OTPThrough $identifier_type
     * @param null $request
     * @return string
     */
    public function checkOtp($code, $type, $identifier_type = OTPThrough::PHONE, $request = null): string
    {
        // get identifier according to identifier type
        if ($identifier_type == OTPThrough::PHONE) {
            $identifier = $request->phone ?? $this->phone;
            $country_code = $request->country_code ?? $this->country_code;
        } else {
            $identifier = $request->email ?? $this->email;
            $country_code = null;
        }

        $otp = $this->otps()->where([
            'code' => $code,
            'type' => $type,
            'identifier' => $identifier,
            'country_code' => $country_code,
        ])->first();

        if ($otp && !$otp->isExpired()) {
            return 'success';
        } elseif ($otp && $otp->isExpired()) {
            return 'expired';
        } else {
            return 'fail';
        }
    }


    /**
     * generate verification code.
     * @return string
     */
    private function activationCode(): string
    {
        return 1234; // for testing
        //TODO: uncomment this line
        //return mt_rand(1111, 9999);
    }

    /**
     * user login.
     * @return string
     */
    public function login(): string
    {
        // logout all devices
        $this->logoutAll();

        // update user device
        $this->updateDevice();

        // update user lang
        $this->updateLang();

        // if the authenticated user is user
        if ($this->isUser()) {
            $this->moveToAuthenticated();
        }

        return $this->createToken(request()->device_type)->plainTextToken;
    }


    /**
     * update user lang.
     * @return void
     */
    public function updateLang(): void
    {
        if (request()->lang) {
            $lang = request()->lang;
        } else {
            $lang = request()->header('Accept-Language') ?? 'ar';
        }

        if (request()->device_id && request()->device_type) {
            Device::firstWhere([
                'device_id' => request()->device_id,
                'device_type' => request()->device_type,
            ])->update(['preferred_locale' => $lang]);
        }
    }


    /**
     * update user device.
     * @return void
     */
    public function updateDevice(): void
    {
        if (request()->device_id && request()->device_type) {
            $this->currentDevice()->update(['is_current' => false]);
            $this->devices()->updateOrCreate([
                'device_id'   => request()->device_id,
                'device_type' => request()->device_type,
            ], [
                'mac_address' => request()->mac_address,
                'is_current'   => true,
            ]);
        }
    }


    /**
     * move the user's cart to the authenticated user.
     * @return void
     */
    public function moveToAuthenticated(): void
    {
        // get the current device mac address
        $mac_address = $this->currentDevice()->first()?->mac_address;

        // logic depends on the user mac address
        //code...
    }



    /**
     * user logout.
     * @return boolean
     */
    public function logout(): bool
    {
        $this->tokens()?->delete();
        return true;
    }


    /**
     * user logout from all devices.
     * @return boolean
     */
    public function logoutAll(): bool
    {
        $this->tokens()->delete();
        return true;
    }
}
