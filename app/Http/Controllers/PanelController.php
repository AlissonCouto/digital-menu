<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Company;
use App\Models\Order;

class PanelController extends Controller
{
    public function dashboard()
    {

        $user = Auth::user();
        $company = $user->company()->first();

        //  enum('realized','inanalysis','inproduction','delivery')
        $inanalysis = Order::where([
            ['status', '=', 'inanalysis'],
            ['company_id', '=', $company->id]
        ])->get();

        $inproduction = Order::where([
            ['status', '=', 'inproduction'],
            ['company_id', '=', $company->id]
        ])->get();

        $delivery = Order::where([
            ['status', '=', 'ready'],
            ['company_id', '=', $company->id]
        ])->get();

        return view('admin.dashboard')->with(compact('inanalysis', 'inproduction', 'delivery'));
    } // dashboard()

    public function graphics()
    {
        return view('admin.graphics.index');
    } // graphics()

}
