<?php
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
define('defautl_path' , 'images/default.png');
if (!function_exists('get_data_of_user')){
   function get_data_of_user($user, $token)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'gender' => $user->gender,
            'Phone_number' => $user->phone_number,
            'date_of_birth' => $user->date_of_birth,
            'photo' => ($user->photo == 0 ) ? null :asset('public/images/'.$user->photo),
            'token' => $token,
        ];
    }
}
if (!function_exists('upload_image')){
    function upload_image(Request $request, $folder, $file  ,$disk)
    {
        $file_name = Str::random(32).'.'.$request->file($file)->getClientOriginalExtension();
        $path = $request->file($file)->storeAs($folder, $file_name, $disk);

        return $path;
    }
}

if (!function_exists('delete_image')){
    function delete_image($disk , $path)
    {
        Storage::disk($disk)->delete($path);
    }
}


