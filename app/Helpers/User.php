<?php

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

define('defautl_path', 'images/default.png');
if (!function_exists('get_data_of_user')) {
    function get_data_of_user($user, $token)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'gender' => $user->gender,
            'Phone_number' => $user->phone_number,
            'date_of_birth' => $user->date_of_birth,
            'photo' => ($user->photo == 0) ? null : Storage::disk('users')->url($user->photo),
            'token' => $token,
        ];
    }
}
if (!function_exists('upload_image')) {
    function upload_image(Request $request, $folder, $name_file_on_request, $disk)
    {
        $fileName = $request->file($name_file_on_request)->getClientOriginalName();
        $extension =pathinfo($fileName, PATHINFO_EXTENSION);
        $file_name = Str::random(32) .'.'. $extension;
        $path = $request->file($name_file_on_request)->storeAs($folder, $file_name, $disk);

        return $path;
    }
}

if (!function_exists('delete_image')) {
    function delete_image($disk, $path)
    {
        Storage::disk($disk)->delete($path);
    }
}

if (!function_exists('SMS_make')) {
    function SMS_make($id, $verificationServices)
    {
        $verification = [];
        $sms_services = $verificationServices;
        $verification['user_id'] = $id;
        $verification_data = $sms_services->setVerificationCode($verification);
        $message = $sms_services->getSMSVerifyMessage($verification_data->code);
        //return app(VonageSMS::class)->sendSms($user->phone_number, $message , 'المعمل');
    }

}


