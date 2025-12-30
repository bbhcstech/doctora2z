@extends('admin.layout.app')

@section('title', 'Districts')

@section('content')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>District / City / Town / Village</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">District / City / Town / Village</li>
            </ol>
        </nav>
    </div>

    <div class="mb-3">
        <a href="{{ route('district.create') }}" class="btn btn-primary mb-3">Add District / City / Town / Village</a>
        <a href="{{ route('district.import.form') }}" class="btn btn-primary mb-3">Import New District / City / Town / Village</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table id="example1" class="table table-striped table-bordered" style="font-size:13px;">
        <thead>
            <tr>
                <th>#</th>
                <th>Country</th>
                <th>State</th>
                <th>District</th>
                <th>Area</th> {{-- ✅ city name shown as area --}}
                <th>Pincode</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @php $row = 1; @endphp
            @foreach($districts as $district)
                @foreach($district->pincodes as $pincode)
                    <tr>
                        <td>{{ $row++ }}</td>
                        <td>{{ $district->state->country->name ?? '—' }}</td>
                        <td>{{ $district->state->name ?? '—' }}</td>
                        <td>{{ $district->name }}</td>
                        <td>{{ $pincode->city->name ?? '—' }}</td> {{-- ✅ area --}}
                        <td>{{ $pincode->pincode }}</td>
                        <td>
                            <a href="{{ route('district.edit', $district->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('district.destroy', $district->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this District / City / Town / Village?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach

                @if($district->pincodes->isEmpty())
                    <tr>
                        <td>{{ $row++ }}</td>
                        <td>{{ $district->state->country->name ?? '—' }}</td>
                        <td>{{ $district->state->name ?? '—' }}</td>
                        <td>{{ $district->name }}</td>
                        <td>—</td>
                        <td>—</td>
                        <td>
                            <a href="{{ route('district.edit', $district->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('district.destroy', $district->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this District / City / Town / Village?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</main>
@endsection

@push('scripts')
<script>
    var $jq = jQuery.noConflict();
    $jq(document).ready(function() {
        $jq('#example1').DataTable({
            pageLength: 25,
            responsive: true,
            order: [[0, 'asc']], // order by row #
            dom: 'Bfrtip',
            buttons: [
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ]
        });
    });
</script>
@endpush
