<?php

namespace App\Traits\Helpers;

/**
 * 
 */
trait Helper
{

    /**
    * Display an action button
    * @param query
    * @return string button
    */
    public function getActionButton($query)
    {
        return '
            <button class="btn btn-sm btn-warning text-white" onclick="editData('.$query->id.')" data-toggle="tooltip" data-placement="bottom" title="Edit">
                <i class="fa fa-edit"></i>
            </button>
            <button class="btn btn-sm btn-danger" onclick="deleteData('.$query->id.')" data-toggle="tooltip" data-placement="bottom" title="Hapus">
                <i class="fa fa-trash"></i>
            </button>
        ';
    }

    /**
     * Display view button
     * @param query
     * @return string view data button
     */
    public function getActionView($query)
    {
        return '
            <button class="btn btn-sm btn-blue" onclick="viewData('.$query->id.')" data-toggle="tooltip" data-placement="bottom" title="Detail">
                <i class="fa fa-eye"></i>
            </button>
        ';
    }

    public function formatMoney($str)
    {
        return number_format($str, 0, ',', '.');
    }

    public function moneyReplace($str)
    {
        return str_replace('.', '', $str);
    }
}
