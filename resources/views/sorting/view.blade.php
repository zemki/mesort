@extends('layouts.checksorting')


@section('content')

    <footer>
        {{$study->name}} - {{$interview->interviewed}}
    </footer>
    <main>
        @if(! $pdf )
            <a href="{{url('/interview/'.$interview->id.'/sorting/'.$sorting_number.'/download')}}" target="_blank">
                <button class="button is-danger noprint" style="display: flex;z-index: 1000;margin: 5px;position: fixed;">{{ __('Export PDF') }}
                </button>
            </a>
        @endif
            <img src="{{$sorting_screenshot}}" id="sorting" class="max-w-full fixed max-h-full"
                 style="max-width: 100%;max-height: 100%;position:fixed;">

    </main>

@endsection

@section('pagespecificstyles')
    <style type="text/css">
        @page {
            margin: 0px 0px;
        }

        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;

            /** Extra personal styles **/
            background-color: #e9e7e3;
            color: black;
            text-align: center;
            line-height: 1.5cm;
        }


        img {
            position: absolute;

            top: 0px;
            left: 0px;
            right: 0px;
            bottom: 2cm;

            overflow: hidden;
            margin: 0;
            padding: 0;
        }

    </style>
@endsection
