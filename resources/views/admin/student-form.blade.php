@extends('layouts.app')
@section('content')
<livewire:student-db-form :student-id="$studentDb->id ?? null" />
@endsection