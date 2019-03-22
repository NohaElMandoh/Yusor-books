<?php

namespace App\Http\Controllers\Api;


use App\Models\Author;
use App\Models\Bill;
use App\Models\Department;
use App\Models\Student;
use function GuzzleHttp\Promise\all;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TransactionType;
use \Validator;
use \Response;
use App\Models\Book;

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
//            'data' => [
                'status' => 0,
                'message' => 'تأكد من جميع البيانات',
//            ]
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

        if ($book) {
            $data = [
//                'data' => [
                    'status' => 1,
                    'msg' => 'تم التسجيل بنجاح',
                    'data' => $book->load('author','department')

//                ]
            ];
            return Response::json($data, 200);


        } else {
            return Response::json([
//                'data' => [
                    'status' => 0,
                    'message' => 'حدث خطأ ، حاول مرة أخرى',
//                ]
            ], 200);
        }
    }
    public function update_book(Request $request)
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
            $book= Book::update([
                'title' => $request->title,
                'desc' => $request->desc,
                'photo' => $request->photo,
                'publish_year'=>$request->publish_year,
                'department_id' => $request->department_id,
                'author_id' => $auth->id,
                'ISBN_num'=>$request->ISBN_num,
            ]);
        } else  return Response::json([
            'status' => 0,
            'message' => 'تأكد من جميع البيانات',

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

        if ($book) {
            $data = [
                'status' => 1,
                'msg' => 'Done',
                'data' => $book->load('author','department')

            ];
            return Response::json($data, 200);


        } else {
            return Response::json([
                'status' => 0,
                'message' => 'حدث خطأ ، حاول مرة أخرى',
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
        })->get();

        $arrNewSku = array();
        $arrays =(array)$books->load('author','department')->toArray();

        for ($i = 0; $i < count($arrays);$i++) {
            $arrNewSku[$i]['id'] = $arrays[$i]['id'];
            $arrNewSku[$i]['created_at'] = $arrays[$i]['created_at'];
                 $arrNewSku[$i]['updated_at'] = $arrays[$i]['updated_at'];
                 $arrNewSku[$i]['title'] = $arrays[$i]['title'];
                 $arrNewSku[$i]['desc'] = $arrays[$i]['desc'];
                 $arrNewSku[$i]['publish_year'] = $arrays[$i]['publish_year'];
                 $arrNewSku[$i]['author_id'] =$arrays[$i]['author_id'];
                 $arrNewSku[$i]['department_id'] = $arrays[$i]['department_id'];
                 $arrNewSku[$i]['photo'] = $arrays[$i]['photo'];
                 $arrNewSku[$i]['ISBN_num'] = $arrays[$i]['ISBN_num'];
                 $arrNewSku[$i]['author'] = [$arrays[$i]['author']];
                 $arrNewSku[$i]['department'] = [$arrays[$i]['department']];
        }
                 return responseJson(1,'تم التحميل',$arrNewSku);
    }
    public function create_offer(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'book_id' => 'required',
            'price' => 'required',
            'book_status' => 'required',
            'availability' => 'required',
            'transaction_types_id' => 'required',

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
        $item = Book::find($request->book_id);
        $readyItem = [
            $item->id => [
                'price' => $request->price,
                'book_status' => $request->book_status,
                'availability' => $request->availability,
                'transaction_types_id' => $request->transaction_types_id
            ]
        ];
        $offer = $request->user()->books()->attach($readyItem);
        if ($offer) {

        return responseJson(1, 'offer added');
    }else  return responseJson(1, 'offer Not added');
    }
    public function books_student(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'student_id' ,

        ]);
        if ($validation->fails()) {
            return Response()->json([
                    'status' => 0,
                    'msg' => 'برجاء ملئ جميع الحقول',
                    'errors' => $validation->errors()
            ], 200);
        }
        $arrNewSku = array();

        $user =Student::all();
        foreach ($user as $u) {
            foreach ($u->books as $user_book) {
                $items = $user_book->pivot->get();
                $arrays =(array)$items->toArray();
                for ($i = 0; $i < count($arrays);$i++) {
                    $book =Book::find( $arrays[$i]['book_id']);
                    $student =Student::find( $arrays[$i]['student_id']);
                    $author=Author::find($book->author_id);
                    $department=Department::find($book->department_id);
                    $arrNewSku[$i]['student_id'] = $arrays[$i]['student_id'];
                    $arrNewSku[$i]['created_at'] = $arrays[$i]['created_at'];
                    $arrNewSku[$i]['updated_at'] = $arrays[$i]['updated_at'];
                    $arrNewSku[$i]['book_id'] = $arrays[$i]['book_id'];
                    $arrNewSku[$i]['price'] = $arrays[$i]['price'];
                    $arrNewSku[$i]['availability'] = $arrays[$i]['availability'];
                    $arrNewSku[$i]['book_status'] = $arrays[$i]['book_status'];
                    $arrNewSku[$i]['transaction_types_id'] = $arrays[$i]['transaction_types_id'];
                    $arrNewSku[$i]['pivot'] = [$book];
                    $arrNewSku[$i]['student'] = [$student];
                    $arrNewSku[$i]['author'] = [$author];

                }
                        return responseJson(1,'Done',$arrNewSku);
        }}


    }
    public function similar_books(Request $request)
    {
        $item = Book::find($request->book_id);
        $items = $request->user()->books()->where('book_id',$item->id)->with('author','department')->get();
        $arrNewSku = array();
        $arrays =(array)$items->toArray();
        for ($i = 0; $i < count($arrays);$i++) {
            $arrNewSku[$i]['id'] = $arrays[$i]['id'];
            $arrNewSku[$i]['created_at'] = $arrays[$i]['created_at'];
            $arrNewSku[$i]['updated_at'] = $arrays[$i]['updated_at'];
            $arrNewSku[$i]['title'] = $arrays[$i]['title'];
            $arrNewSku[$i]['desc'] = $arrays[$i]['desc'];
            $arrNewSku[$i]['publish_year'] = $arrays[$i]['publish_year'];
            $arrNewSku[$i]['author_id'] =$arrays[$i]['author_id'];
            $arrNewSku[$i]['department_id'] = $arrays[$i]['department_id'];
            $arrNewSku[$i]['photo'] = $arrays[$i]['photo'];
            $arrNewSku[$i]['ISBN_num'] = $arrays[$i]['ISBN_num'];
            $arrNewSku[$i]['pivot'] = [$arrays[$i]['pivot']];
            $arrNewSku[$i]['author'] = [$arrays[$i]['author']];

        }
        return responseJson(1,'Done',$arrNewSku);
//        return responseJson(1,'',$items);

    }
    public function create_bill(Request $request)
    {
        //`bills`
        $validation = Validator::make($request->all(), [
            'book_id' => 'required',
            'buyer_id' => 'required',//saler
            'TotalAmount' => 'required',
            'owner_status' => 'required',
            'buyer_status' => 'required',

        ]);

        if ($validation->fails()) {
            return Response()->json([
                'status' => 0,
                'msg' => 'please fill all fields',
                'errors' => $validation->errors()
            ], 200);
        }
            $item = Book::find($request->book_id);
            if ($item) {

                $bill = $request->user()->bill()->with('student_buy')->create($request->all());
                if ($bill) {
                    return responseJson(1, 'bill added',$bill->load('book'));
                } else return responseJson(0, 'bill  not added');

            } else  return responseJson(0, 'book Not found');

//        } else return responseJson(0, 'seller_id not found');
    }
    public function Bills_sold(Request $request)//
    {
        $bills=$request->user()->bill()->where(function($order) use($request){
            if ($request->has('state') && $request->state == 'completed')
            {
                $order->where('owner_status',1)->where('buyer_status',1);

            }
            elseif ($request->has('state') && $request->state == 'pending')
            {
                $order->where('owner_status',1)->where('buyer_status',0)
                    ->orWhere(function ($query) {
            $query->where('owner_status',0)->where('buyer_status',1);
        })->orWhere(function ($query) {
                        $query->where('owner_status',0)->where('buyer_status',0);
                    });
            }
        })->get();

        $arrNewSku = array();
        $arrays =(array)$bills->load('student_buy','book')->toArray();

        for ($i = 0; $i < count($arrays);$i++) {
            $arrNewSku[$i]['id'] = $arrays[$i]['id'];
            $arrNewSku[$i]['created_at'] = $arrays[$i]['created_at'];
            $arrNewSku[$i]['updated_at'] = $arrays[$i]['updated_at'];
            $arrNewSku[$i]['TotalAmount'] = $arrays[$i]['TotalAmount'];
            $arrNewSku[$i]['book_id'] = $arrays[$i]['book_id'];
            $arrNewSku[$i]['buyer_id'] = $arrays[$i]['buyer_id'];
            $arrNewSku[$i]['owner_status'] =$arrays[$i]['owner_status'];
            $arrNewSku[$i]['buyer_status'] = $arrays[$i]['buyer_status'];
            $arrNewSku[$i]['student_id'] = $arrays[$i]['student_id'];
            $arrNewSku[$i]['student_buy'] = $arrays[$i]['student_buy'];
            $arrNewSku[$i]['book'] = [$arrays[$i]['book']];
        }
        return responseJson(1,'تم التحميل',$arrNewSku);
    }
    public function acceptOrder(Request $request)
    {
//        $order= $request->user()->orders()->find($request->order_id);
//        if (!$order)
//        {
//            return responseJson(0,'لا يمكن الحصول على بيانات الطلب');
//        }
//        if ($order->state == 'accepted')
//        {
//            return responseJson(1,'تم قبول الطلب');
//        }
//        $order->update(['state' => 'accepted']);
//        $client = $order->client;
//        $client->notifications()->create([
//            'title' => 'تم قبول طلبك',
//            'title_en' => 'Your order is accepted',
//            'content' => 'تم قبول الطلب رقم '.$request->order_id,
//            'content_en' => 'Order no. '.$request->order_id.' is accepted',
//            'order_id' => $request->order_id,
//        ]);
//
//        $tokens = $client->tokens()->where('token','!=','')->pluck('token')->toArray();
//        $audience = ['include_player_ids' => $tokens];
//        $contents = [
//            'en' => 'Order no. '.$request->order_id.' is accepted',
//            'ar' => 'تم قبول الطلب رقم '.$request->order_id,
//        ];
//        $send = notifyByOneSignal($audience , $contents , [
//            'user_type' => 'client',
//            'action' => 'accept-order',
//            'order_id' => $request->order_id,
//            'restaurant_id' => $request->user()->id,
//        ]);
//        $send = json_decode($send);
//        return responseJson(1,'تم قبول الطلب');
    }
    }
