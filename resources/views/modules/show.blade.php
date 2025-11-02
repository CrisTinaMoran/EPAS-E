@extends('layouts.app')

@section('title', $module->module_number . ' - ' . $module->module_name . ' - EPAS-E')

@section('content')

<!-- Module Header - Sticky Secondary Nav -->
<header class="module-header">
    <div class="module-title">
        <h1>{{ $module->module_number }}: {{ $module->module_name }}</h1>
        <p>{{ $module->qualification_title }}</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('modules.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back to Modules
        </a>
    </div>
</header>

<button class="btn btn-primary mobile-toc-toggle" id="mobileTocToggle">
    <i class="fas fa-list"></i>
</button>

<div class="module-container">
    <!-- Main Content -->
    <main class="module-main">
    <div class="content-wrapper">
        <!-- Dynamic Content Area -->
        <div id="dynamic-content">
            <!-- Overview Section (Default) -->
            <section id="overview" class="content-section section-transition active-section">
                <div class="section-header">
                    <h2>Module Overview</h2>
                    <p>Get started with {{ $module->module_name }}</p>
                </div>

                <!-- Module Details -->
                <div class="info-card">
                    <h3><i class="fas fa-info-circle"></i> Module Details</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <strong>Sector:</strong> {{ $module->sector }}
                        </div>
                        <div>
                            <strong>Module Number:</strong> {{ $module->module_number }}
                        </div>
                        <div>
                            <strong>Qualification Title:</strong> {{ $module->qualification_title }}
                        </div>
                        <div>
                            <strong>Unit of Competency:</strong> {{ $module->unit_of_competency }}
                        </div>
                    </div>
                </div>

                <!-- Introduction Content -->
                @if($module->learning_outcomes || $module->how_to_use_cblm || $module->introduction)
                <div class="info-card">
                    <h3><i class="fas fa-book-open"></i> Module Introduction</h3>
                    
                    @if($module->learning_outcomes)
                    <div class="content-display">
                        <h4>Learning Outcomes</h4>
                        {!! nl2br(e($module->learning_outcomes)) !!}
                    </div>
                    @endif

                    @if($module->how_to_use_cblm)
                    <div class="content-display">
                        <h4>How to Use This CBLM</h4>
                        {!! nl2br(e($module->how_to_use_cblm)) !!}
                    </div>
                    @endif

                    @if($module->introduction)
                    <div class="content-display">
                        <h4>Introduction</h4>
                        {!! nl2br(e($module->introduction)) !!}
                    </div>
                    @endif
                </div>
                @endif
            </section>
        </div>
    </div>
</main>

    <!-- Right Sidebar -->
    <aside class="module-sidebar" id="moduleTocSidebar">
        <!-- Progress Section -->
        <div class="progress-container">
            <div class="progress-circle">
                <svg viewBox="0 0 100 100">
                    <circle class="progress-bg" cx="50" cy="50" r="45"/>
                    <circle class="progress-fill" cx="50" cy="50" r="45" id="progressCircle"/>
                </svg>
                <div class="progress-text" id="progressText">0%</div>
            </div>
            <div>
                <div style="font-weight: 600; color: var(--primary);">Overall Progress</div>
                <small style="color: var(--gray);">{{ $module->informationSheets->count() }} information sheets</small>
            </div>
        </div>

        <!-- Current Section -->
        <div class="info-card" style="margin-bottom: 1.5rem;">
            <div style="font-size: 0.9rem; font-weight: 600; color: var(--primary); margin-bottom: 0.25rem;">
                CURRENT SECTION
            </div>
            <div style="font-weight: 600; color: var(--primary);" id="currentSection">
                Module Overview
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div style="display: flex; gap: 0.5rem; margin-bottom: 1.5rem;">
            <button class="btn btn-outline" id="sidebar-prev" style="flex: 1;" disabled>
                <i class="fas fa-arrow-left"></i> Previous
            </button>
            <button class="btn btn-primary" id="sidebar-next" style="flex: 1;">
                Next <i class="fas fa-arrow-right"></i>
            </button>
        </div>

        <!-- Module Actions -->
        <div class="module-actions">
            <a href="#" class="action-btn" id="download-pdf">
                <i class="fas fa-download"></i> Download Current PDF
            </a>
        </div>

        <!-- Table of Contents -->
<div class="toc-section">
    <div class="toc-title">
        <i class="fas fa-list"></i> Table of Contents
    </div>
    
    <!-- Overview Item -->
    <div class="toc-item">
        <div class="toc-link active" data-content="overview">
            <i class="fas fa-home toc-icon"></i>
            Module Overview
        </div>
    </div>

    <!-- Information Sheets with Dropdown -->
            @foreach($module->informationSheets as $infoSheet)
                    <div class="toc-item information-sheet-item">
                        <div class="toc-link sheet-header" data-sheet-id="{{ $infoSheet->id }}">
                            <i class="fas fa-chevron-down toc-icon toggle-icon"></i>
                            <div class="sheet-title">
                                <div class="sheet-main-title">Information Sheet {{ $infoSheet->sheet_number }}</div>
                                <div class="sheet-subtitle">{{ $infoSheet->title }}</div>
                            </div>
                        </div>
                        
                        <!-- Topics Dropdown -->
                                <div class="topics-dropdown">
                    @if($infoSheet->topics && $infoSheet->topics->count() > 0)
                        @foreach($infoSheet->topics as $topic)
                        <div class="topic-item" data-topic-id="{{ $topic->id }}">
                            <i class="fas fa-file-alt topic-icon"></i>
                            <span class="topic-title">{{ $topic->title }}</span>
                        </div>
                        @endforeach
                    @else
                        <!-- For Information Sheet 1.1, use content types -->
                        @if($infoSheet->sheet_number == '1.1')
                        <div class="topic-item" data-content-type="introduction">
                            <i class="fas fa-file-alt topic-icon"></i>
                            <span class="topic-title">Introduction to Electronics and Electricity</span>
                        </div>
                        <div class="topic-item" data-content-type="electric-history">
                            <i class="fas fa-file-alt topic-icon"></i>
                            <span class="topic-title">Electric History</span>
                        </div>
                        <div class="topic-item" data-content-type="static-electricity">
                            <i class="fas fa-file-alt topic-icon"></i>
                            <span class="topic-title">Static Electricity</span>
                        </div>
                        <div class="topic-item" data-content-type="free-electrons">
                            <i class="fas fa-file-alt topic-icon"></i>
                            <span class="topic-title">Free Electrons, Introduction to Sources of Electricity</span>
                        </div>
                        <div class="topic-item" data-content-type="alternative-energy">
                            <i class="fas fa-file-alt topic-icon"></i>
                            <span class="topic-title">Alternative Energy</span>
                        </div>
                        <div class="topic-item" data-content-type="electric-energy">
                            <i class="fas fa-file-alt topic-icon"></i>
                            <span class="topic-title">Types of Electric Energy and Current</span>
                        </div>
                        <div class="topic-item" data-content-type="materials">
                            <i class="fas fa-file-alt topic-icon"></i>
                            <span class="topic-title">Types of Materials</span>
                        </div>
                        <div class="topic-item self-check-item" data-content-type="self-check">
                            <i class="fas fa-question-circle topic-icon"></i>
                            <span class="topic-title">Self Check No. 1.1.1</span>
                        </div>
                        @endif
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </aside>
</div>

<!-- Hidden data container for JavaScript -->
<div id="module-data"
     data-total-sections="{{ $module->informationSheets->count() + 1 }}"
     data-info-sheets="{{ json_encode($module->informationSheets->pluck('id')) }}"
     data-module-id="{{ $module->id }}"
     data-user-role="{{ auth()->user()->role }}"
     data-csrf-token="{{ csrf_token() }}"
     style="display: none;">
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/modules.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/modules/show.js') }}"></script>
@endpush