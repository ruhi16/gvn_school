@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1>Admin Dashboard</h1>
        <div class="alert alert-info">
            🔒 This page is only accessible to Admins
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5>Total Users</h5>
                <h2>{{ $data['totalUsers'] ?? 0 }}</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5>Teachers</h5>
                <h2>{{ $data['totalTeachers'] ?? 0 }}</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5>Students</h5>
                <h2>{{ $data['totalStudents'] ?? 0 }}</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <h5>Recent Activities</h5>
                <small>{{ count($data['recentActivities'] ?? []) }} new</small>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Quick Actions</div>
            <div class="card-body">
                <a href="{{ route('admin.users') }}" class="btn btn-primary">Manage Users</a>
                <a href="#" class="btn btn-success">Add Teacher</a>
                <a href="#" class="btn btn-warning">Add Student</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Admin Only Features</div>
            <div class="card-body">
                <ul>
                    <li>System Settings</li>
                    <li>User Management</li>
                    <li>Role Assignments</li>
                    <li>View All Data</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection