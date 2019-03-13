<?php

namespace App\Http\Controllers\Api;


use App\Models\Author;
use App\Models\Department;
use function GuzzleHttp\Promise\all;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TransactionType;
use \Validator;
use \Response;
use App\models\Book;

class MainController extends Controller
{

    public function add_book(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'desc' => 'required',
            'photo' => 'required',
            'publish_year'=>'required',
            'department_id' => 'required',
            'author_id' ,
            'ISBN_num'=>'required',
            'auther_name'
        ]);

        if ($validation->fails()) {
            return Response()->json([
                'data' => [
                    'status' => 0,
                    'msg' => 'برجاء ملئ جميع الحقول',
                    'errors' => $validation->errors()
                ]
            ], 200);
        }
        if ($request->has('author_id')){
            $book= Book::create($request->all());

        }
        elseif ($request->has('auther_name')){
            $auth= Author::create([
                'name'=>$request->auther_name
            ]);
            $book= Book::create([
                'title' => $request->title,
                'desc' => $request->desc,
                'photo' => $request->photo,
                'publish_year'=>$request->publish_year,
                'department_id' => $request->department_id,
                'author_id' => $auth->id,
                'ISBN_num'=>$request->ISBN_num,
            ]);

        } else  return Response::json([
            'data' => [
                'status' => 0,
                'message' => 'تأكد من جميع البيانات',
            ]
        ], 200);

        if ($request->hasFile('photo')) {
            $path = public_path();
            $destinationPath = $path . '/uploads/items/'; // upload path
            $photo = $request->file('photo');
            $extension = $photo->getClientOriginalExtension(); // getting image extension
            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
            $photo->move($destinationPath, $name); // uploading file to given path
            $book->update(['photo' => 'uploads/items/' . $name]);
        }

//            if ($request->has('auther_name')){
//                $auth= Author::create([
//                    'name'=>$request->auther_name
//                ]);
//                $book->update(['author_id' => $auth->id]);
//
//            }


        if ($book) {
            $data = [
                'data' => [
                    'status' => 1,
                    'msg' => 'تم التسجيل بنجاح',
                    'data' => $book->load('author','department')

                ]
            ];
            return Response::json($data, 200);
        } else {
            return Response::json([
                'data' => [
                    'status' => 0,
                    'message' => 'حدث خطأ ، حاول مرة أخرى',
                ]
            ], 200);
        }
    }
    public function add_dept(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',

        ]);

        if ($validation->fails()) {
            return Response()->json([
                'data' => [
                    'status' => 0,
                    'msg' => 'برجاء ملئ جميع الحقول',
                    'errors' => $validation->errors()
                ]
            ], 200);
        }
        $dept = Department::create($request->all());
        return responseJson(1,'تم التحميل',$dept);
    }
    public function authers(Request $request)
    {
        $authers = Author::all();
        return responseJson(1,'تم التحميل',$authers);
    }
    public function transactions(Request $request)
    {
        $transaction_types = TransactionType::all();
        return responseJson(1,'تم التحميل',$transaction_types);
    }
    public function departments(Request $request)
    {
        $depts = Department::all();
        return responseJson(1,'تم التحميل',$depts);
    }
    public function books(Request $request)
    {

        $books = Book::where(function($q) use($request){
            if ($request->has('book_name')){
                $q->where('title','LIKE','%'.$request->book_name.'%');
            }
        })->paginate(10);
        return responseJson(1,'تم التحميل',$books->load('author','department'));
    }
    public function create_offer(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'desc' => 'required',
            'photo' => 'required',
            'publish_year'=>'required',
            'department_id' => 'required',
            'author_id' ,
            'ISBN_num'=>'required',
            'auther_name'
        ]);

        if ($validation->fails()) {
            return Response()->json([
                'data' => [
                    'status' => 0,
                    'msg' => 'برجاء ملئ جميع الحقول',
                    'errors' => $validation->errors()
                ]
            ], 200);
        }
        if ($request->has('author_id')){
            $book= Book::create($request->all());

        }
        elseif ($request->has('auther_name')){
            $auth= Author::create([
                'name'=>$request->auther_name
            ]);
            $book= Book::create([
                'title' => $request->title,
                'desc' => $request->desc,
                'photo' => $request->photo,
                'publish_year'=>$request->publish_year,
                'department_id' => $request->department_id,
                'author_id' => $auth->id,
                'ISBN_num'=>$request->ISBN_num,
            ]);

        } else  return Response::json([
            'data' => [
                'status' => 0,
                'message' => 'تأكد من جميع البيانات',
            ]
        ], 200);




        if ($book) {
            $data = [
                'data' => [
                    'status' => 1,
                    'msg' => 'تم التسجيل بنجاح',
                    'data' => $book->load('author','department')

                ]
            ];
            return Response::json($data, 200);
        } else {
            return Response::json([
                'data' => [
                    'status' => 0,
                    'message' => 'حدث خطأ ، حاول مرة أخرى',
                ]
            ], 200);
        }
    }

}
