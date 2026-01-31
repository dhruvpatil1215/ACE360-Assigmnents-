@extends('layouts.app')

@section('title', 'Edit Employee')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="fas fa-user-edit me-2"></i>Edit Employee</h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('employees.update', $employee) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                   id="first_name" name="first_name" 
                                   value="{{ old('first_name', $employee->first_name) }}" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                   id="last_name" name="last_name" 
                                   value="{{ old('last_name', $employee->last_name) }}" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" 
                                   value="{{ old('email', $employee->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" 
                                   value="{{ old('phone', $employee->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="company_id" class="form-label">Company <span class="text-danger">*</span></label>
                            <select class="form-select @error('company_id') is-invalid @enderror" 
                                    id="company_id" name="company_id" required>
                                <option value="">Select Company</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" 
                                        {{ old('company_id', $employee->company_id) == $company->id ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('company_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="manager_id" class="form-label">Manager</label>
                            <select class="form-select @error('manager_id') is-invalid @enderror" 
                                    id="manager_id" name="manager_id">
                                <option value="">No Manager</option>
                                @foreach($managers as $manager)
                                    <option value="{{ $manager->id }}" 
                                        {{ old('manager_id', $employee->manager_id) == $manager->id ? 'selected' : '' }}>
                                        {{ $manager->full_name }} ({{ $manager->company->name ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('manager_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">
                    <h5 class="mb-3"><i class="fas fa-map-marker-alt me-2"></i>Location</h5>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="country" class="form-label">Country</label>
                            <select class="form-select @error('country') is-invalid @enderror" 
                                    id="country" name="country">
                                <option value="">Select Country</option>
                                @if($employee->country)
                                    <option value="{{ $employee->country }}" selected>{{ $employee->country }}</option>
                                @endif
                            </select>
                            @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="state" class="form-label">State</label>
                            <select class="form-select @error('state') is-invalid @enderror" 
                                    id="state" name="state">
                                <option value="">Select State</option>
                                @if($employee->state)
                                    <option value="{{ $employee->state }}" selected>{{ $employee->state }}</option>
                                @endif
                            </select>
                            @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="city" class="form-label">City</label>
                            <select class="form-select @error('city') is-invalid @enderror" 
                                    id="city" name="city">
                                <option value="">Select City</option>
                                @if($employee->city)
                                    <option value="{{ $employee->city }}" selected>{{ $employee->city }}</option>
                                @endif
                            </select>
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="2">{{ old('address', $employee->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="hire_date" class="form-label">Hire Date</label>
                        <input type="date" class="form-control @error('hire_date') is-invalid @enderror" 
                               id="hire_date" name="hire_date" 
                               value="{{ old('hire_date', $employee->hire_date?->format('Y-m-d')) }}">
                        @error('hire_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Update Employee
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var savedCountry = '{{ $employee->country }}';
    var savedState = '{{ $employee->state }}';
    var savedCity = '{{ $employee->city }}';

    // Load countries on page load
    loadCountries();

    // Country change handler
    $('#country').on('change', function() {
        var country = $(this).val();
        $('#state').html('<option value="">Loading...</option>');
        $('#city').html('<option value="">Select City</option>');
        
        if (country) {
            loadStates(country);
        }
    });

    // State change handler
    $('#state').on('change', function() {
        var state = $(this).val();
        $('#city').html('<option value="">Loading...</option>');
        
        if (state) {
            loadCities(state);
        }
    });

    function loadCountries() {
        $.ajax({
            url: '{{ route("api.countries") }}',
            type: 'GET',
            success: function(data) {
                var options = '<option value="">Select Country</option>';
                $.each(data, function(key, value) {
                    var selected = value.country_name === savedCountry ? 'selected' : '';
                    options += '<option value="' + value.country_name + '" ' + selected + '>' + value.country_name + '</option>';
                });
                $('#country').html(options);
                
                // Load states if country was pre-selected
                if (savedCountry) {
                    loadStates(savedCountry);
                }
            },
            error: function() {
                // Keep the existing value if API fails
                if (savedCountry) {
                    $('#country').html('<option value="' + savedCountry + '" selected>' + savedCountry + '</option>');
                }
            }
        });
    }

    function loadStates(country) {
        $.ajax({
            url: '/api/states/' + encodeURIComponent(country),
            type: 'GET',
            success: function(data) {
                var options = '<option value="">Select State</option>';
                $.each(data, function(key, value) {
                    var selected = value.state_name === savedState ? 'selected' : '';
                    options += '<option value="' + value.state_name + '" ' + selected + '>' + value.state_name + '</option>';
                });
                $('#state').html(options);
                
                // Load cities if state was pre-selected
                if (savedState) {
                    loadCities(savedState);
                }
            },
            error: function() {
                if (savedState) {
                    $('#state').html('<option value="' + savedState + '" selected>' + savedState + '</option>');
                }
            }
        });
    }

    function loadCities(state) {
        var country = $('#country').val() || savedCountry;
        $.ajax({
            url: '/api/cities/' + encodeURIComponent(state) + '?country=' + encodeURIComponent(country),
            type: 'GET',
            success: function(data) {
                var options = '<option value="">Select City</option>';
                $.each(data, function(key, value) {
                    var selected = value.city_name === savedCity ? 'selected' : '';
                    options += '<option value="' + value.city_name + '" ' + selected + '>' + value.city_name + '</option>';
                });
                $('#city').html(options);
            },
            error: function() {
                if (savedCity) {
                    $('#city').html('<option value="' + savedCity + '" selected>' + savedCity + '</option>');
                }
            }
        });
    }
});
</script>
@endpush
