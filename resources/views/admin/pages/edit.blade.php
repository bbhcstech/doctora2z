@extends('admin.layout.app')

@section('title', 'Edit Page')

@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Edit Patients Say</h1>
        </div>

        <div class="card" style="background-color: #d8e0f1; padding: 20px; border-radius: 10px;">
            <div class="card-body">
                <form action="{{ route('pages.update', $page->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Client Name</label>
                        <input type="text" class="form-control" name="title" value="{{ $page->title }}" required>
                    </div>

                    <!-- Banner Image -->
                    <div class="mb-3">
                        <label for="banner_image" class="form-label">Client Image</label>
                        <input type="file" class="form-control" name="banner_image" accept="image/*">
                        @if ($page->banner_image)
                            <img src="{{ asset('/admin/uploads/pages/' . $page->banner_image) }}" alt="Banner Image"
                                class="img-thumbnail mt-2" style="width: 100px;">
                        @endif
                    </div>

                    <!-- Slug -->
                    <div class="mb-3">
                        <label for="slug" class="form-label">Profession</label>
                        <input type="text" class="form-control" name="slug" value="{{ $page->slug }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label">Description</label>
                        <textarea class="form-control three-line-textarea" name="desc" required maxlength="250">{{ $page->desc }}</textarea>
                    </div>



                    <!-- Submit Button -->
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Update Page</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
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
    document.querySelector('textarea[name="desc"]').addEventListener('input', function() {
        let lines = this.value.split('\n');
        if (lines.length > 3) {
            this.value = lines.slice(0, 3).join('\n');
        }
    });
</script>
