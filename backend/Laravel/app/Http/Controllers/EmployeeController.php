<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the employees.
     */
    public function index()
    {
        $companies = Company::all();
        return view('employees.index', compact('companies'));
    }

    /**
     * Get employees data for DataTables.
     */
    public function getData(Request $request)
    {
        $query = Employee::with(['company', 'manager'])
            ->select('employees.*');

        // Search
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('company', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by company
        if ($companyId = $request->input('company_id')) {
            $query->where('company_id', $companyId);
        }

        $totalRecords = Employee::count();
        $filteredRecords = $query->count();

        // Ordering
        $orderColumn = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'asc');
        $columns = ['id', 'first_name', 'last_name', 'email', 'company_id', 'manager_id'];

        if (isset($columns[$orderColumn])) {
            $query->orderBy($columns[$orderColumn], $orderDir);
        }

        // Pagination
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $employees = $query->skip($start)->take($length)->get();

        $data = $employees->map(function ($employee) {
            return [
                'id' => $employee->id,
                'first_name' => $employee->first_name,
                'last_name' => $employee->last_name,
                'full_name' => $employee->full_name,
                'email' => $employee->email,
                'phone' => $employee->phone,
                'company' => $employee->company->name ?? '-',
                'manager' => $employee->manager ? $employee->manager->full_name : '-',
                'location' => implode(', ', array_filter([$employee->city, $employee->state, $employee->country])),
                'hire_date' => $employee->hire_date?->format('Y-m-d'),
                'actions' => view('employees.partials.actions', compact('employee'))->render(),
            ];
        });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ]);
    }

    /**
     * Show the form for creating a new employee.
     */
    public function create()
    {
        $companies = Company::all();
        $managers = Employee::all();
        return view('employees.create', compact('companies', 'managers'));
    }

    /**
     * Store a newly created employee in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email|max:255',
            'phone' => 'nullable|string|max:50',
            'company_id' => 'required|exists:companies,id',
            'manager_id' => 'nullable|exists:employees,id',
            'country' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'hire_date' => 'nullable|date',
        ]);

        Employee::create($validated);

        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully.');
    }

    /**
     * Show the form for editing the specified employee.
     */
    public function edit(Employee $employee)
    {
        $companies = Company::all();
        $managers = Employee::where('id', '!=', $employee->id)->get();
        return view('employees.edit', compact('employee', 'companies', 'managers'));
    }

    /**
     * Update the specified employee in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:employees,email,' . $employee->id,
            'phone' => 'nullable|string|max:50',
            'company_id' => 'required|exists:companies,id',
            'manager_id' => 'nullable|exists:employees,id',
            'country' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'hire_date' => 'nullable|date',
        ]);

        $employee->update($validated);

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    /**
     * Remove the specified employee from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
}
