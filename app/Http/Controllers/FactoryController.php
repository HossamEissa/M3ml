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

            $factory = Factory::where('user_name', $request->name);

            return $factory->users();
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            return $this->returnError($error = "", $msg);
        }
    }

}
