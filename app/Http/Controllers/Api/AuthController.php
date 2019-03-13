<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function register(Request $request)
    {// 'Gender','department_id'
        $validation = validator()->make($request->all(), [
            'perso_name' => 'required',
            'Email' => 'required|regex:/^.+@taibahu.edu.sa+$/i|unique:students',
            'UserName' => 'required|unique:students',
            'password' => 'required|confirmed',
            'department_id' => 'required',
            'Gender'=>'required'
        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return responseJson(0,$validation->errors()->first(),$data);
        }

        $api_token = str_random(60);
        $request->merge(array('api_token' => $api_token));
        $request->merge(array('password' => bcrypt($request->password)));
        $user = Student::create($request->all());



        if ($user) {
            $data = [
                'api_token' => $api_token,
                'user' => $user->load('department')
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

        $user = Student::where('Email', $request->input('Email'))->first();
        if ($user)
        {
            if (Hash::check($request->password, $user->password))
            {
                $data = [
                    'api_token' => $user->api_token,
                    'user' => $user->load('department'),
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
        if ($request->has('Email')) {
            $request->user()->update($request->only('Email'));
        }
        if ($request->has('password')) {
            $request->merge(array('password' => bcrypt($request->password)));
            $request->user()->update($request->only('password'));
        }

        if ($request->has('department_id')) {
            $request->user()->update($request->only('department_id'));
        }
        if ($request->has('Gender')) {
            $request->user()->update($request->only('Gender'));
        }



        $data = [
            'user' => $request->user()->load('department')
        ];
        return responseJson(1,'تم تحديث البيانات',$data);
    }


}
