@extends('layout')

@section('content')
<h1>All Students</h1>
<ul>
    @foreach( $students as $student)
        <li>
            {{  $student -> fname }} {{ $student -> lname }} 
            <a href="{{ route('students.edit', $student -> id) }}">Edit</a>
            <form action="{{ route('students.destroy', $student -> id) }}" method="POST">
                {{ csrf_field() }}
                @method('DELETE')
                <input type="submit" value="Delete">
           </form>
        </li>
    @endforeach
</ul>
@endsection


