@extends('admin.layouts.app')

@section('content')
<div class="container-fluid mt-3">
    <div class="card">
        <div class="card-header"><h5>Edit Blog Category</h5></div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.blogcategories.update', $cat->id) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" value="{{ old('name', $cat->name) }}" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control">{{ old('description', $cat->description) }}</textarea>
                </div>
                <button class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
</div>
@endsection