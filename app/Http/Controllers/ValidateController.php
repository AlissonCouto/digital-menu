<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;

class ValidateController extends Controller
{
    public function clientPhoneUnique(Request $request)
    {
        $unique = true;
        $user = Auth::guard('client')->check() == true ? Auth::guard('client')->user() : NULL;

        // Regras para usuários logados
        if ($user) {
            if ($user->phone != $request) {
                // O cliente não pode existir no banco
                $client = Client::where([
                    ['phone', '=', $request->phone]
                ]);

                if ($client->count() > 0) {
                    $client = $client->first();
                    $unique = false;
                }
            }
        } else {
            // Regras para usuário deslogado
            // O cliente não pode existir no banco
            $client = Client::where([
                ['phone', '=', $request->phone]
            ]);

            if ($client) {
                $client = $client[0];
                $unique = true;
            }
        }

        echo json_encode([
            'success' => $unique
        ]);
        die;
    } // clientPhoneUnique()
}
