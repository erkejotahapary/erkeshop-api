<?php

namespace App\Http\Controllers\Admin;

use App\Book;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\Helpers\Helper;
use Yajra\Datatables\Datatables;
use App\Http\Requests\BookRequest;
use App\Http\Controllers\Controller;
use App\Traits\Response\ResponseJson;


class BookController extends Controller
{
    use Helper, ResponseJson;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.book.index');
    }

    public function grid()
    {
        $query = Book::latest();

        return Datatables::of($query)
            ->addIndexColumn()
            ->editColumn('cover', function($query) {
                return '<img src="'.asset('/images/books/'.$query->cover).'" alt="Cover '.$query->title.'" width="60" height="60" style="border-radius: 0.5rem; margin-right: 0.5rem; object-fit:cover;">';
            })
            ->editColumn('price', fn($query) => 'Rp. '.$this->formatMoney($query->price))
            ->addColumn('action', fn($query) => $this->getActionButton($query))
            ->rawColumns(['action', 'title' ,'code', 'cover'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookRequest $request)
    {
        $validRequest = $request->validated(); 

        $book = Book::create(Arr::collapse([
                $validRequest, [ 'slug' => Str::slug($request->title) ]]
        ));
        
        if($request->has('cover') && $request->file('cover') != null)
            $this->uploadBookPhoto($request, $book);

        return $this->sendResponseSuccess(__('response.success'));
    }

    protected function uploadBookPhoto($request, $book)
    {
        $file = $request->file('cover');
        $fileOriginalName = $file->getClientOriginalName();

        $file->storeAs('books', $fileOriginalName, 'public');
        $book->update(['cover' => $fileOriginalName]);

        return true;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Show the data for editing the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $book)
    {
        return response()->json($book);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(BookRequest $request, Book $book)
    {
        $validRequest = $request->validated(); 

        $book->update($validRequest);

        if($request->has('cover') && $request->file('cover') != null)
            $this->uploadBookPhoto($request, $book);

        return $this->sendResponseSuccess(__('response.success-update'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return $this->sendResponseSuccess(__('response.success-delete'));
    }
}
