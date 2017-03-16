<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Request;

use App\Book;

class BookController extends Controller
{
     public function create()
    {
    	
    	return view('/books/addbook');

    }

    public function store()

    { 	
    	

    	// Create a new book using the request data
    	//Save it to the database
        //dont need to save the token generated by csfr
        $input =Request::except('_token');
        //extract only the values that are attributes in the books table
        $inputOnly =Request::intersect(['title','codeNum','authorLastName','authorFirstName','illustratorFirstName','illustratorLastName','translatorFirstName','translatorLastName','publisher','copyright','isbn','createdBy']);

        // removes all keys with values of null!
        $rm_null = array_filter( $input, 'strlen' );
        
        //need to stringify array
        $arr_tojson = json_encode($rm_null);

        $arr2_tojson = json_encode($input);

        //insert the json fields into the dtabase and return the Id of that insertion

        $id = DB::table('book')->insertGetId(['bookAttr'=>$arr_tojson,'fields'=>$arr_tojson]);
        //use that id to update the other fields corresponding to the same row.
        DB::table('book')
            ->where('bid', $id)
            ->update($inputOnly);




        return view('/books/addbook');

    }
    


   
    public function edit()
    {
        return view('/books/edit');
    }

    // this function updates books in the database
    public function update() {


         DB::table('book')->where('bid', request('bid'))->update([
             'title' => request('title'),
             'codeNum' => request('codeNum'),
             'authorLastName' => request('authorLastName'),
             'authorFirstName' => request('authorFirstName'),
             'illustratorFirstName' => request('illustratorFirstName'),
             'illustratorLastName' => request('illustratorLastName'),
             'translatorFirstName' => request('translatorFirstName'),
             'translatorLastName' => request('translatorLastName'),
             'publisher'=> request('publisher'),
             'copyright'=> request('copyright'),
             'isbn'=> request('isbn'),
             'createdBy'=> request('createdBy')
         ]);


       return view('/books/edit');

    }
    /*This works it just looks weird with all the books because it uses route model binding.*/
    public function destroy(Book $Book){
            Book::destroy($Book);
    }


    public function show($id){
        $book = DB::table('book')->select('bookAttr')->where('bid', '=', $id)->get();

        return view('/home', compact($book));
    }


}

