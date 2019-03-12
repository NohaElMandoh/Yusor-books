<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function register(Request $request)
    {//'perso_name', 'G_email', 'photo', 'access_token', 'user_id', 'password', 'major'
        $validation = validator()->make($request->all(), [
            'perso_name' => 'required',
            'G_email' => 'required|regex:/^.+@taibahu.edu.sa+$/i|unique:students',
            'photo' => 'required',
            'password' => 'required|confirmed',
            'major' => 'required',
            'user_id'=>'0123'
        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return responseJson(0,$validation->errors()->first(),$data);
        }

        $api_token = str_random(60);
        $request->merge(array('api_token' => $api_token));
        $request->merge(array('password' => bcrypt($request->password)));
        $user = Student::create($request->all());

        if ($request->hasFile('photo')) {
            $path = public_path();
            $destinationPath = $path . '/uploads/profile/'; // upload path
            $logo = $request->file('photo');
            $extension = $logo->getClientOriginalExtension(); // getting image extension
            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
            $logo->move($destinationPath, $name); // uploading file to given path
            $user->update(['photo' => 'uploads/profile/' . $name]);
        }

        if ($user) {
            $data = [
                'access_token' => $api_token,
                'user' => $user
            ];

            return responseJson(1,'تم التسجيل بنجاح',$data);
        } else {
            return responseJson(0,'حدث خطأ ، حاول مرة أخرى');
        }
    }
    public function login(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return responseJson(0,$validation->errors()->first(),$data);
        }

        $user = Student::where('G_email', $request->input('email'))->first();
        if ($user)
        {
            if (Hash::check($request->password, $user->password))
            {
                $data = [
                    'access_token' => $user->access_token,
                    'user' => $user,
                ];
                return responseJson(1,'تم تسجيل الدخول',$data);
            }else{
                return responseJson(0,'بيانات الدخول غير صحيحة');
            }
        }else{
            return responseJson(0,'بيانات الدخول غير صحيحة');
        }
    }
//    get student info  according to ap_token
//   update student info if data passed
    public function profile(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'password' => 'confirmed',
        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return responseJson(0,$validation->errors()->first(),$data);
        }

        if ($request->has('perso_name')) {
            $request->user()->update($request->only('perso_name'));
        }
        if ($request->has('G_email')) {
            $request->user()->update($request->only('G_email'));
        }
        if ($request->has('password')) {
            $request->merge(array('password' => bcrypt($request->password)));
            $request->user()->update($request->only('password'));
        }

        if ($request->has('major')) {
            $request->user()->update($request->only('major'));
        }
//        if ($request->has('photo')) {
//            if ($request->hasFile('photo')) {
//                $path = public_path();
//                $destinationPath = $path . '/uploads/items/'; // upload path
//                $photo = $request->file('photo');
//                $extension = $photo->getClientOriginalExtension(); // getting image extension
//                $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
//                $photo->move($destinationPath, $name); // uploading file to given path
//                $item->update(['photo' => 'uploads/items/' . $name]);
//            }
//
//            $request->user()->update($request->only('photo'));
//        }



        $data = [
            'user' => $request->user()
        ];
        return responseJson(1,'تم تحديث البيانات',$data);
    }


}
