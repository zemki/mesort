<?php

namespace App\Http\Controllers;

use App\Study;
use App\Token;
use Exception;
use Helper;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getpresettokenimages()
    {
        $data['presetimages'] = Helper::getPresetImages();

        return response($data, 200);
    }

    public function savetoken(Request $request)
    {
        $study = Study::where('id', $request->study)->first();
        try {
            Token::store($request->token, $study, true);
        } catch (Exception $exception) {
            return response('something went wrong', 500);
        }

        return response('token saved successfully', 200);
    }

    public function getTokens(Request $request)
    {
        $token = Study::where('id', $request->study)->first()->tokens->last();
        if (strpos($token['image_path'], 'presets') !== false) {
            $path = $token['image_path'];
            $token['image_path'] = mb_convert_encoding($path, 'HTML-ENTITIES', 'UTF-8');
        } else {
            $path = storage_path('app/' . $token['image_path']);
            $token['image_path'] = decrypt(file_get_contents($path));
        }

        return response($token, 200);
    }

    public function deleteToken(Request $request)
    {
        $token = Token::where('id', $request->token['id'])->first();
        try {
            $token->delete();
        } catch (Exception $exception) {
            return response('A problem was encountered = ' . $exception, 500);
        }
        $tokens = Study::where('id', $request->study)->first()->available_tokens;
        $TokensCount = count($tokens);
        $tokens = $this->elaborateTokens($TokensCount, $tokens);

        return response($tokens, 200);
    }

    /**
     * @return mixed
     */
    private function elaborateTokens(int $TokensCount, $tokens)
    {
        for ($i = 0; $i < $TokensCount; $i++) {
            if (strpos($tokens[$i]['image_path'], 'presets') !== false) {
                $path = $tokens[$i]['image_path'];
                $tokens[$i]['image_path'] = mb_convert_encoding($path, 'HTML-ENTITIES', 'UTF-8');
            } else {
                $path = storage_path('app/' . $tokens[$i]['image_path']);
                $tokens[$i]['image_path'] = decrypt(file_get_contents($path));
            }
        }

        return $tokens;
    }
}
