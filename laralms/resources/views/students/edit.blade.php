@extends('layout')
@section('content')
<h1>Edit Student</h1>
    @if ($errors -> any())
        @foreach ($errors -> all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    @endif
    <form action="{{ route('students.update', $student -> id) }}" method="PUT">
        {{ csrf_field() }}
        @method('PUT')
        <input type="text" name="fname" placeholder="fname" 
            value="{{ old('fname') ?? $student -> fname }}">
        <input type="text" name="lname" placeholder="lname" 
            value="{{ old('lname') ?? $student -> lname }}">
        <input type="email" name="email" placeholder="email" 
            value="{{ old('email') ?? $student -> email }}">
        <input type="submit" value="Create">
    </form>
@endsection