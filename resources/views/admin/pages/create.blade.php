<!-- resources/views/pages/create.blade.php -->

@extends('admin.layout.app')

@section('title', 'Create Page')

@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Create New Patients Say</h1>
        </div>

        <div class="card" style="background-color: #d8e0f1; padding: 20px; border-radius: 10px;">
            <div class="card-body">
                <form action="{{ route('pages.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Client Name</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>

                    <!-- Banner Image -->
                    <div class="mb-3">
                        <label for="banner_image" class="form-label">Client Image</label>
                        <input type="file" class="form-control" name="banner_image" accept="image/*" required>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label">Profession</label>
                        <input type="text" class="form-control" name="slug" required>
                    </div>
                    <div class="mb-3">
                        <label for="title" class="form-label">Description</label>
                        <textarea class="form-control three-line-textarea" name="desc" required maxlength="250"></textarea>
                    </div>




            </div>
        </div>



        <!-- Submit Button -->
        <div class="text-end">
            <button type="submit" class="btn btn-primary">Create Page</button>
        </div>
        </form>
        </div>
        </div>
    </main>

    <!-- JavaScript to handle dynamic sections -->


@endsection
<style>
    /* Wrapper Styling */
    .sections-wrapper {
        padding: 20px;
        background-color: #f9f9f9;
        /* Light background for visibility */
        border: 1px solid #ddd;
        border-radius: 8px;
    }

    /* Individual Section Styling */
    .section {
        background-color: #ffffff;
        border: 1px solid #ccc;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    /* Inner Field Styling */
    .section-style .form-label {
        font-weight: bold;
        font-size: 0.9rem;
        margin-bottom: 5px;
    }

    .section-style .form-control {
        border-radius: 5px;
        border: 1px solid #bbb;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .section {
            padding: 15px;
        }

        .sections-wrapper {
            padding: 15px;
        }
    }
</style>

<style>
    .three-line-textarea {
        overflow: hidden;
        resize: none;
        line-height: 1.5em;
        height: 4.5em;
        /* 1.5em * 3 lines */
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.querySelector('textarea[name="desc"]');

        textarea.addEventListener('input', function() {
            const lines = this.value.split(/\r?\n/);
            if (lines.length > 3) {
                this.value = lines.slice(0, 3).join('\n');
            }
        });
    });
</script>
