@extends('admin.layout.app')

@section('title', 'Doctors Inline Listing')

@section('content')

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Doctors Inline Listing</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Doctors Inline Listing</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Bulk Actions Form -->
        <form id="bulkForm" method="POST" action="{{ route('doctors.bulk-store') }}">
            @csrf
            <div class="mb-3">
                @if (auth()->user()->role == 'admin')
                    <button type="button" class="btn btn-primary" id="addRow">Add New Doctor</button>
                    <button type="submit" class="btn btn-success">Save All</button>
                    <button type="button" class="btn btn-danger" id="bulkDelete"
                        formaction="{{ route('doctors.bulk-delete') }}">Delete Selected</button>
                @endif
            </div>

            <!-- Table Section -->
            <div class="table-responsive">
                <table id="example1" class="table table-striped table-bordered" style="font-size:13px;">
                    <thead class="thead-dark">
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>#</th>
                            <th>ID</th>
                            <th>Dr. Name</th>
                            <th>Phone Number</th>
                            <th>Email</th>
                            <th>Degree</th>
                            <th>Reg No.</th>
                            <th>Category</th>
                            <th>Sub-category</th>
                            <th>Personal Phone</th>
                            <th>Month</th>
                            <th>Day</th>
                            <th>Time Slot</th>
                            <th>Profile Details</th>
                            <th>Image</th>
                            <th>Fees</th>
                            <th>Whatsapp</th>
                            <th>Facebook</th>
                            <th>Instagram</th>
                            <th>Website</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Language</th>
                            <th>Mode of Payment</th>
                            <th>Location 1</th>
                            <th>Location 2</th>
                            <th>Location 3</th>
                            <th>Location 4</th>
                            <th>Location 5</th>
                            <th>Membership</th>
                            <th>Clinics</th>
                            <th>Country</th>
                            <th>State</th>
                            <th>District</th>
                            <th>City</th>
                            <th>Active</th>
                            <th>Status</th>
                            <th>Last Update</th>
                            @if (auth()->user()->role == 'admin')
                                <th>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody id="doctorTableBody">
                        @foreach ($doctors as $index => $doctor)
                            @php
                                $user = DB::table('users')->where('id', $doctor->auth_id)->first();
                                $categoryIds = $doctor->category_id ? explode(',', $doctor->category_id) : [];
                                $categoriesSelected = \App\Models\Category::whereIn('id', $categoryIds)->get();
                                $clinicIds = $doctor->clinic_ids ? explode(',', $doctor->clinic_ids) : [];
                                $clinicsSelected = \App\Models\Client::whereIn('id', $clinicIds)->get();
                                $months = json_decode($doctor->month, true) ?? [];
                                $days = json_decode($doctor->day, true) ?? [];
                                $timeSlots = $doctor->time_slot ? explode(', ', $doctor->time_slot) : [];
                            @endphp
                            <tr data-id="{{ $doctor->id }}">
                                <td><input type="checkbox" name="selected_ids[]" value="{{ $doctor->id }}"
                                        class="selectRow"></td>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $doctor->id }}</td>
                                <td><input type="text" name="doctors[{{ $doctor->id }}][name]"
                                        value="{{ $doctor->name }}" class="form-control"></td>
                                <td><input type="text" name="doctors[{{ $doctor->id }}][phone_number]"
                                        value="{{ $doctor->phone_number }}" class="form-control"></td>
                                <td><input type="email" name="doctors[{{ $doctor->id }}][email]"
                                        value="{{ $user->email ?? '' }}" class="form-control"></td>
                                <td><input type="text" name="doctors[{{ $doctor->id }}][degree]"
                                        value="{{ $doctor->degree }}" class="form-control"></td>
                                <td><input type="text" name="doctors[{{ $doctor->id }}][reg_no]"
                                        value="{{ $doctor->reg_no }}" class="form-control"></td>
                                <td>
                                    <select name="doctors[{{ $doctor->id }}][category_id][]" multiple
                                        class="form-control">
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ in_array($category->id, $categoryIds) ? 'selected' : '' }}>
                                                {{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="text" name="doctors[{{ $doctor->id }}][sub_category]"
                                        value="{{ $doctor->sub_category }}" class="form-control"></td>
                                <td><input type="text" name="doctors[{{ $doctor->id }}][personal_phone_number]"
                                        value="{{ $doctor->personal_phone_number }}" class="form-control"></td>
                                <td>
                                    <select name="doctors[{{ $doctor->id }}][month][]" multiple class="form-control">
                                        @foreach (['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $month)
                                            <option value="{{ $month }}"
                                                {{ in_array($month, $months) ? 'selected' : '' }}>{{ $month }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="doctors[{{ $doctor->id }}][day][]" multiple class="form-control">
                                        @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                            <option value="{{ $day }}"
                                                {{ in_array($day, $days) ? 'selected' : '' }}>{{ $day }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    @foreach ($timeSlots as $slotIndex => $slot)
                                        <div class="input-group mb-1">
                                            <input type="time"
                                                name="doctors[{{ $doctor->id }}][time_slot][{{ $slotIndex }}][start]"
                                                value="{{ explode(' - ', $slot)[0] ?? '' }}" class="form-control">
                                            <input type="time"
                                                name="doctors[{{ $doctor->id }}][time_slot][{{ $slotIndex }}][end]"
                                                value="{{ explode(' - ', $slot)[1] ?? '' }}" class="form-control">
                                        </div>
                                    @endforeach
                                    <button type="button" class="btn btn-sm btn-outline-secondary add-time-slot">Add Time
                                        Slot</button>
                                </td>
                                <td>
                                    <textarea name="doctors[{{ $doctor->id }}][profile_text]" class="form-control">{{ $doctor->profile_text }}</textarea>
                                </td>
                                <td>
                                    @if ($doctor->image)
                                        <img src="{{ asset('/admin/uploads/doctor/' . $doctor->image) }}"
                                            alt="Doctor Image" class="img-fluid mb-2" style="max-width: 50px;">
                                    @else
                                        <img src="{{ asset('/admin/assets/adminimg/demo_doctor_image.jpeg') }}"
                                            alt="Default Image" class="img-fluid mb-2" style="max-width: 50px;">
                                    @endif
                                    <input type="file" name="doctors[{{ $doctor->id }}][image]" class="form-control">
                                </td>
                                <td><input type="number" name="doctors[{{ $doctor->id }}][fees]"
                                        value="{{ $doctor->fees }}" class="form-control" step="0.01"></td>
                                <td><input type="text" name="doctors[{{ $doctor->id }}][whatsapp]"
                                        value="{{ $doctor->whatsapp }}" class="form-control"></td>
                                <td><input type="text" name="doctors[{{ $doctor->id }}][facebook]"
                                        value="{{ $doctor->facebook }}" class="form-control"></td>
                                <td><input type="text" name="doctors[{{ $doctor->id }}][instagram]"
                                        value="{{ $doctor->instagram }}" class="form-control"></td>
                                <td><input type="text" name="doctors[{{ $doctor->id }}][website]"
                                        value="{{ $doctor->website }}" class="form-control"></td>
                                <td><input type="number" name="doctors[{{ $doctor->id }}][latitude]"
                                        value="{{ $doctor->latitude }}" class="form-control" step="0.000001"></td>
                                <td><input type="number" name="doctors[{{ $doctor->id }}][logitude]"
                                        value="{{ $doctor->logitude }}" class="form-control" step="0.000001"></td>
                                <td><input type="text" name="doctors[{{ $doctor->id }}][language]"
                                        value="{{ $doctor->language }}" class="form-control"></td>
                                <td><input type="text" name="doctors[{{ $doctor->id }}][mode_of_payment]"
                                        value="{{ $doctor->mode_of_payment }}" class="form-control"></td>
                                <td><input type="text" name="doctors[{{ $doctor->id }}][loc1]"
                                        value="{{ $doctor->loc1 }}" class="form-control"></td>
                                <td><input type="text" name="doctors[{{ $doctor->id }}][loc2]"
                                        value="{{ $doctor->loc2 }}" class="form-control"></td>
                                <td><input type="text" name="doctors[{{ $doctor->id }}][loc3]"
                                        value="{{ $doctor->loc3 }}" class="form-control"></td>
                                <td><input type="text" name="doctors[{{ $doctor->id }}][loc4]"
                                        value="{{ $doctor->loc4 }}" class="form-control"></td>
                                <td><input type="text" name="doctors[{{ $doctor->id }}][loc5]"
                                        value="{{ $doctor->loc5 }}" class="form-control"></td>
                                <td><input type="text" name="doctors[{{ $doctor->id }}][membership]"
                                        value="{{ $doctor->membership }}" class="form-control"></td>
                                <td>
                                    <select name="doctors[{{ $doctor->id }}][clinic_ids]" class="form-control">
                                        <option value="">Select Clinic</option>
                                        @foreach ($clinics as $clinic)
                                            <option value="{{ $clinic->id }}"
                                                {{ in_array($clinic->id, $clinicIds) ? 'selected' : '' }}>
                                                {{ $clinic->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="doctors[{{ $doctor->id }}][country_id]" class="form-control">
                                        <option value="">Select Country</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}"
                                                {{ $doctor->country_id == $country->id ? 'selected' : '' }}>
                                                {{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="doctors[{{ $doctor->id }}][state_id]" class="form-control">
                                        <option value="">Select State</option>
                                        @foreach ($states as $state)
                                            <option value="{{ $state->id }}"
                                                {{ $doctor->state_id == $state->id ? 'selected' : '' }}>
                                                {{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="doctors[{{ $doctor->id }}][district_id]" class="form-control">
                                        <option value="">Select District</option>
                                        @foreach ($districts as $district)
                                            <option value="{{ $district->id }}"
                                                {{ $doctor->district_id == $district->id ? 'selected' : '' }}>
                                                {{ $district->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="doctors[{{ $doctor->id }}][city_id]" class="form-control">
                                        <option value="">Select City</option>
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->id }}"
                                                {{ $doctor->city_id == $city->id ? 'selected' : '' }}>{{ $city->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="checkbox" name="doctors[{{ $doctor->id }}][active]" value="1"
                                        {{ $doctor->active ? 'checked' : '' }}>
                                </td>
                                <td>{{ $doctor->status ?? 'N/A' }}</td>
                                <td>{{ $doctor->updated_at ? \Carbon\Carbon::parse($doctor->updated_at)->format('d-m-Y') : 'N/A' }}
                                </td>
                                @if (auth()->user()->role == 'admin')
                                    <td>
                                        <button type="button" class="btn btn-success btn-sm save-row"
                                            data-id="{{ $doctor->id }}">Save</button>
                                        <button type="button" class="btn btn-danger btn-sm delete-row"
                                            data-id="{{ $doctor->id }}">Delete</button>
                                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                            data-target="#statusModal" data-id="{{ $doctor->id }}"
                                            data-status="{{ $doctor->status }}">Change Status</button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>

        <!-- Status Update Modal -->
        <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="statusModalLabel">Update Status</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="statusForm" method="POST">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="doctors_id" id="doctors_id" value="">
                            <div class="form-group">
                                <label for="status">Select Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="Approved">Approved</option>
                                    <option value="Pending">Pending</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap4.min.js"></script>

    <script>
        var $jq = jQuery.noConflict();

        $jq(document).ready(function() {
            // Initialize DataTable
            var table = $jq('#example1').DataTable({
                pageLength: 25,
                responsive: true,
                order: [
                    [2, 'desc']
                ],
                dom: 'Bfrtip',
                buttons: ['excelHtml5', 'csvHtml5', 'pdfHtml5']
            });

            // CSRF Token Setup
            $jq.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $jq('meta[name="csrf-token"]').attr('content')
                }
            });

            // Select All Checkbox
            $jq('#selectAll').on('change', function() {
                $jq('.selectRow').prop('checked', this.checked);
            });

            // Add New Row
            $jq('#addRow').on('click', function() {
                var newRowIndex = new Date().getTime();
                var row = `
                <tr data-id="">
                    <td><input type="checkbox" name="selected_ids[]" class="selectRow"></td>
                    <td></td>
                    <td></td>
                    <td><input type="text" name="doctors[${newRowIndex}][name]" class="form-control"></td>
                    <td><input type="text" name="doctors[${newRowIndex}][phone_number]" class="form-control"></td>
                    <td><input type="email" name="doctors[${newRowIndex}][email]" class="form-control"></td>
                    <td><input type="text" name="doctors[${newRowIndex}][degree]" class="form-control"></td>
                    <td><input type="text" name="doctors[${newRowIndex}][reg_no]" class="form-control"></td>
                    <td>
                        <select name="doctors[${newRowIndex}][category_id][]" multiple class="form-control">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="text" name="doctors[${newRowIndex}][sub_category]" class="form-control"></td>
                    <td><input type="text" name="doctors[${newRowIndex}][personal_phone_number]" class="form-control"></td>
                    <td>
                        <select name="doctors[${newRowIndex}][month][]" multiple class="form-control">
                            @foreach (['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $month)
                                <option value="{{ $month }}">{{ $month }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="doctors[${newRowIndex}][day][]" multiple class="form-control">
                            @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                <option value="{{ $day }}">{{ $day }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <div class="input-group mb-1">
                            <input type="time" name="doctors[${newRowIndex}][time_slot][0][start]" class="form-control">
                            <input type="time" name="doctors[${newRowIndex}][time_slot][0][end]" class="form-control">
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary add-time-slot">Add Time Slot</button>
                    </td>
                    <td><textarea name="doctors[${newRowIndex}][profile_text]" class="form-control"></textarea></td>
                    <td><input type="file" name="doctors[${newRowIndex}][image]" class="form-control"></td>
                    <td><input type="number" name="doctors[${newRowIndex}][fees]" class="form-control" step="0.01"></td>
                    <td><input type="text" name="doctors[${newRowIndex}][whatsapp]" class="form-control"></td>
                    <td><input type="text" name="doctors[${newRowIndex}][facebook]" class="form-control"></td>
                    <td><input type="text" name="doctors[${newRowIndex}][instagram]" class="form-control"></td>
                    <td><input type="text" name="doctors[${newRowIndex}][website]" class="form-control"></td>
                    <td><input type="number" name="doctors[${newRowIndex}][latitude]" class="form-control" step="0.000001"></td>
                    <td><input type="number" name="doctors[${newRowIndex}][logitude]" class="form-control" step="0.000001"></td>
                    <td><input type="text" name="doctors[${newRowIndex}][language]" class="form-control"></td>
                    <td><input type="text" name="doctors[${newRowIndex}][mode_of_payment]" class="form-control"></td>
                    <td><input type="text" name="doctors[${newRowIndex}][loc1]" class="form-control"></td>
                    <td><input type="text" name="doctors[${newRowIndex}][loc2]" class="form-control"></td>
                    <td><input type="text" name="doctors[${newRowIndex}][loc3]" class="form-control"></td>
                    <td><input type="text" name="doctors[${newRowIndex}][loc4]" class="form-control"></td>
                    <td><input type="text" name="doctors[${newRowIndex}][loc5]" class="form-control"></td>
                    <td><input type="text" name="doctors[${newRowIndex}][membership]" class="form-control"></td>
                    <td>
                        <select name="doctors[${newRowIndex}][clinic_ids]" class="form-control">
                            <option value="">Select Clinic</option>
                            @foreach ($clinics as $clinic)
                                <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="doctors[${newRowIndex}][country_id]" class="form-control">
                            <option value="">Select Country</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="doctors[${newRowIndex}][state_id]" class="form-control">
                            <option value="">Select State</option>
                            @foreach ($states as $state)
                                <option value="{{ $state->id }}">{{ $state->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="doctors[${newRowIndex}][district_id]" class="form-control">
                            <option value="">Select District</option>
                            @foreach ($districts as $district)
                                <option value="{{ $district->id }}">{{ $district->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="doctors[${newRowIndex}][city_id]" class="form-control">
                            <option value="">Select City</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="checkbox" name="doctors[${newRowIndex}][active]" value="1"></td>
                    <td>N/A</td>
                    <td>N/A</td>
                    @if (auth()->user()->role == 'admin')
                        <td>
                            <button type="button" class="btn btn-success btn-sm save-row">Save</button>
                            <button type="button" class="btn btn-danger btn-sm delete-row">Delete</button>
                        </td>
                    @endif
                </tr>`;
                table.row.add($jq(row)).draw();
            });

            // Add Time Slot
            $jq(document).on('click', '.add-time-slot', function() {
                var row = $jq(this).closest('tr');
                var index = row.find('input[name*="[name]"]').attr('name').match(/\d+/)[0];
                var slotIndex = row.find('.input-group').length;
                var timeSlot = `
                <div class="input-group mb-1">
                    <input type="time" name="doctors[${index}][time_slot][${slotIndex}][start]" class="form-control">
                    <input type="time" name="doctors[${index}][time_slot][${slotIndex}][end]" class="form-control">
                </div>`;
                $jq(this).before(timeSlot);
            });

            // Save Row via AJAX
            $jq(document).on('click', '.save-row', function() {
                var row = $jq(this).closest('tr');
                var id = row.data('id') || '';
                var formData = new FormData();
                row.find('input, select, textarea').each(function() {
                    var input = $jq(this);
                    if (input.is(':file')) {
                        if (input[0].files.length > 0) {
                            formData.append(input.attr('name'), input[0].files[0]);
                        }
                    } else if (input.is(':checkbox')) {
                        formData.append(input.attr('name'), input.is(':checked') ? 1 : 0);
                    } else {
                        formData.append(input.attr('name'), input.val());
                    }
                });
                formData.append('id', id);

                $jq.ajax({
                    url: '{{ route('doctors.inline-save') }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            location.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('Validation errors: ' + JSON.stringify(xhr.responseJSON.errors));
                    }
                });
            });

            // Delete Row via AJAX
            $jq(document).on('click', '.delete-row', function() {
                var row = $jq(this).closest('tr');
                var id = row.data('id');
                if (!id) {
                    table.row(row).remove().draw();
                    return;
                }
                if (confirm('Are you sure you want to delete this doctor?')) {
                    $jq.ajax({
                        url: '{{ route('doctors.ajax-destroy', ['id' => ':id']) }}'.replace(':id',
                            id),
                        method: 'DELETE',
                        success: function(response) {
                            if (response.success) {
                                table.row(row).remove().draw();
                                alert(response.message);
                            } else {
                                alert('Error: ' + response.message);
                            }
                        },
                        error: function(xhr) {
                            alert('An error occurred. Please try again.');
                        }
                    });
                }
            });

            // Bulk Delete
            $jq('#bulkDelete').on('click', function() {
                var selectedIds = $jq('.selectRow:checked').map(function() {
                    return $jq(this).val();
                }).get();
                if (selectedIds.length === 0) {
                    alert('No doctors selected for deletion.');
                    return;
                }
                if (confirm('Are you sure you want to delete the selected doctors?')) {
                    $jq.ajax({
                        url: '{{ route('doctors.bulk-delete') }}',
                        method: 'POST',
                        data: {
                            selected_ids: selectedIds,
                            _method: 'POST'
                        },
                        success: function(response) {
                            if (response.success) {
                                alert(response.message);
                                location.reload();
                            } else {
                                alert('Error: ' + response.message);
                            }
                        },
                        error: function(xhr) {
                            alert('An error occurred. Please try again.');
                        }
                    });
                }
            });

            // Status Modal
            $jq('#statusModal').on('show.bs.modal', function(event) {
                var button = $jq(event.relatedTarget);
                var doctorsId = button.data('id');
                var doctorsStatus = button.data('status') || 'Approved';
                var modal = $jq(this);
                modal.find('#doctors_id').val(doctorsId);
                modal.find('#status').val(doctorsStatus);
            });

            // Status Form Submission
            $jq('#statusForm').on('submit', function(event) {
                event.preventDefault();
                var form = $jq(this);
                $jq.ajax({
                    url: '{{ route('doctors.update-status') }}',
                    method: 'PATCH',
                    data: form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            $jq('#statusModal').modal('hide');
                            alert(response.message);
                            location.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('An error occurred: ' + xhr.responseJSON.message);
                    }
                });
            });
        });
    </script>
@endsection
