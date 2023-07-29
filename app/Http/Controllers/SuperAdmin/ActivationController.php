<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Factory;
use App\Rules\NoSpaces;
use App\Traits\responseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ActivationController extends Controller
{
    use responseTrait;

    public function activation(Request $request)
    {
        try {

            $valid = Validator::make($request->all(), [
                'name' => ['required', 'exists:factories,name', 'string', new NoSpaces()],
                'active' => 'required|boolean'
            ]);

            if ($valid->fails()) {
                $this->returnValidationError($valid);
            }

            $factory = Factory::where('name', $request->name)->first();
            $factory->update([
                'active' => $request->active,
            ]);
            if ($request->active) {
                return $this->returnSuccessMessage('تم تفعيل المعمل بنجاح');
            } else {
                return $this->returnSuccessMessage('تم ايقاف تفعيل المعمل بنجاح');
            }
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            return $this->returnError($error = "", $msg);
        }
    }
}
