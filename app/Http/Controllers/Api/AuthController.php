<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function reset(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'email' => 'required'
        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return responseJson(0,$validation->errors()->first(),$data);
        }

        $user = Student::where('Email',$request->email)->first();
        if ($user){
            $code = rand(111111,999999);
            $update = $user->update(['pin_code' => $code]);
            if ($update)
            {
                // send email
                Mail::to($user->Email)
                    ->bcc("nohamelmandoh@gmail")
                    ->send(new resetpassword($code));
                // send('emails.reset', ['pin_code' => $code], function ($mail) use($user) {
                // $mail->from('nohamelmandoh@gmail.com', 'ØªØ·Ø¨ÙŠÙ‚ yusor');

                // $mail->to($user->Email, $user->name)->subject('Ø¥Ø¹Ø§Ø¯Ø© ØªØ¹ÙŠÙŠÙ† ÙƒÙ„Ù…Ø© Ø§Ù„Ù…Ø±ÙˆØ±');
                // });

                return responseJson(1,'email sent');
            }else{
                return responseJson(0,'error');
            }
        }else{
            return responseJson(0,'email not exist');
        }
    }
    public function check_code(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'code' => 'required',

        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return responseJson(0,$validation->errors()->first(),$data);
        }

        $user = Student::where('pin_code',$request->code)->where('pin_code','!=',0)->first();

        if ($user)
        {

            return responseJson(1,'code valid',[$user]);

        }else{
            return responseJson(0,'code not valid');
        }
    }
    public function update_password(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'student_id'=>'required',
            'password' => 'confirmed'
        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return responseJson(0,$validation->errors()->first(),$data);
        }

        $user = Student::find($request->student_id);

        if ($user)
        {
            $update = $user->update(['password' => bcrypt($request->password), 'code' => null]);
            if ($update)
            {
                return responseJson(1,'password changed');
            }else{
                return responseJson(0,'faild, try again');
            }
        }else{
            return responseJson(0,'error');
        }
    }

    public function register(Request $request)
    {// 'Gender','department_id'
        $validation = validator()->make($request->all(), [
            'perso_name' => 'required',
            'Email' => 'required|regex:/^.+@taibahu.edu.sa+$/i|unique:students',
            'UserName' => 'required|unique:students',
            'password' => 'required|confirmed',
            'department_id' => 'required',
            'Gender'=>'required',
            'firebase_id'=>'required'
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

            $data =[
                'api_token' => $api_token,
                'user' => [$user],
            'department'=>[$user->load('department')]
            ];

            return responseJson(1,'تم التسجيل بنجاح',$data);
        } else {
            return responseJson(0,'حدث خطأ ، حاول مرة أخرى');
        }
    }
    public function login(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'email' => 'required|regex:/^.+@taibahu.edu.sa+$/i',
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
    public function signout(Request $request){
        $request->session()->forget('adminLogin');
        return redirect('/login');
    }

}
