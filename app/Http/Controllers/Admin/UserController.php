<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\Helpers\Helper;
use App\Traits\Response\ResponseJson;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Yajra\Datatables\Datatables;

class UserController extends Controller
{
    use Helper, ResponseJson;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.user.index');
    }

    public function grid()
    {
        $query = User::latest();

        return Datatables::of($query)
            ->addIndexColumn()
            ->editColumn('address', fn($query) => $query->address ?? '-')
            ->editColumn('avatar', function($query) {
                if(empty($query->avatar))
                    return '<img src="'.asset('assets/images/blank.svg').'" alt="Blank" width="60" height="60" style="border-radius: 0.5rem; margin-right: 0.5rem; object-fit:cover;">';
                else
                    return '<img src="'.$query->getPhoto().'" alt="Cover '.$query->title.'" width="60" height="60" style="border-radius: 0.5rem; margin-right: 0.5rem; object-fit:cover;">';
            })
            ->addColumn('action', fn($query) => $this->getActionButton($query))
            ->rawColumns(['action', 'avatar'])
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
    public function store(Request $request)
    {
        $validRequest = $this->validate($request, $this->rules($request), $this->messages($request));

        $user = User::create(Arr::collapse([
            $validRequest, [ 
                'roles' => User::ROLE_ADMIN,
                'password' => Hash::make($request->input('password'))
            ]]
        ));

        if($request->has('avatar') && $request->file('avatar') != null)
            $this->uploadUserPhoto($request, $user);

        return $this->sendResponseSuccess(__('response.success'));
    }

    protected function rules($request)
    {
        $rulePassword = $request->isMethod('post') ? 'required' : 'sometimes';
        return [
            'name'    => 'required',
            'password'    => $rulePassword,
            'email'   => 'email|unique:users,email,'.$request->id,
            'address' => 'nullable'
        ];
    }

    protected function messages($request)
    {

        return [
            'password.required' => 'Mohon isi password anda',
            'name.required' => 'Mohon isi nama lengkap anda',
            'email.email' => 'Mohon masukan format email yang benar',
            'email.unique' => 'Email sudah terdaftar sebelumnya',
        ];
    }

    protected function uploadUserPhoto($request, $user)
    {
        $file = $request->file('avatar');
        $fileOriginalName = $file->getClientOriginalName();

        $file->storeAs('users', $fileOriginalName, 'public');
        $user->update(['avatar' => $fileOriginalName]);

        return true;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validRequest = $this->validate($request, $this->rules($request), $this->messages($request));

        $user->update($validRequest);

        if($request->has('avatar') && $request->file('avatar') != null)
            $this->uploadUserPhoto($request, $user);

        return $this->sendResponseSuccess(__('response.success-update'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return $this->sendResponseSuccess(__('response.success-delete'));
    }
}
