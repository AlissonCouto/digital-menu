<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

use App\Models\Employee;

use App\Services\EmployeeService;

use App\Http\Requests\EmployeeStore;

class EmployeeController extends Controller
{

    private $service;

    public function __construct(EmployeeService $employeeService)
    {
        $this->service = $employeeService;
    }

    /**
     * Display a listing of the resource through query.
     */
    public function search(Request $request)
    {

        $search = $request->search;
        $search = $request->search ? $request->search : '';

        $user = Auth::user();
        $company = $user->company()->first();


        $where[] = ['name', 'like', '%' . $search . '%'];

        $perPage = 10; // Número de itens por página
        $page = $request->get('page', 1); // Pega o número da página da requisição, padrão é 1

        $totalQuery = $company->employees()->with('address')->where($where)
            ->count();

        $entity = $company->employees()->with('address')->where($where)->orderby('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        $html = view('admin.employees.body-table')->with('entity', $entity)->render();

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

        $totalQuery = $company->employees()->get()
            ->count();

        $entity = $company->employees()->orderby('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return view('admin.employees.index')->with([
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
        return view('admin.employees.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmployeeStore $request)
    {
        $return = $this->service->store($request);
        return Redirect::route('employees.index')->with('success', $return);
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        if ($employee) {
            return view('admin.employees.show')->with(compact('employee'));
        }

        $session = ['status' => false, 'message' => 'O funcionário informado não existe.'];
        return Redirect::route('employees.index')->with('success', $session);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Employee $employee)
    {
        if ($employee) {

            $address = $employee->address()->first();

            return view('admin.employees.edit')->with(compact('employee', 'address'));
        }

        $session = ['status' => false, 'message' => 'O funcionário informado não existe.'];
        return Redirect::route('employees.index')->with('success', $session);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeStore $request, Employee $employee)
    {

        if ($employee) {
            $return = $this->service->update($request, $employee);
            return Redirect::route('employees.index')->with('success', $return);
        }

        $session = ['status' => false, 'message' => 'O funcionário informado não existe.'];
        return Redirect::route('employees.index')->with('success', $session);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {

        if ($employee) {

            $return = $this->service->destroy($employee);
            return Redirect::route('employees.index')->with('success', $return);
        }

        $session = ['status' => false, 'message' => 'O funcionário informado não existe.'];
        return Redirect::route('employees.index')->with('success', $session);
    }
}
