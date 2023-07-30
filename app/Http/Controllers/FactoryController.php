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

class FactoryController extends Controller
{
    use responseTrait;

    public function find(FindFactoryRequest $request)
    {
        DB::beginTransaction();
        try {
            $factory = Factory::where('name', $request->name)->first();
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
            $factory = Factory::where('name', $request->name)->first();
            $factory->update([
                'title' => $request->title,
                'description' => $request->description
            ]);
            return $this->returnSuccessMessage('تم تعديل البيانات بنجاح');
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            return $this->returnError($error = "", $msg);
        }
    }

    public function change_password()
    {

    }

}
