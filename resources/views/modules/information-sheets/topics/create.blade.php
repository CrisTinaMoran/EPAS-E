@extends('layouts.app')

@section('title', 'Create Topic - EPAS-E')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Create New Topic</h1>
                <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Content Management
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('topics.store', $informationSheet->id) }}" method="POST">
                        @csrf

                        <!-- Breadcrumb -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">{{ $informationSheet->module->course->course_name }}</li>
                                        <li class="breadcrumb-item">Module {{ $informationSheet->module->module_number }}</li>
                                        <li class="breadcrumb-item">Info Sheet {{ $informationSheet->sheet_number }}</li>
                                        <li class="breadcrumb-item active">Create New Topic</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>

                        <!-- Information Sheet Info -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Information Sheet</label>
                                    <input type="text" class="form-control" 
                                           value="Sheet {{ $informationSheet->sheet_number }}: {{ $informationSheet->title }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Module</label>
                                    <input type="text" class="form-control" 
                                           value="Module {{ $informationSheet->module->module_number }}: {{ $informationSheet->module->module_name }}" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Topic Details -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="topic_number" class="form-label">Topic Number *</label>
                                    <input type="text" class="form-control @error('topic_number') is-invalid @enderror" 
                                           id="topic_number" name="topic_number" 
                                           value="{{ old('topic_number') }}" 
                                           placeholder="e.g., 1, 2, 3 or 1.1.1" required>
                                    @error('topic_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="title" class="form-label">Title *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" 
                                           value="{{ old('title') }}" 
                                           placeholder="e.g., Electric History, Static Electricity" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="form-group mt-4">
                            <label for="content" class="form-label">Content *</label>
                            <textarea class="form-control preserve-whitespace @error('content') is-invalid @enderror" 
                                    id="content" name="content" 
                                    rows="15" 
                                    placeholder="Enter the detailed content for this topic...">{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <small>
                                    <strong>Formatting tips:</strong> Use empty lines to separate paragraphs. 
                                    Basic formatting allowed: <code>&lt;b&gt;</code> <code>&lt;i&gt;</code> <code>&lt;u&gt;</code> 
                                    <code>&lt;strong&gt;</code> <code>&lt;em&gt;</code> <code>&lt;br&gt;</code>
                                    <code>&lt;ul&gt;</code> <code>&lt;ol&gt;</code> <code>&lt;li&gt;</code>
                                    <code>&lt;code&gt;</code>
                                </small>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="form-group mt-4">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                    id="description" name="description" 
                                    rows="3" 
                                    placeholder="Brief description of this topic...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Order -->
                        <div class="form-group mt-4">
                            <label for="order" class="form-label">Order</label>
                            <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                   id="order" name="order" 
                                   value="{{ old('order', 0) }}" 
                                   min="0">
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Actions -->
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Create Topic
                            </button>
                            <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.breadcrumb {
    background-color: #f8f9fa;
    padding: 0.75rem 1rem;
    border-radius: 0.375rem;
}

.breadcrumb-item.active {
    color: #6c757d;
}

.preserve-whitespace {
    white-space: pre-wrap;
    word-wrap: break-word;
    font-family: 'Courier New', Courier, monospace;
    line-height: 1.5;
}
</style>
@endpush

@endsection