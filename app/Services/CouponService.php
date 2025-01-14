<?php

namespace App\Services;

use App\Http\Requests\CouponStore;
use App\Http\Requests\CouponUpdate;

use Illuminate\Support\Facades\Auth;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use App\Models\Coupon;
use Illuminate\Support\Str;

class CouponService
{

    public function store(CouponStore $data)
    {

        try {
            $return = DB::transaction(function () use ($data) {

                $user = Auth::user();
                $company = $user->company()->first();

                $coupon = new Coupon();

                $coupon->name = $data->name;
                $coupon->description = $data->description;
                $coupon->validity_type = $data->validity_type;
                $coupon->discount_type = $data->discount_type;
                $coupon->value = str_replace(['.', ','], ['', '.'], $data->value);

                if ($coupon->validity_type == 'deadline') {
                    $coupon->usage_limit = null;
                    $coupon->expiration_date = $data->expiration_date;
                    $coupon->expiry_time = $data->expiry_time;
                } else {
                    $coupon->usage_limit = $data->usage_limit;
                    $coupon->expiration_date = null;
                    $coupon->expiry_time = null;
                }

                $slug = Str::slug($data->name);

                // Verificando se ja existe Coupono com o slug
                $slugExist = $coupon->where('slug', $slug)->get();
                $qtdSlugs = count($slugExist);
                if ($qtdSlugs >= 1) {
                    $slug .= "-" . $qtdSlugs;
                }
                $coupon->slug = $slug;
                $coupon->company_id = $company->id;
                $coupon->save();


                return $coupon;
            });

            return [
                'success' => true,
                'message' => 'Cupom cadastrado com sucesso',
                'data' => $return
            ];
        } catch (\Exception $e) {

            switch (get_class($e)) {

                case QueryException::class:
                    return ['success' => false, 'message' => $e->getMessage()];
                case \ErrorException::class:
                    return ['success' => false, 'message' => ['file' => $e->getFile(), 'line' => $e->getLine(), 'message' => $e->getMessage()]];
                default:
                    return ['success' => false, 'message' => get_class($e)];
            }
        }
    } // store()

    public function update(CouponUpdate $data, Coupon $coupon)
    {
        $updated = [];

        if ($coupon) {

            $coupon->name = $data->name;
            $coupon->description = $data->description;
            $coupon->validity_type = $data->validity_type;
            $coupon->discount_type = $data->discount_type;
            $coupon->active = is_null($data->active) || $data->active == 0 ? false : true;
            $coupon->value = str_replace(['.', ','], ['', '.'], $data->value);

            if ($coupon->validity_type == 'deadline') {
                $coupon->usage_limit = null;
                $coupon->expiration_date = $data->expiration_date;
                $coupon->expiry_time = $data->expiry_time;
            } else {
                $coupon->usage_limit = $data->usage_limit;
                $coupon->expiration_date = null;
                $coupon->expiry_time = null;
            }

            $slug = Str::slug($data->name);

            if ($coupon->slug != $slug) {
                // Verificando se ja existe Coupono com o slug
                $slugExist = $coupon->where('slug', $slug)->get();
                $qtdSlugs = count($slugExist);
                if ($qtdSlugs >= 1) {
                    $slug .= "-" . $qtdSlugs;
                }

                $coupon->slug = $slug;
            }

            $coupon->save();

            $updated['success'] = true;
            $updated['message'] = 'Cupom editado com sucesso';
            $updated['data'] = $coupon;
        } else {
            $updated['success'] = false;
            $updated['message'] = 'O Cupom informado não existe';
        }

        return $updated;
    } // update()

    public function destroy(Coupon $coupon)
    {
        try {

            $deleted = [];

            if ($coupon) {
                $coupon->delete();

                $deleted['success'] = true;
                $deleted['message'] = 'Cupom deletado com sucesso';
            } else {
                $deleted['success'] = false;
                $deleted['message'] = 'O Cupom informado não existe';
            }

            return $deleted;
        } catch (\Exception $e) {

            switch (get_class($e)) {

                case QueryException::class:
                    return ['success' => false, 'message' => $e->getMessage()];
                case \ErrorException::class:
                    return ['success' => false, 'message' => ['file' => $e->getFile(), 'line' => $e->getLine(), 'message' => $e->getMessage()]];
                default:
                    return ['success' => false, 'message' => get_class($e)];
            }
        }
    } // destroy()

    // Validando o cupom
    public function validateCoupon($param, $total)
    {
        $return = [];
        $value = 0;
        $discount = 0;

        $return = [
            'success' => true,
            'message' => "Copum " . $param . " aplicado"
        ];

        $coupon = Coupon::where([
            ['name', '=', $param]
        ])->first();

        if (!$param) {
            $return = [
                'success' => false,
                'message' => "Cupom inválido"
            ];
        }

        if ($coupon) {
            // Verificando validade por DATA

            if ($coupon->validity_type == 'deadline') {
                $expiration_date = $coupon->expiration_date;
                $expiry_time = $coupon->expiry_time;
                $curdate = date('Y-m-d H:i:s');

                if (strtotime($curdate) > strtotime($expiration_date . " " . $expiry_time)) {
                    $return = [
                        'success' => false,
                        'message' => "Cupom inválido"
                    ];
                }
            } else {
                // Verificando validade por limite de uso
                if ($coupon->aplications >= $coupon->usage_limit) {
                    $return = [
                        'success' => false,
                        'message' => "Cupom inválido"
                    ];
                }
            }

            // Calculando o valor do desconto
            if ($return['success']) {

                $return['discount_type'] = $coupon->discount_type;
                $return['value'] = $coupon->value;

                if ($coupon->discount_type == 'value') {
                    $discount = $coupon->value;
                } else {
                    $discount = $total * ($coupon->value / 100);
                }
            }
        } else {
            $return = [
                'success' => false,
                'message' => "Cupom inválido"
            ];
        }

        $return['discount'] = number_format($discount, 2, '.');

        return $return;
    }
}
