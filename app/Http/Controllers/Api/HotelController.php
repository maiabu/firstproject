<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Http\Resources\HotelResource;
use App\Traits\ApiResponse;
use App\Http\Requests\HotelRequest;

class HotelController extends Controller
{
    use ApiResponse;
     public function store(HotelRequest $request)
    {

        $user = $request->user();
        if(!$user){
              return $this-> unauthorized([
            'message'=>'unauthorized'
         ],401);
        }

        $validate = $request->validate();

        $hotel = Hotel::create($validate);

        $hotel ->addMediaFromRequest('cover')->toMediaCollection('cover');

        return response()->json([
            'status' => true,
            'message' => [
                'ar' => 'تم بنجاح',
                'en' => 'succssefully'

            ],
            
           'data'=> new HotelResource($hotel)
            ]);
    }

    public function destroy(Request $request, $id)
    {

        $user = $request->user();

        // بنتاكد المستخدم مسجل دخول
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => [
                    'ar' => 'يجب تسجيل الدخول',
                    'en' => 'Unauthenticated'
                ]
            ]);
        }

        // اجد الفندق

        $hotel = Hotel::find($id);

        if (!$hotel) {
            return response()->json([
                'status' => false,
                'message' => [
                    'ar' => 'غير موجود',
                    'en' => 'not found'
                ]
            ]);
        }

        $hotel->delete();

        return response()->json([
            'status' => true,
            'message' => [
                'ar' => 'تم الحذف بنجاح',
                'en' => 'deleted successfuly'
            ]
        ]);
    }

    public function update(HotelRequest $request, $id)
    {

        $user = $request->user();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => [
                    'ar' => 'يجيب تسجيل الدخول',
                    'en' => 'Unauthenticated'
                ]

            ]);
        }

        $hotel = Hotel::findOrFail($id);

        if (!$hotel) {
            return response()->json([
                'status' => false,
                'message' => [
                    'ar' => 'غير موجود',
                    'en' => 'not found'
                ]
            ]);
        }

        $validate = $request->validate();

        $hotel->update($validate);

        return response()->json([
            'status' => true,
            'message' => [
                'ar' => 'تم بنجاح',
                'en' => 'update successfuly',
            ]
        ]);
    }

    public function index(){
        $hotels=Hotel::all();
        return response()->json([
            'status'=> true,
            'message'=> 'successfully',
            'data'=> HotelResource::collection($hotels)
        ]);
    }

    public function show($id){
        $hotel =Hotel::findOrFail($id);
        return response()->json([
            'status'=>true,
            'message'=>'successfully',
            'data'=> new HotelResource($hotel)
        ]);
    }
}


