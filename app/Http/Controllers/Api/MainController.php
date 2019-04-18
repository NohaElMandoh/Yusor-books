<?php

namespace App\Http\Controllers\Api;


use App\Models\Author;
use App\Models\Bill;
use App\Models\Book_Student;
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
//
    public function update_buyer_atatus(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'bill_id' => 'required',
        ]);

        if ($validation->fails()) {
            return Response()->json([
                'status' => 0,
                'msg' => 'please fill all fields',
                'errors' => $validation->errors()
            ], 200);
        }
        $bill= Bill::find($request->bill_id);

        $bill->update(['buyer_status' => 1]);
        $student= Student::find($bill->student_id);
        foreach ($student->books as $books)
        {
            if( $books->pivot->book_id=$bill->book_id){
                $books->pivot->availability = 1;
                $books->pivot->save();
            }

        }


        return responseJson(1,'Done',$books);

    }
    public function upload_photo(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'book_id' => 'required',

        ]);


        if ($validation->fails()) {
            return responseJson(0,$validation->errors()->first(),$data=[]);

        }
        $book=Book::find( $request->book_id);
        if($book){
            if ($request->hasFile('uploaded_file')) {
                $path = public_path();
                $destinationPath = $path . '/uploads/books/'; // upload path
                $photo = $request->file('uploaded_file');
                $extension = $photo->getClientOriginalExtension(); // getting image extension
                $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
                $photo->move($destinationPath, $name); // uploading file to given path
                $book->update(['photo' => 'uploads/books/' . $name]);
            }
            return responseJson(1,"Done");

        } return responseJson(1," Not Done");
    }
    public function add_book(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'desc' => 'required',
            'photo',
            'publish_year' => 'required',
            'department_id' => 'required',
            'author_id',
            'ISBN_num' => 'required',
            'name'
            // => 'unique:authors',
        ]);
        $user_id = $request->user()->id;

        if ($validation->fails()) {
            return responseJson(0, $validation->errors()->first(), $data = []);

        }
        if ($request->has('author_id')) {
            if (!is_null($request->author_id)) {
                $book = Book::create([
                    'title' => $request->title,
                    'desc' => $request->desc,
                    'photo' => $request->photo,
                    'publish_year' => $request->publish_year,
                    'department_id' => $request->department_id,
                    'author_id' => $request->author_id,
                    'ISBN_num' => $request->ISBN_num,
                    'student_id' => $user_id,
                    'offer_status' => 0
                ]);
                //   return responseJson(1,'added with auth id',$book);
            }


        } elseif ($request->has('name')) {

            $auth = Author::where('name', $request->name)->first();
            //   return responseJson(0,'auth',$auth);
            if ($auth) {
                $book = Book::create([
                    'title' => $request->title,
                    'desc' => $request->desc,
                    'photo' => $request->photo,
                    'publish_year' => $request->publish_year,
                    'department_id' => $request->department_id,
                    'author_id' => $auth->id,
                    'ISBN_num' => $request->ISBN_num,
                    'student_id' => $user_id,
                    'offer_status' => 0
                ]);
                return responseJson(1, 'added with auth id from auth name', $book);
                //if(!$auth)
            }
            // else
            // return responseJson(0,'author not exist');


            else {
                if (!empty($request->name)) {
                    $auth2 = Author::create([
                        'name' => $request->name
                    ]);
                    if ($auth2) {
                        $book = Book::create([
                            'title' => $request->title,
                            'desc' => $request->desc,
                            'photo' => $request->photo,
                            'publish_year' => $request->publish_year,
                            'department_id' => $request->department_id,
                            'author_id' => $auth2->id,
                            'ISBN_num' => $request->ISBN_num,
                            'student_id' => $user_id,
                            'offer_status' => 0
                        ]);
                        //  return responseJson(1,'added with auth id after add auth name',$book);
                    } else
                        return responseJson(0, 'author not added');


                } else  return responseJson(0, 'name is empty');


            }
        }
        // else {
        //     return responseJson(0,$validation->errors()->first(),$data=[]);
        // }
        if ($request->hasFile('photo')) {
            $path = public_path();
            $destinationPath = $path . '/uploads/books/'; // upload path
            $photo = $request->file('photo');
            $extension = $photo->getClientOriginalExtension(); // getting image extension
            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
            $photo->move($destinationPath, $name); // uploading file to given path
            $book->update(['photo' => 'uploads/books/' . $name]);
        }


        if ($book) {
            $data = [
                'status' => 1,
                'msg' => 'Done',
                'data' => [$book]
            ];
            return Response::json($data, 200);
        } else {
            return Response::json([
                'data' => [
                    'status' => 0,
                    'message' => 'error',
                ]
            ], 200);
        }
    }

//    public function add_book(Request $request)
//    {
//        $validation = Validator::make($request->all(), [
//            'title' => 'required',
//            'desc' => 'required',
//            'photo' => 'required',
//            'publish_year'=>'year:required',
//            'department_id' => 'required',
//            'author_id' ,
//            'ISBN_num'=>'required',
//            'name' => 'unique:authors',
//        ]);
//        $user_id=$request->user()->id;
////        return $user_id;
//        if ($validation->fails()) {
//            return Response()->json([
//                    'status' => 0,
//                    'msg' => 'برجاء ملئ جميع الحقول',
//                    'errors' => $validation->errors()
//            ], 200);
//        }
//        if ($request->has('author_id')){
//            $book= Book::create($request->all());
//        }
//        elseif ($request->has('auther_name')){
//            $auth= Author::create([
//                'name'=>$request->auther_name
//            ]);
//            $book= Book::create([
//                'title' => $request->title,
//                'desc' => $request->desc,
//                'photo' => $request->photo,
//                'publish_year'=>$request->publish_year,
//                'department_id' => $request->department_id,
//                'author_id' => $auth->id,
//                'ISBN_num'=>$request->ISBN_num,
//            ]);
//        } else  return Response::json([
////            'data' => [
//                'status' => 0,
//                'message' => 'تأكد من جميع البيانات',
////            ]
//        ], 200);
//
//        if ($request->hasFile('photo')) {
//            $path = public_path();
//            $destinationPath = $path . '/uploads/items/'; // upload path
//            $photo = $request->file('photo');
//            $extension = $photo->getClientOriginalExtension(); // getting image extension
//            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
//            $photo->move($destinationPath, $name); // uploading file to given path
//            $book->update(['photo' => 'uploads/items/' . $name]);
//        }
//
//        if ($book) {
//            $data = [
////                'data' => [
//                    'status' => 1,
//                    'msg' => 'Done',
//                    'data' => $book->load('author','department')
//
////                ]
//            ];
//            return Response::json($data, 200);
//
//
//        } else {
//            return Response::json([
////                'data' => [
//                    'status' => 0,
//                    'message' => 'حدث خطأ ، حاول مرة أخرى',
////                ]
//            ], 200);
//        }
//    }
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
            elseif ($request->has('student_id')){
                $q->where('student_id',$request->student_id);
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
    //get all books that available to transaction
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
                return $user_book;
                $items = $user_book->pivot->where('availability',1)->get();
                $arrays =(array)$items->toArray();
                for ($i = 0; $i < count($arrays);$i++) {
                    $book =Book::find( $arrays[$i]['book_id']);
                    $student =Student::find( $arrays[$i]['student_id']);
                    $author=Author::find($book->author_id);
                    $department=Department::find($book->department_id);

                    $arrNewSku[$i]['id'] = $arrays[$i]['id'];
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
                    $arrNewSku[$i]['department'] = [$department];
                }
                        return responseJson(1,'Done',$arrNewSku);
        }}


    }
    public function similar_books(Request $request)
    {
//        $item = Book::find($request->book_id);
//        $items = $request->user()->books()->where('book_id',$item->id)->with('author','department')->get();
//        $arrNewSku = array();
//        $arrays =(array)$items->toArray();
//        for ($i = 0; $i < count($arrays);$i++) {
//            $arrNewSku[$i]['id'] = $arrays[$i]['id'];
//            $arrNewSku[$i]['created_at'] = $arrays[$i]['created_at'];
//            $arrNewSku[$i]['updated_at'] = $arrays[$i]['updated_at'];
//            $arrNewSku[$i]['title'] = $arrays[$i]['title'];
//            $arrNewSku[$i]['desc'] = $arrays[$i]['desc'];
//            $arrNewSku[$i]['publish_year'] = $arrays[$i]['publish_year'];
//            $arrNewSku[$i]['author_id'] =$arrays[$i]['author_id'];
//            $arrNewSku[$i]['department_id'] = $arrays[$i]['department_id'];
//            $arrNewSku[$i]['photo'] = $arrays[$i]['photo'];
//            $arrNewSku[$i]['ISBN_num'] = $arrays[$i]['ISBN_num'];
//            $arrNewSku[$i]['pivot'] = [$arrays[$i]['pivot']];
//            $arrNewSku[$i]['author'] = [$arrays[$i]['author']];
//
//        }
//        return responseJson(1,'Done',$arrNewSku);
//        return responseJson(1,'',$items);
//        -----------------------------


        $books = Book::where(function($q) use($request){
            if ($request->has('book_name')){
                $q->where('title','LIKE','%'.$request->book_name.'%');
            }

        })->get();
        //  return $books;

        foreach ($books as $u) {
            foreach ($u->students as $user_book) {
                $user = Student::find($user_book->student_id);
                return responseJson(1,'user',$user);
                foreach ($user->books as $role) {
                    // return responseJson(1,'Done',$role);
                }

//                 $items = $user_book->pivot->where('availability',1)->get();
//                 $arrays =(array)$items->toArray();
//                 for ($i = 0; $i < count($arrays);$i++) {
//                     $book =Book::find( $arrays[$i]['book_id']);

//                     $student =Student::find( $arrays[$i]['student_id']);
//                     $author=Author::find($book->author_id);
//                     $department=Department::find($book->department_id);
// $arrNewSku[$i]['id'] = $arrays[$i]['id'];
//                     $arrNewSku[$i]['student_id'] = $arrays[$i]['student_id'];
//                     $arrNewSku[$i]['created_at'] = $arrays[$i]['created_at'];
//                     $arrNewSku[$i]['updated_at'] = $arrays[$i]['updated_at'];
//                     $arrNewSku[$i]['book_id'] = $arrays[$i]['book_id'];
//                     $arrNewSku[$i]['price'] = $arrays[$i]['price'];
//                     $arrNewSku[$i]['availability'] = $arrays[$i]['availability'];
//                     $arrNewSku[$i]['book_status'] = $arrays[$i]['book_status'];
//                     $arrNewSku[$i]['transaction_types_id'] = $arrays[$i]['transaction_types_id'];
//                     $arrNewSku[$i]['pivot'] = [$book];
//                     $arrNewSku[$i]['student'] = [$student];
//                     $arrNewSku[$i]['author'] = [$author];
// $arrNewSku[$i]['department'] = [$department];
//                 }
                // return responseJson(1,'Done',$arrNewSku);
            }}

        //  return responseJson(1,'Done',$books);
        //   $item = Book::find($request->book_id);
        // $items = $request->user()->books()->where('book_id',$item->id)->with('author','department')->get();
        $arrNewSku = array();
        $sim=Book_Student::where('book_id',112)->get();
        // return $sim;
        foreach ($books as $value){

            $arrays =(array)$sim->toArray();
            for ($i = 0; $i < count($arrays);$i++) {

                $arrNewSku[$i]['id'] = $arrays[$i]['id'];
                $arrNewSku[$i]['created_at'] = $arrays[$i]['created_at'];
                $arrNewSku[$i]['updated_at'] = $arrays[$i]['updated_at'];


            }

        }

//         for ($i = 0; $i < count($arrays);$i++) {
//           $items = $arrNewSku[$i]['id']->students()->get();
// $arrNewSku[$i]['items'] = $items;
//         }



        // for ($i = 0; $i < count($arrays);$i++) {
        //     $arrNewSku[$i]['id'] = $arrays[$i]['id'];
        //     $arrNewSku[$i]['created_at'] = $arrays[$i]['created_at'];
        //     $arrNewSku[$i]['updated_at'] = $arrays[$i]['updated_at'];
        //     $arrNewSku[$i]['title'] = $arrays[$i]['title'];
        //     $arrNewSku[$i]['desc'] = $arrays[$i]['desc'];
        //     $arrNewSku[$i]['publish_year'] = $arrays[$i]['publish_year'];
        //     $arrNewSku[$i]['author_id'] =$arrays[$i]['author_id'];
        //     $arrNewSku[$i]['department_id'] = $arrays[$i]['department_id'];
        //     $arrNewSku[$i]['photo'] = $arrays[$i]['photo'];
        //     $arrNewSku[$i]['ISBN_num'] = $arrays[$i]['ISBN_num'];
        //     $arrNewSku[$i]['pivot'] = [$arrays[$i]['pivot']];
        //     $arrNewSku[$i]['author'] = [$arrays[$i]['author']];

        // }
        return responseJson(1,'Done',$arrNewSku);

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
//    public function update_buyer_atatus(Request $request)
//    {
//        $validation = Validator::make($request->all(), [
//            'bill_id' => 'required',
//        ]);
//
//        if ($validation->fails()) {
//            return Response()->json([
//                'status' => 0,
//                'msg' => 'please fill all fields',
//                'errors' => $validation->errors()
//            ], 200);
//        }
//        $bill= Bill::find($request->bill_id);
//
//        $bill->update(['buyer_status' => 1]);
//
//        return responseJson(1,'Done');
//    }
    public function reports(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'text' => 'required',

        ]);

        if ($validation->fails()) {
            return Response()->json([
                'status' => 0,
                'msg' => 'برجاء ملئ جميع الحقول',
                'errors' => $validation->errors()
            ], 200);
        }
        $report=$request->user()->report()->create($request->all());
        if ($report) {
            $data = [
                'status' => 1,
                'msg' => 'Done',
                'data' => $report->load('student')
            ];
            return Response::json($data, 200);

        } else {
            return Response::json([
                'status' => 0,
                'message' => 'حدث خطأ ، حاول مرة أخرى',
            ], 200);
        }
    }
    }
