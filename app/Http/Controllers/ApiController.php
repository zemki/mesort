<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Study;
use App\Token;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    /**
     * Get preset token images for interview.
     */
    public function getPresetTokenImages(): JsonResponse
    {
        $data['presetimages'] = Helper::getPresetImages();

        return response()->json($data);
    }

    /**
     * Save a new token to study.
     */
    public function saveToken(Request $request): JsonResponse
    {
        $request->validate([
            'study' => 'required|integer|exists:studies,id',
            'token' => 'required',
        ]);

        $study = Study::findOrFail($request->study);
        try {
            Token::store($request->token, $study, true);
        } catch (Exception $exception) {
            Log::error('Token save failed', [
                'study_id' => $request->study,
                'error' => $exception->getMessage(),
            ]);

            return response()->json(['error' => 'Failed to save token'], 500);
        }

        return response()->json(['message' => 'Token saved successfully']);
    }

    /**
     * Get latest token for study with processed image path.
     */
    public function getTokens(Request $request): JsonResponse
    {
        $request->validate([
            'study' => 'required|integer|exists:studies,id',
        ]);

        $study = Study::findOrFail($request->study);
        $token = $study->tokens->last();

        if (! $token) {
            return response()->json(['error' => 'No tokens found for this study'], 404);
        }

        $token['image_path'] = $this->processTokenImagePath($token['image_path']);

        return response()->json($token);
    }

    /**
     * Delete a token and return updated token list.
     */
    public function deleteToken(Request $request): JsonResponse
    {
        $request->validate([
            'study' => 'required|integer|exists:studies,id',
            'token.id' => 'required|integer|exists:tokens,id',
        ]);

        try {
            $token = Token::findOrFail($request->token['id']);
            $token->delete();

            $study = Study::findOrFail($request->study);
            $tokens = $study->available_tokens;
            $tokensCount = count($tokens);
            $tokens = $this->processTokensList($tokensCount, $tokens);

            return response()->json($tokens);
        } catch (Exception $exception) {
            Log::error('Token deletion failed', [
                'token_id' => $request->token['id'] ?? null,
                'study_id' => $request->study ?? null,
                'error' => $exception->getMessage(),
            ]);

            return response()->json(['error' => 'Failed to delete token'], 500);
        }
    }

    /**
     * Process image path for a single token.
     */
    private function processTokenImagePath(string $imagePath): string
    {
        if (str_contains($imagePath, 'presets')) {
            return mb_convert_encoding($imagePath, 'HTML-ENTITIES', 'UTF-8');
        }

        try {
            $path = storage_path('app/' . $imagePath);

            return decrypt(file_get_contents($path));
        } catch (Exception $exception) {
            Log::error('Failed to process token image', [
                'image_path' => $imagePath,
                'error' => $exception->getMessage(),
            ]);

            return $imagePath; // Return original path as fallback
        }
    }

    /**
     * Process image paths for multiple tokens.
     */
    private function processTokensList(int $tokensCount, array $tokens): array
    {
        for ($i = 0; $i < $tokensCount; $i++) {
            $tokens[$i]['image_path'] = $this->processTokenImagePath($tokens[$i]['image_path']);
        }

        return $tokens;
    }
}
