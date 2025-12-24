<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\categoryResource;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    public function store(Request $request){
        $user=$request->user();
        if(!$user){
            return response()->json([
                'status'=> false,
                'message'=>[
                    'ar'=>'يجب تسجل الدخول',
                    'en'=>'Unauthenticated'
                ]
                ]);
        }
        
        $validate = $request->validated([
             'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'cover'   =>['required','image','mimes:jpg,png,jpeg,gif','max:2024'],
        ]);

        $category =Category::create($validate);

        $category ->addMediaFromRequest('cover')->toMediaCollection('cover');

         return response()->json([
            'status' => true,
            'message' => [
                'ar' => 'تم بنجاح',
                'en' => 'succssefully'

            ],
         
            'data'=>[
            'id' => $category->id,
            'name' => $category->name,
            'description' => $category->description,
            'cover' => $category->cover_url,
            ]
            ]);
}

    public function destroy(Request $request, $id)
    {

        $user = $request->user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => [
                    'ar' => 'يجب تسجيل الدخول',
                    'en' => 'Unauthenticated'
                ]
            ]);
        }


        $category= Category::find($id);

        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => [
                    'ar' => 'غير موجود',
                    'en' => 'not found'
                ]
            ]);
        }

        $category->delete();

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

        $category = Category::findOrFail($id);

        if (!$category) {
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
        ]);  
        
        $category->update($validate);

        return response()->json([
            'status' => true,
            'message' => [
                'ar' => 'تم بنجاح',
                'en' => 'update successfuly',
            ]
        ]);
}
      public function index(){
        $categories=Category::all();
        return response()->json([
            'status'=> true,
            'message'=> 'successfully',
            'data'=> categoryResource::collection($categories)
        ]);
    }



    
    public function show($id){
        $category =Category::findOrFail($id);
        return response()->json([
            'status'=>true,
            'message'=>'successfully',
            'data'=> new categoryResource($category)
        ]);
    }
}