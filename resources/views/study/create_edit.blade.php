@extends('layouts.app')

@section('content')

    <new-study
        @if(isset($study))
        :studydata="{{$study}}"
        @endif
        :isedit="{{$isedit}}" :preset="{{ json_encode($presetimages) }}"
        :classifiers="{{ json_encode($classifiers,true) }}"></new-study>
@endsection
