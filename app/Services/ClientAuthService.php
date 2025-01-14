<?php

namespace App\Services;

use App\Services\GooglePlacesService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
    
use App\Models\Lead;
use App\Models\City;
use App\Http\Requests\LeadStore;
use App\Http\Requests\LeadEdit;

class ClientAuthService{


     public function store(Request $data){

        try{

            


            return [
                'success' => true,
                'message' => 'Usuário logado com sucesso',
                'data' => $data
            ];

        } catch (\Exception $e) {

            return [
                'success' => false,
                'message' => 'Erro ao fazer login!',
                'data' => []
            ]; 
        }

    } // store()

}