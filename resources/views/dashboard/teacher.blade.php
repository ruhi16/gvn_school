@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1>Teacher Dashboard</h1>
        <div class="alert alert-success">
            👨‍🏫 This page is only accessible to Teachers
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5>My Students</h5>
                <h2>{{ $data['totalStudents'] ?? 0 }}</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5>Pending Grades</h5>
                <h2>{{ $data['pendingGrades'] ?? 0 }}</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card text-white bg-secondary">
            <div class="card-body">
                <h5>Classes</h5>
                <small>{{ implode(', ', $data['classes'] ?? []) }}</small>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Teacher Actions</div>
            <div class="card-body">
                <a href="{{ route('teacher.grades') }}" class="btn btn-primary">Manage Grades</a>
                <a href="#" class="btn btn-success">Take Attendance</a>
                <a href="#" class="btn btn-info">View Class Schedule</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Teacher Features</div>
            <div class="card-body">
                <ul>
                    <li>Grade Management</li>
                    <li>Attendance Tracking</li>
                    <li>Student Reports</li>
                    <li>Class Materials</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection