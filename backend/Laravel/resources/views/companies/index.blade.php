@extends('layouts.app')

@section('title', 'Companies')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-building me-2"></i>Companies</h4>
            <a href="{{ route('companies.create') }}" class="btn btn-light">
                <i class="fas fa-plus me-1"></i>Add Company
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="companiesTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Location</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Employees</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($companies as $company)
                            <tr>
                                <td>{{ $company->id }}</td>
                                <td><strong>{{ $company->name }}</strong></td>
                                <td>{{ $company->location ?? '-' }}</td>
                                <td>{{ $company->email ?? '-' }}</td>
                                <td>{{ $company->phone ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $company->employees_count }}</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('companies.edit', $company) }}" class="btn btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('companies.destroy', $company) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this company?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-building fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No companies found. <a href="{{ route('companies.create') }}">Add
                                            one!</a></p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#companiesTable').DataTable({
                order: [[0, 'desc']],
                pageLength: 10,
                language: {
                    search: '<i class="fas fa-search"></i>',
                    searchPlaceholder: 'Search companies...'
                }
            });
        });
    </script>
@endpush