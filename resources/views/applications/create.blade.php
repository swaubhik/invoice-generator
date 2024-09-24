<!DOCTYPE html>
<html>

<head>
  <title>Create Application</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Include any additional CSS or JS here -->
  {{-- jquery --}}
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
  <div class="container mt-5">
    <h2>Create Application</h2>

    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('applications.store') }}" method="POST">
      @csrf
      <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
      </div>
      <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>

  <!-- Include CKEditor or any other rich text editor -->
  <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
  <script>
    // Initialize CKEditor
    CKEDITOR.replace('description');

    // Form validation
    function validateForm() {
      // Update the textarea with the CKEditor data before submission
      var editorData = CKEDITOR.instances.description.getData();

      if (!editorData.trim()) {
        alert('Description field is required');
        return false; // Prevent form submission
      }
      return true; // Allow form submission
    }
  </script>
</body>

</html>
