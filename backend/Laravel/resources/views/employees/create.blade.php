@extends('layouts.app')

@section('title', 'Add Employee')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-user-plus me-2"></i>Add New Employee</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('employees.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                    id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                    id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                    name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                                    name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="company_id" class="form-label">Company <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('company_id') is-invalid @enderror" id="company_id"
                                    name="company_id" required>
                                    <option value="">Select Company</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
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
                                <select class="form-select @error('manager_id') is-invalid @enderror" id="manager_id"
                                    name="manager_id">
                                    <option value="">No Manager</option>
                                    @foreach($managers as $manager)
                                        <option value="{{ $manager->id }}" {{ old('manager_id') == $manager->id ? 'selected' : '' }}>
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
                                <select class="form-select @error('country') is-invalid @enderror" id="country"
                                    name="country">
                                    <option value="">Select Country</option>
                                </select>
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="state" class="form-label">State</label>
                                <select class="form-select @error('state') is-invalid @enderror" id="state" name="state"
                                    disabled>
                                    <option value="">Select State</option>
                                </select>
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="city" class="form-label">City</label>
                                <select class="form-select @error('city') is-invalid @enderror" id="city" name="city"
                                    disabled>
                                    <option value="">Select City</option>
                                </select>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address"
                                name="address" rows="2">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="hire_date" class="form-label">Hire Date</label>
                            <input type="date" class="form-control @error('hire_date') is-invalid @enderror" id="hire_date"
                                name="hire_date" value="{{ old('hire_date') }}">
                            @error('hire_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Create Employee
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
        $(document).ready(function () {
            // Load countries on page load
            loadCountries();

            // Country change handler
            $('#country').on('change', function () {
                var country = $(this).val();
                $('#state').prop('disabled', true).html('<option value="">Loading...</option>');
                $('#city').prop('disabled', true).html('<option value="">Select City</option>');

                if (country) {
                    loadStates(country);
                }
            });

            // State change handler
            $('#state').on('change', function () {
                var state = $(this).val();
                $('#city').prop('disabled', true).html('<option value="">Loading...</option>');

                if (state) {
                    loadCities(state);
                }
            });

            function loadCountries() {
                $.ajax({
                    url: '{{ route("api.countries") }}',
                    type: 'GET',
                    success: function (data) {
                        var options = '<option value="">Select Country</option>';
                        $.each(data, function (key, value) {
                            options += '<option value="' + value.country_name + '">' + value.country_name + '</option>';
                        });
                        $('#country').html(options);
                    },
                    error: function () {
                        $('#country').html('<option value="">Failed to load countries</option>');
                    }
                });
            }

            function loadStates(country) {
                $.ajax({
                    url: '/api/states/' + encodeURIComponent(country),
                    type: 'GET',
                    success: function (data) {
                        var options = '<option value="">Select State</option>';
                        $.each(data, function (key, value) {
                            options += '<option value="' + value.state_name + '">' + value.state_name + '</option>';
                        });
                        $('#state').prop('disabled', false).html(options);
                    },
                    error: function () {
                        $('#state').prop('disabled', false).html('<option value="">Failed to load states</option>');
                    }
                });
            }

            function loadCities(state) {
                var country = $('#country').val();
                $.ajax({
                    url: '/api/cities/' + encodeURIComponent(state) + '?country=' + encodeURIComponent(country),
                    type: 'GET',
                    success: function (data) {
                        var options = '<option value="">Select City</option>';
                        $.each(data, function (key, value) {
                            options += '<option value="' + value.city_name + '">' + value.city_name + '</option>';
                        });
                        $('#city').prop('disabled', false).html(options);
                    },
                    error: function () {
                        $('#city').prop('disabled', false).html('<option value="">Failed to load cities</option>');
                    }
                });
            }
        });
    </script>
@endpush