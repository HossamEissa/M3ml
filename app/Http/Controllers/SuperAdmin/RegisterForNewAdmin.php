<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Requests\CreateFactoryRequest;
use App\Http\Requests\RegisterAdminRequest;
use App\Models\Admin;
use App\Models\Factory;
use App\Traits\responseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterForNewAdmin
{
    use  responseTrait;

    public function __construct()
    {

    }

    public function register(RegisterAdminRequest $request)
    {
        DB::beginTransaction();
        try {

            $admin = Admin::create([
                'name' => $request->input('user_name'),
                'phone' => $request->input('phone'),
                'password' => Hash::make($request->input('password'))
            ]);

            DB::commit();

            $msg = "تم تسجيل المسئول بنجاح";
            return $this->returnSuccessMessage($msg);
        } catch (\Exception $e) {
            DB::rollBack();
            $msg['msg'] = "حدث خطأ ما حاول مجددا فى وقت لاحق ";
            $msg['error'] = $e->getMessage();
            return $this->returnError($error = "", $msg);
        }
    }

    public function create(CreateFactoryRequest $request)
    {
        DB::beginTransaction();
        try {
            Factory::create($request->all());
            DB::commit();
            return $this->returnSuccessMessage('تم تسجيل هذا المعمل بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            $msg['msg'] = "حدث خطأ ما حاول مجددا فى وقت لاحق ";
            $msg['error'] = $e->getMessage();
            return $this->returnError($error = "", $msg);
        }
    }
}
