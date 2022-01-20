<?php

namespace App\Http\Controllers;

use App\Book;
use App\Http\Resources\Book as BookResource;
use App\Http\Resources\Books as BookResourceCollection;
use Illuminate\Http\Request;

class BookController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new BookResourceCollection(Book::paginate(6));
    }

    public function top($count)
    {
        $criteria = Book::select('*')
            ->orderBy('views', 'DESC')
            ->limit($count)
            ->get();

        return new BookResourceCollection($criteria);
    }

    public function slug($slug)
    {
        $criteria = Book::where('slug', $slug)->first();
        $criteria->views = $criteria->views + 1;
        $criteria->save();

        return new BookResource($criteria);
    }

    public function search($keyword)
    {
        $criteria = Book::select('*')
            ->where('title', 'LIKE', '%' . $keyword . '%')
            ->orderBy('views', 'DESC')
            ->get();
        return new BookResourceCollection($criteria);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new BookResource(Book::find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
