<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddOfferRequest;
use App\Http\Requests\DeleteOfferRequest;
use App\Http\Requests\EditOfferRequest;
use App\Http\Requests\ShowOfferRequest;
use App\Models\Factory;
use App\Models\FactoryOffer;
use App\Traits\responseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FactoryOfferController extends Controller
{
    use responseTrait;

    public function add(AddOfferRequest $request)
    {
        DB::beginTransaction();
        try {
            $factory = Factory::select('id')->where('user_name', $request->name)->first();
            $offer = FactoryOffer::create([
                'title' => $request->title,
                'factory_id' => $factory->id,
                'description' => $request->description ?? null,
                'photo_path' => null
            ]);


            if ($request->hasFile('photo')) {
                $image_path = upload_image($request, $factory->id, 'photo', 'offers');
                $offer->photo_path = $image_path;
                $offer->save();
            }

            DB::commit();
            return $this->returnSuccessMessage('تم اضافة العرض بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            return $this->returnError($error = "", $msg);
        }
    }

    public function show(ShowOfferRequest $request)
    {
        try {
            $factory = Factory::where('name', $request->name)->first();
            $offers = $factory->offers()->select('id', 'title', 'description', 'photo_path')->latest()->get();
            $formattedResult = $offers->map(function ($offer) {
                return [
                    'id' => $offer->id,
                    'title' => $offer->title,
                    'description' => $offer->description,
                    'photo_path' => $offer->photo_path ? Storage::disk('offers')->url($offer->photo_path) : $offer->photo_path
                ];
            });
            return $this->returnData('data', $formattedResult);
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            return $this->returnError($error = "", $msg);
        }
    }

    public function edit(EditOfferRequest $request)
    {

        try {
            $offers = FactoryOffer::find($request->id);
            $offers->update([
                'title' => $request->title,
                'description' => $request->description,
                'photo_path' => $offers->photo_path
            ]);

            if ($request->hasFile('photo')) {
                if ($offers->photo_path != null) {
                    $this->make_delete($request->id);
                }
                $image_path = upload_image($request, $offers->factory_id, 'photo', 'offers');
                $offers->photo_path = $image_path;
                $offers->save();
            }
            $msg = "تم التعديل بنجاح";
            return $this->returnSuccessMessage($msg);
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            return $this->returnError($error = "", $msg);
        }

    }

    public function delete(DeleteOfferRequest $request)
    {
        try {
            $offer_to_delete = $this->make_delete($request->id);
            $offer_to_delete->delete();
            $msg = 'تم الحذف بنجاح';
            return $this->returnSuccessMessage($msg);

        } catch (\Exception $e) {
            $msg = $e->getMessage();
            return $this->returnError($error = "", $msg);
        }
    }

    public function make_delete($id)
    {
        $offer_to_delete = FactoryOffer::find($id);

        $path = $offer_to_delete->photo_path;
        $find = Storage::disk('offers')->exists($path);
        if ($find) {
            Storage::disk('offers')->delete($path);
        }
        return $offer_to_delete;
    }

}
