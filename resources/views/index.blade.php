@extends('layouts.app')

@section('content')
<div class="container">
    <div class="jumbotron mt-3">
        <h1>Choose image to upload</h1>
        <form method="POST" action="/upload" enctype="multipart/form-data">
            @csrf
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <strong>{{ $message }}</strong>
                </div>
            @endif

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="custom-file">
                <input type="file" class="custom-file-input" name="image">
                <label class="custom-file-label" for="customFile">Choose file</label>
            </div>
            <button type="submit" name="submit" class="btn btn-primary btn-block mt-4">
                Upload Files
            </button>
        </form>
    </div>
</div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function(){
            $(".custom-file-input").on("change", function() {
                var fileName = $(this).val().split("\\").pop();

                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            });
        });
    </script>
@endpush
