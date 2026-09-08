@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1>Student Dashboard</h1>
        <div class="alert alert-warning">
            🎓 This page is only accessible to Students
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5>Current GPA</h5>
                <h2>{{ $data['gpa'] ?? 'N/A' }}</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5>Attendance</h5>
                <h2>{{ $data['attendance'] ?? 0 }}%</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5>Subjects</h5>
                <small>{{ implode(', ', $data['subjects'] ?? []) }}</small>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">My Actions</div>
            <div class="card-body">
                <a href="{{ route('student.grades') }}" class="btn btn-primary">View My Grades</a>
                <a href="#" class="btn btn-success">Attendance Report</a>
                <a href="#" class="btn btn-info">Course Materials</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Student Features</div>
            <div class="card-body">
                <ul>
                    <li>View Grades</li>
                    <li>Attendance Report</li>
                    <li>Download Materials</li>
                    <li>Submit Assignments</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection