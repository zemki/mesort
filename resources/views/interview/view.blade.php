@extends('layouts.app')

@section('content')

    <show-interview
        :study="{{ json_encode($study) }}"
        :author="{{ json_encode($author) }}"
        :interview="{{ json_encode($interview) }}"
        :screenshots="{{ json_encode($screenshots) }}"
        :created-tokens="{{ json_encode($createdtokens) }}"
        :questions="{{ json_encode($questions) }}"
        :column-values="{{ json_encode($columnValues) }}"
        :is-qsort-sorting="{{ json_encode($isQSortSorting) }}"
        :extreme-question="{{ json_encode($extremequestion ?? null) }}">

    </show-interview>

@endsection
