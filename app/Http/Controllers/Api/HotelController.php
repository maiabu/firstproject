<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Http\Resources\HotelResource;
use App\Traits\ApiResponse;

class HotelController extends Controller
{
    use ApiResponse;
     public function store(Request $request)
    {

        $user = $request->user();
        if(!$user){
              return $this-> unauthorized([
            'message'=>'unauthorized'
         ],401);
        }

        $validate = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'city' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'cover'   =>['required','image','mimes:jpg,png,jpeg,gif','max:2024'],
        ]);

        $hotel = Hotel::create($validate);

        $hotel ->addMediaFromRequest('cover')->toMediaCollection('cover');

        return response()->json([
            'status' => true,
            'message' => [
                'ar' => 'تم بنجاح',
                'en' => 'succssefully'

            ],
            'data' => [
                'id'  => $hotel->id,
                'name' => $hotel->name,
                'description' => $hotel->description,
                'category_id' => $hotel->category_id,
                'category' => $hotel->catergory?->name,
                'city' => $hotel->city,
                'address' => $hotel->city,
                'cover' => $hotel->cover_url

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

    public function update(Request $request, $id)
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

        $validate = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'city' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
        ]);

        $hotel->update($validate);

        return response()->json([
            'status' => true,
            'message' => [
                'ar' => 'تم بنجاح',
                'en' => 'update successfuly',
            ]
        ]);
    }
}


