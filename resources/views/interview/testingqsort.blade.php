@extends('layouts.interview')

@section('content')

    <?php
    // in case of equal names, insert something at the beginning
    // then strip it before printing it.
    $arrayWithValues = array(
        ["", "", "", "", "","worse",1],
        ["", "", "", "", "", "","&noprint",2],
        ["", "", "", "", "", "", "","&&noprint",3],
        ["", "", "", "", "", "", "", "","&&&noprint",4],
        ["", "", "", "", "", "", "", "","&&&&&noprint",5],
        ["", "", "", "", "", "", "","&&&&&&&&noprint",6],
        ["", "", "", "", "","better",7]
    );

    /*
     * qsort||&noprint| | | | | ||
     *
     */
    $test = "&noprint|---blank---|---blank---|---blank---|---blank---|---blank---||&&noprint|---blank---|---blank---|---blank---|---blank---|---blank---|---blank---||";

    // calculate array with more values
    $arrayWithValuesLengths = array();
    foreach ($arrayWithValues as &$a)
    {
        array_push($arrayWithValuesLengths, count($a));
    }
    $maxNumberOfItems = max($arrayWithValuesLengths);
    $basicWidth = 24;
    $containerWidth = 24 * count($arrayWithValues) * 2 * 2;
    $eachContainerHeight = 24 * $maxNumberOfItems * 2;

    // fill each array to fill until it's as big as the biggest <-- to fill the screen.
    foreach ($arrayWithValues as $key => $array)
    {
        while (count($array) < $maxNumberOfItems)
        {
            array_unshift($array, "---blank---");
        }
        $arrayWithValues[$key] = $array;
    }

    ?>



    <div
        class="grid grid-cols-{{count($arrayWithValues)}} gap-0 text-center absolute mx-auto w-auto"
        id="qsort"
        style="left: 50%;transform: translateX(-50%);top:15%;"
    >

        @foreach($arrayWithValues as $key => $array)
            <div class="grid grid-cols-1 gap-0 align-middle" style="height: {{$eachContainerHeight}}px">
                <div class="qsortlg:w-24 qsortmd:w-16 qsortsm:w-12 grid grid-rows-{{$maxNumberOfItems+1}} grid-flow-col gap-0 ">
                    @for ($i = 0; $i < count($array)-2; $i++)
                        @if($array[$i] == "---blank---")
                            <div class="bg-pink-400 opacity-0 qsortlg:w-24 qsortmd:w-16 qsortsm:w-12  qsortlg:h-24 qsortmd:h-16 qsortsm:h-12"></div>

                        @else
                            <div class="bg-gray-900 text-white border border-solid border-white qsortlg:w-24 qsortmd:w-16 qsortsm:w-12 qsortlg:h-24 qsortmd:h-16 qsortsm:h-12 select-none inline-block align-middle justify-center">{{$array[$i]}}</div>
                        @endif
                    @endfor

                    <div class="bg-white text-black qsortlg:w-24 qsortmd:w-16 qsortsm:w-12 qsortlg:h-12 qsortmd:h-8 qsortsm:h-8 select-none inline-block align-middle justify-center">
                            {{$array[count($array)-1]}}
                    </div>
                    <div class="bg-white text-black qsortlg:w-24 qsortmd:w-16 qsortsm:w-12 qsortlg:h-24 qsortmd:h-16 qsortsm:h-12 select-none inline-block align-top justify-center">
                        @if(strpos($array[count($array)-2], '&noprint') === false)
                            {{$array[count($array)-2]}}
                        @endif
                    </div>
                </div>
            </div>

        @endforeach
        {{-- insert last row of labels? --}}

    </div>


@endsection

@section('pagespecificstyles')

    <script>

            (function() {
                setTimeout(function() {
                    let windowSize = window.innerWidth;
                    let widthMultiplier = 24;
                    let top = 6;
                    let mediaQuerySM = windowSize > 639 && windowSize <768;
                    let mediaQueryMD = windowSize >= 768 && windowSize < 1366;
                    let mediaQueryLG = windowSize >= 1366;

                    if(mediaQuerySM) {
                        widthMultiplier = 12;
                        top *= 3;
                        console.log("SM")

                    }
                    else if(mediaQueryMD) {
                        console.log("MD")
                        widthMultiplier = 16;
                        top *= 2;
                    }
                    else if(mediaQueryLG) {
                        console.log("LG?")
                        widthMultiplier = 24;
                    }

                    let arrayCount = <?php echo count($arrayWithValues) ?>;
                    let containerWidth = widthMultiplier * arrayCount * 2 * 2;
                    let element = document.getElementById('qsort');
                    element.style.width = containerWidth.toString() + 'px';
                    element.style.top = top.toString() + '%';
                }, 75);
            })();


    </script>
@endsection
