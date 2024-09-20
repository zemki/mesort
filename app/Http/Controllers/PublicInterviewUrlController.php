<?php

namespace App\Http\Controllers;

use App\PublicInterviewUrl;
use ArieTimmerman\Laravel\URLShortener\URLShortener;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class PublicInterviewUrlController extends Controller
{
    /**
     * Store an url and create a shortened url.
     *
     * @return JsonResponse
     */
    public function store()
    {
        if (request()->has('study')) {
            $uuid = Str::uuid();
            $PublicInterviewUrl = new PublicInterviewUrl();
            $PublicInterviewUrl->id = $uuid;
            $PublicInterviewUrl->study_id = request()->study;
            $PublicInterviewUrl->created_at = Carbon::now()->toDateTimeString('minutes');
            $PublicInterviewUrl->save();
            $url = (string) URLShortener::shorten(url('/interviews/new?study=' . request()->study . '&interviewed=' . (request()->name ?? '') . '&t=' . $uuid));
            $PublicInterviewUrl->short_url_id = Carbon::now()->toDateTimeString('minutes');

            return response()->json(['message' => 'Url Created!.', 'url' => $url], 200);
        } else {
            return response()->json(['message' => 'Please set a study.'], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     */
    public function destroy()
    {
        $url = PublicInterviewUrl::where('id', '=', request()->id)->first();
        $url->delete();
        $delete_shorturl = DB::table('art_urls')
            ->where('id', '=', request()->url_id)
            ->delete();

        return response('Url deleted', 200);
    }
}
