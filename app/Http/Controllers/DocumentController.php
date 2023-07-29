<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddDocumentRequest;
use App\Http\Requests\DeleteOrDownloadDocumentRequest;
use App\Http\Requests\ShowDocumentRequest;
use App\Models\Document;
use App\Models\Factory;
use App\Models\User;
use App\Models\UserFactoryPivot;
use App\Traits\responseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    use responseTrait;

    public function add(AddDocumentRequest $request)
    {
        DB::beginTransaction();
        try {
            $factory = Factory::where('name', auth('admin')->user()->name)->first();
            $user = User::where('phone_number', $request->phone)->first();
            $user->factories()->sync([$factory->id], true);

            $pivot = UserFactoryPivot::where('user_id', $user->id)->where('factory_id', $factory->id)->first();
            $document = Document::create([
                'user_factory_id' => $pivot->id,
                'path' => '0'
            ]);

            if ($request->hasFile('photo')) {
                $image_path = upload_image($request, $user->id, 'photo', 'documents');
                $document->path = $image_path;
                $document->save();
            }

            DB::commit();
            $msg = "تم اضافة الصورة بنجاح";
            return $this->returnSuccessMessage($msg);
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            return $this->returnError($error = "", $msg);
        }
    }


    public function show(ShowDocumentRequest $request)
    {
        try {
            $factory = Factory::where('name', $request->name)->first();
            $user = User::where('phone_number', $request->phone)->first();
            $pivot = UserFactoryPivot::where('user_id', $user->id)->where('factory_id', $factory->id)->first();
            $images = $pivot->images()->orderBy('created_at', 'desc')->select('id', 'path')->get();
            $formattedResult = $images->map(function ($image) {
                return [
                    'id' => $image->id,
                    'path' => Storage::disk('documents')->url($image->path),
                ];
            });
            return $this->returnData('data', $formattedResult);
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            return $this->returnError($error = "", $msg);
        }
    }

    public function delete(DeleteOrDownloadDocumentRequest $request)
    {
        try {
            $document_to_delete = Document::find($request->id);

            $path = $document_to_delete->path;
            $find = Storage::disk('documents')->exists($path);
            $msg = "";
            if ($find) {
                Storage::disk('documents')->delete($path);
                $msg = 'تم الحذف بنجاح';
            } else {
                $msg = 'تم حذف الصوره بالفعل من قبل';
            }
            $document_to_delete->delete();
            return $this->returnSuccessMessage($msg);

        } catch (\Exception $e) {
            $msg = $e->getMessage();
            return $this->returnError($error = "", $msg);
        }

    }

    public function download(DeleteOrDownloadDocumentRequest $request)
    {
        try {
            $image = Document::find($request->id);

            if ($image) {
                $filePath = $image->path;
                $fullPath = Storage::disk('documents')->path($filePath);
                if (file_exists($fullPath)) {
                    return response()->download($fullPath);
                } else {
                    return $this->returnError('', 'هذه الصورة غير موجوده');
                }
            } else {
                return $this->returnError('', 'هذه الصورة غير موجوده');
            }

        } catch (\Exception $e) {
            $msg = $e->getMessage();
            return $this->returnError($error = "", $msg);
        }
    }

}
