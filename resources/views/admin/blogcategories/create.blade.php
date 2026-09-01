@extends('admin.layouts.app')

@section('content')
<div class="container-fluid mt-3">
    <div class="card">
        <div class="card-header"><h5>Create Blog Category</h5></div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.blogcategories.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                </div>
                <button class="btn btn-primary">Create</button>
            </form>
        </div>
    </div>
</div>
@endsection