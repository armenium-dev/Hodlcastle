<?php

namespace App\Http\Controllers;

use App\Models\ShortLink;

class ShortLinkController extends Controller
{

    /**
     * Display a listing of the resource.
     * @param $code
     * @return \Illuminate\Http\RedirectResponse
     */
    public function shortenLink($code)
    {
        $find = ShortLink::where('code', $code)->first();

        if (empty($find)){
            abort(404);
        }

        return redirect()->to($find->link);
    }
}
