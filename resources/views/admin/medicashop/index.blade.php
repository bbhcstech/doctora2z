@extends('admin.layout.app')

@section('title', 'Medica Shop Listing')

@section('content')



    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Medica Shop Listing</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Medica Shop Listing</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <!-- Add New Doctor Button -->

        <div class="mb-3">
            <a href="{{ route('medicashop.create') }}" class="btn btn-primary">Add New Medica Shop</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Table Section -->
        <table id="example1" class="table table-bordered">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Address</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($medicashops as $medicashop)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $medicashop->name }}</td>
                        <td>
                            @if ($medicashop->image)
                                <!-- Display the current image if available -->
                                <div>
                                    <img src="{{ asset('/admin/uploads/medicashop/' . $medicashop->image) }}"
                                        alt="Current Image" class="img-fluid mb-2" style="max-width: 50px;">
                                </div>
                            @else
                                <!-- Display the default image if no image is available -->
                                <div>
                                    <img src="{{ asset('/admin/assets/adminimg/medicashop.jpg') }}" alt="Default Image"
                                        class="img-fluid mb-2" style="max-width: 50px;">
                                </div>
                            @endif
                        </td>
                        <td>{{ $medicashop->address_link }}</td>
                        <td>
                            <a href="{{ route('medicashop.edit', $medicashop) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('medicashop.destroy', $medicashop) }}" method="POST"
                                style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endsection

</main><!-- End #main -->



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
                order: [
                    [1, 'desc']
                ],
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
