<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

use App\Models\Coupon;

use App\Services\CouponService;

use App\Http\Requests\CouponStore;
use App\Http\Requests\CouponUpdate;

class CouponController extends Controller
{

    private $service;

    public function __construct(CouponService $couponService)
    {
        $this->service = $couponService;
    }

    /**
     * Display a listing of the resource through query.
     */
    public function search(Request $request)
    {

        $search = $request->search;
        $search = $request->search ? $request->search : '';
        //dd($request->query());
        $user = Auth::user();
        $company = $user->company()->first();

        /* Filtros */
        $category_id = $request->category_id ?? null;

        $where[] = ['name', 'like', '%' . $search . '%'];

        if ($category_id) {
            $where[] = ['category_id', '=', $category_id];
        }

        /* Filtros */

        $perPage = 10; // Número de itens por página
        $page = $request->get('page', 1); // Pega o número da página da requisição, padrão é 1

        $totalQuery = $company->coupons()->where($where)
            ->count();

        $entity = $company->coupons()->where($where)->orderby('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        $html = view('admin.coupons.body-table')->with('entity', $entity)->render();

        echo json_encode([
            'total' => $totalQuery,
            'perPage' => $perPage,
            'currentPage' => $entity->currentPage(),
            'lastPage' => $entity->lastPage(),
            'from' => $entity->firstItem(),
            'to' => $entity->lastItem(),
            'html' => $html
        ]);
        die;
    } // search()

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $company = $user->company()->first();

        $perPage = 10; // Número de itens por página
        $page = $request->get('page', 1); // Pega o número da página da requisição, padrão é 1

        $totalQuery = $company->coupons()->get()
            ->count();

        $entity = $company->coupons()->orderby('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return view('admin.coupons.index')->with([
            'total' => $totalQuery,
            'entity' => $entity,
            'perPage' => $perPage,
            'currentPage' => $entity->currentPage(),
            'lastPage' => $entity->lastPage(),
            'from' => $entity->firstItem(),
            'to' => $entity->lastItem(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.coupons.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CouponStore $request)
    {
        $return = $this->service->store($request);
        return Redirect::route('coupons.index')->with('success', $return);
    }

    /**
     * Display the specified resource.
     */
    public function show(Coupon $coupon)
    {
        $user = Auth::user();

        return view('admin.coupons.show')->with(compact('coupon'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Coupon $coupon)
    {
        $user = Auth::user();
        $company = $user->company()->first();

        return view('admin.coupons.edit')->with(compact('coupon'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CouponUpdate $request, Coupon $coupon)
    {
        $return = $this->service->update($request, $coupon);
        return Redirect::route('coupons.index')->with('success', $return);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coupon $coupon)
    {

        if ($coupon) {

            $return = $this->service->destroy($coupon);
            return Redirect::route('coupons.index')->with('success', $return);
        }

        $session = ['status' => false, 'message' => 'O cupom informado não existe.'];
        return Redirect::route('coupons.index')->with('success', $session);
    }
}
