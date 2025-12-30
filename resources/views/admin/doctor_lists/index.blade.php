@extends('admin.layout.app')

@section('title', 'Doctors Listing')

@section('content')



<main id="main" class="main">

  <div class="pagetitle">
    <h1>Doctors Listing</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">Doctors Listing</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <!-- Add New Doctor Button -->
   @php if (auth()->user()->role == 'admin' ) { @endphp
  <div class="mb-3">
    <a href="{{ route('doctors.create') }}" class="btn btn-primary">Add New Doctor</a>
     <a href="{{ route('doctors.import.form') }}" class="btn btn-primary">Import New Doctors in Excel</a>
  </div>
  @php } @endphp
  @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  
  <!-- Table Section -->
  <div class="table-responsive">
    <table id="example1" class="table table-striped table-bordered" style="font-size:13px;" >
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>id</th>
                <th>Dr.Name</th>
                <th>Phone Number</th>
                <th>Email</th>
                <th>Degree</th>
                <th>Reg No.</th>
                <th>Category</th>
                <th>Sub-category</th>
                <th>Personal Phone no.</th>
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
                <th>Experience</th>
                <th>Mode of Payment</th>
                <th>Location 1</th>
                <th>Location 2</th>
                <th>Location 3</th>
                <th>Location 4</th>
                <th>Location 5</th>
                <th>Membership</th>
                <th>Clinics</th>
                <th>Active</th>
                <th>Status</th>
                <th>Last Update</th>
                   @php if (auth()->user()->role == 'admin' ) { @endphp
                
                <th>Action</th>
                  @php } @endphp
            </tr>
        </thead>
        <tbody>
             @foreach ($doctors as $index => $doctor)
             @php
                $user = DB::table('users')->where('id', $doctor->auth_id)->first();
            @endphp
        
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $doctor->id }}</td>
                
                <td>{{ $doctor->name }}</td>
                <td>{{ $doctor->phone_number }}</td>
                <td>{{ $doctor->email ?? 'N/A' }}</td>
                <td>{{ $doctor->degree }}</td>
                <td>{{ $doctor->reg_no }}</td>
                <td> 
                    @php
                        // Retrieve the category_id for the given doctor (assuming it's a comma-separated string)
                        $categoryIds = $doctor->category_id;  // category_id is a comma-separated string like '1,2,3,4,5'
                    
                        // Convert the comma-separated string into an array
                        $categoryIdsArray = explode(',', $categoryIds);  // This will create an array of clinic IDs
                    
                        // Retrieve category using the category ids
                        $category = \App\Models\Category::whereIn('id', $categoryIdsArray)->get();
                    @endphp
                    
                    @foreach ($category as $cat)
                        {{ $cat->name }}@if(!$loop->last), @endif
                    @endforeach
                </td>
                <td>{{ $doctor->sub_category }}</td>
                <td>{{ $doctor->personal_phone_number }}</td>
               
                @php
                    $months = json_decode($doctor->month, true); // Decode months
                    $days = json_decode($doctor->day, true);     // Decode days
                @endphp
                
                <th>
                    @if(is_array($months))
                        {{ implode(', ', $months) }}
                    @else
                        No months selected.
                    @endif
                </th>
                <th>
                    @if(is_array($days) && count($days) > 0)
                        {{ implode(', ', $days) }}
                    @else
                        No days selected.
                    @endif
                </th>
                
                <th> {{ $doctor->{'time_slot'} }}</th>
               
                
                <td>{{ $doctor->profile_text }}</td>
                
                  
                <td>
                    @if($doctor->image)
                        <!-- Display the current image if available -->
                        <div>
                            <img src="{{ asset('/admin/uploads/doctor/' . $doctor->image) }}" alt="Current Image" class="img-fluid mb-2" style="max-width: 50px;">
                        </div>
                    @else
                        <!-- Display the default image if no image is available -->
                        <div>
                            <img src="{{ asset('/admin/assets/adminimg/demo_doctor_image.jpeg') }}" alt="Default Image" class="img-fluid mb-2" style="max-width: 50px;">
                        </div>
                    @endif
                </td>
                
                <td>{{ $doctor->fees ?? 'N/A' }}</td>
                <td>{{ $doctor->whatsapp ?? 'N/A' }}</td>
                <td>{{ $doctor->facebook ?? 'N/A' }}</td>
                <td>{{ $doctor->instagram ?? 'N/A' }}</td>
                <td>{{ $doctor->website ?? 'N/A' }}</td>
                
                <td>{{ $doctor->latitude ?? 'N/A' }}</td>
                <td>{{ $doctor->logitude ?? 'N/A' }}</td>
                <td>{{ $doctor->language ?? 'N/A' }}</td>
                <td>{{ $doctor->experience ?? 'N/A' }}</td>
                
                <td>{{ $doctor->mode_of_payment ?? 'N/A' }}</td>
                <td>{{ $doctor->loc1 ?? 'N/A' }}</td>
                <td>{{ $doctor->loc2 ?? 'N/A' }}</td>
                <td>{{ $doctor->loc3 ?? 'N/A' }}</td>
                
                <td>{{ $doctor->loc4 ?? 'N/A' }}</td>
                <td>{{ $doctor->loc5 ?? 'N/A' }}</td>
                <td>{{ $doctor->membership ?? 'N/A' }}</td>
                
                <td>
                    @php
                        // Retrieve the clinic_ids for the given doctor (assuming it's a comma-separated string)
                        $clinicIds = $doctor->clinic_ids;  // clinic_ids is a comma-separated string like '1,2,3,4,5'
                    
                        // Convert the comma-separated string into an array
                        $clinicIdsArray = explode(',', $clinicIds);  // This will create an array of clinic IDs
                    
                        // Retrieve clinics using the clinic IDs
                        $clinics = \App\Models\Client::whereIn('id', $clinicIdsArray)->get();
                    @endphp
                    
                    @foreach ($clinics as $clinic)
                        {{ $clinic->name }}@if(!$loop->last), @endif
                    @endforeach
                </td>
                <td>{{ $doctor->active ? 'Yes' : 'No' }}</td>
                <td>{{ $doctor->status ?? 'N/A' }}</td>
                <td>{{ \Carbon\Carbon::parse($doctor->last_update)->format('d-m-Y') }}</td>
                
                 
                
                 @php if (auth()->user()->role == 'admin' ) { @endphp
                    
                <td>
                    <!-- Edit Button -->
                    <a href="{{ route('doctors.edit', $doctor->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    
                    <!-- Delete Button -->
                    <form action="{{ route('doctors.destroy', $doctor->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this doctor?')">Delete</button>
                    </form>
                    
                    <!-- Status Update Button -->
                    @if(auth()->user()->role == 'admin')
                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#statusModal" data-id="{{ $doctor->id }}" data-status="{{ $doctor->status }}">Change Status</button>
                 @endif
                </td>
                
                 @php } @endphp
            </tr>
            @endforeach
        </tbody>
    </table>
    <!-- End Table Section -->
    
   <!-- Modal for Status Update -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
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
                            <option value="Approved">Select Status</option>
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

    
    
    <!-- Modal end--->
</div>



</main><!-- End #main -->

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>



<script>
    var $jq = jQuery.noConflict();

$jq(document).ready(function() {
    var table = $jq('#example1').DataTable({
        pageLength: 25,
        rowReorder: {
            selector: 'td:nth-child(2)'
        },
        responsive: true,
        order: [[1, 'desc']],
        dom: 'Bfrtip',
        buttons: [
            // 'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });
});
</script>

<script>
jQuery.noConflict();
jQuery(document).ready(function ($) {

    // Set up CSRF token for Ajax
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Show modal and populate fields
    $('#statusModal').on('show.bs.modal', function (event) { 
        var button = $(event.relatedTarget); // Button that triggered the modal
        var doctorsId = button.data('id');
        var doctorsStatus = button.data('status');
        
        var modal = $(this);
        modal.find('#doctors_id').val(doctorsId);
        modal.find('#status').val(doctorsStatus);
    });

    // Handle form submission
    $('#statusForm').off('submit').on('submit', function (event) {
        console.log("Form submitted, preventing default...");
        event.preventDefault();  // Prevent the default form submission

        var form = $(this);
        var url = '{{ route("doctors.update-status") }}';  // Use the route helper
        console.log("Generated URL: ", url);
        $.ajax({
            url: url,
            method: 'POST',
            data: form.serialize(),
            success: function (response) {
                if (response.success) {
                    $('#statusModal').modal('hide'); // Close modal
                    alert(response.message);
                    location.reload(); // Reload page
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error(error);
                alert('An error occurred. Please try again.');
            }
        });
    });
});

</script>



