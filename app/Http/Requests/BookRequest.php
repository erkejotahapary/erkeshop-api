<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'       => 'required',
            'publisher'   => 'required',
            'author'      => 'required|max:100',
            'stock'       => 'required|integer|min:1',
            'price'       => 'required',
            'description' => 'nullable'
        ];
    }

    public function messages()
    {
        return [
            'title.required'     => 'Harap isi judul buku',
            'publisher.required' => 'Harap pilih penerbit buku',
            'author.required'    => 'Harap isi pengarang buku',
            'author.max'         => 'Jumlah maksimal karakter adalah :value',
            'stock.required'     => 'Harap isi stok buku',
        ];
    }
}
