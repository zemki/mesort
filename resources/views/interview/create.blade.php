@extends('layouts.interview')

@section('content')

    <new-interview ref="thenewinterview" :questions="JSON.parse('{{ json_encode($questions,JSON_HEX_APOS) }}')"
                   :study="{{$study}}" interviewed="{{$interviewed}}" :sorting="{{json_encode($sorting)}}"
                   :gotos="{{$gotos}}" :classifiers="{{ json_encode($classifiers) }}"></new-interview>

@endsection
