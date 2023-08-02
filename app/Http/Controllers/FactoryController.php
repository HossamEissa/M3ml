<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFactoryRequest;
use App\Http\Requests\EditFactoryRequest;
use App\Http\Requests\FindFactoryRequest;
use App\Models\Document;
use App\Models\Factory;
use App\Models\User;
use App\Models\UserFactoryPivot;
use App\Traits\responseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FactoryController extends Controller
{
    use responseTrait;

    public function find(FindFactoryRequest $request)
    {
        DB::beginTransaction();
        try {
            $factory = Factory::where('user_name', $request->user_name)->first();
            $user = auth('api')->user();
            $user->factories()->sync([$factory->id], true);

            DB::commit();
            return $this->returnData('data', $factory);
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            return $this->returnError($error = "", $msg);
        }
    }

    public function edit(EditFactoryRequest $request)
    {
        try {
            $factory = Factory::where('user_name', $request->user_name)->first();
            $factory->update([
                'name' => $request->name,
                'title' => $request->title,
                'description' => $request->description
            ]);
            return $this->returnSuccessMessage('تم تعديل البيانات بنجاح');
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            return $this->returnError($error = "", $msg);
        }
    }

    public function allUsers(Request $request)
    {
        try {
            $valid = Validator::make($request->all(), [
                'name' => 'required|exists:factories,user_name|exists:admins,name'
            ]);

            if ($valid->fails()) {
                return $this->returnValidationError($valid);
            }

            $factory = Factory::where('user_name', $request->name)->first();

            $users = $factory->users;


            $formattedResult = $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    "name" => $user->name,
                    "Phone_number" => $user->phone_number,
                    "date_of_birth" => $user->date_of_birth,
                    "gender" => $user->gender,
                    'path' => ($user->photo == 0) ? null : Storage::disk('users')->url($user->photo),
                ];
            });
            return $this->returnData('data', $formattedResult);
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            return $this->returnError($error = "", $msg);
        }
    }

}
