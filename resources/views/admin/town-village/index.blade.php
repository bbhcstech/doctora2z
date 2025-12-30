@extends('admin.layout.app')

@section('title', 'City/Town/Village')

@section('content')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Cities/Towns/Villages</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">City/Town/Village</li>
            </ol>
        </nav>
    </div>

    <a href="{{ route('town-village.create') }}" class="btn btn-primary mb-3">Add City/Town/Village</a>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table id="example1" class="table table-striped table-bordered" style="font-size:13px;">
    <thead>
        <tr>
            <th>#</th>
            <th>ID</th>
            <th>City/Town/Village Name</th>
            <th>District</th>
            <th>State (Part)</th>
            <th>Country</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
       @foreach($towns as $index => $town)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $town->id }}</td>
            <td>{{ $town->name }}</td>
            <td>{{ $town->district?->name }}</td>
            <td>{{ $town->district?->state?->name }}</td>
            <td>{{ $town->district?->state?->country?->name }}</td>
            <td>
                <a href="{{ route('town-village.edit', $town->id) }}" class="btn btn-warning">Edit</a>
                <form action="{{ route('town-village.destroy', $town->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"  onclick="return confirm('Are you sure you want to delete this city/town/village?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach

    </tbody>
</table>

</main>

@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->


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

