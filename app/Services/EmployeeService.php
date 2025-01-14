<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Models\Employee;
use App\Models\Address;
use App\Models\User;

use App\Http\Requests\EmployeeStore;

class EmployeeService
{

    public function store(EmployeeStore $data)
    {
        try {

            $return = DB::transaction(function () use ($data) {
                $user = Auth::user();
                $company = $user->company()->first();

                $companyAddress = Address::where([
                    ['company_id', '=', $company->id],
                    ['client_id', 'IS', NULL],
                    ['deliveryman_id', 'IS', NULL],
                    ['employee_id', 'IS', NULL],
                ]);

                if ($companyAddress->count() > 0) {
                    $city_id = $companyAddress->first()->city_id;
                } else {
                    $city_id = 1;
                }

                $user = new User();
                $user->name = $data->name;
                $user->email = $data->email;
                $user->type = $data->type;
                $user->password = $data->password;
                $user->save();

                $employee = new Employee();
                $employee->name = $data->name;

                $slug = Str::slug($data->name);
                // Verificando se ja existe empresa com o slug
                $slugExist = $company->employees()->where([
                    ['slug', 'like', '%' . $slug . '%']
                ])->get();
                $qtdSlugs = count($slugExist);

                if ($qtdSlugs >= 1) {
                    $slug .= "-" . $qtdSlugs;
                }
                $employee->slug = $slug;

                $employee->cpf = $data->cpf;
                $employee->birth = date('Y-m-d', strtotime($data->birth));
                $employee->phone = $data->phone;
                $employee->email = $data->email;

                $employee->user_id = $user->id;
                $employee->company_id = $company->id;
                $employee->save();

                if (!is_null($data->street)) {
                    $address = new Address();
                    $address->street = $data->street;
                    $address->number = $data->number;
                    $address->neighborhood = $data->neighborhood;
                    $address->reference = $data->reference;
                    $address->main = 1;
                    $address->city_id = $city_id;
                    $address->company_id = $company->id;
                    $address->employee_id = $employee->id;

                    $address->save();
                }

                return [
                    'success' => true,
                    'message' => 'Funcionário cadastrado com sucesso.',
                    'data' => $employee
                ];
            });

            return $return;
        } catch (\Exception $e) {
            dd([
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage()
            ]);
            return [
                'success' => false,
                'message' => 'Erro ao cadastrar funcionário.',
                'data' => []
            ];
        }
    } // store()

    public function update(EmployeeStore $data, Employee $employee)
    {

        try {

            $return = DB::transaction(function () use ($data, $employee) {
                $user = Auth::user();
                $company = $user->company()->first();

                $companyAddress = Address::where([
                    ['company_id', '=', $company->id],
                    ['client_id', 'IS', NULL],
                    ['deliveryman_id', 'IS', NULL],
                    ['employee_id', 'IS', NULL],
                ]);

                if ($companyAddress->count() > 0) {
                    $city_id = $companyAddress->first()->city_id;
                } else {
                    $city_id = 1;
                }

                $user = $employee->user()->first();

                $user->name = $data->name;
                $user->email = $data->email;
                $user->type = $data->type;

                if ($data->password) {
                    $user->password = $data->password;
                }

                $user->save();

                $employee->name = $data->name;

                $slug = Str::slug($data->name);

                if ($employee->slug != $slug) {
                    // Verificando se ja existe empresa com o slug
                    $slugExist = $company->employees()->where([
                        ['slug', 'like', '%' . $slug . '%']
                    ])->get();
                    $qtdSlugs = count($slugExist);

                    if ($qtdSlugs >= 1) {
                        $slug .= "-" . $qtdSlugs;
                    }
                    $employee->slug = $slug;
                }

                $employee->cpf = $data->cpf;
                $employee->birth = date('Y-m-d', strtotime($data->birth));
                $employee->phone = $data->phone;
                $employee->email = $data->email;

                $employee->company_id = $company->id;
                $employee->save();

                // Se já tem endereço salvo
                if ($employee->address()->count() > 0) {
                    $address = $employee->address()->first();

                    // E informou rua, atualiza
                    if (!is_null($data->street)) {
                        $address->street = $data->street;
                        $address->number = $data->number;
                        $address->neighborhood = $data->neighborhood;
                        $address->reference = $data->reference;
                        $address->main = 1;
                        $address->city_id = $city_id;
                        $address->company_id = $company->id;
                        $address->employee_id = $employee->id;

                        $address->save();
                    } else {
                        // Não informou rua, apague o que existe
                        $employee->address()->delete();
                    }
                } else {
                    // Se não tem endereço
                    // E informou rua, cadastra
                    if (!is_null($data->street)) {
                        $address = new Address();
                        $address->street = $data->street;
                        $address->number = $data->number;
                        $address->neighborhood = $data->neighborhood;
                        $address->reference = $data->reference;
                        $address->main = 1;
                        $address->city_id = $city_id;
                        $address->company_id = $company->id;
                        $address->employee_id = $employee->id;

                        $address->save();
                    }
                }

                $employee->save();


                return [
                    'success' => true,
                    'message' => 'Funcionário editado com sucesso.',
                    'data' => $employee
                ];
            });

            return $return;
        } catch (\Exception $e) {

            dd([
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Erro ao editar funcionário.',
                'data' => []
            ];
        }
    } // update()

    public function destroy(Employee $employee)
    {

        try {

            $return = DB::transaction(function () use ($employee) {



                if ($employee->address()->count() > 0) {
                    $employee->address()->delete();
                }

                $user = $employee->user();

                $employee->delete();

                if ($user->count() > 0) {
                    $user->delete();
                }

                return [
                    'success' => true,
                    'message' => 'Funcionário deletado com sucesso.',
                    'data' => []
                ];
            });

            return $return;
        } catch (\Exception $e) {

            $error = [
                'File: ' => $e->getFile(),
                'Line: ' => $e->getLine(),
                'Message: ' => $e->getMessage()
            ];
            dd($error);

            return [
                'success' => false,
                'message' => 'Erro ao tentar apagar funcionário.'
            ];
        }
    } // destroy()

}
