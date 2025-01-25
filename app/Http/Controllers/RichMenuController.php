<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RichMenuController extends Controller
{
    public function createRichMenu(Request $request)
    {
        $channelAccessToken = config('services.line.message.channel_token');

        // リッチメニューの設定
        $richMenuData = [
            'size' => [
                'width' => 2500,
                'height' => 1686,
            ],
            'selected' => true,
            'name' => 'My Rich Menu',
            'chatBarText' => 'メニュー表示',
            'areas' => [
                [
                    'bounds' => [
                        'x' => 0,
                        'y' => 0,
                        'width' => 1666,
                        'height' => 1686,
                    ],
                    'action' => [
                        'type' => 'message',
                        'text' => 'Hello, World!',
                    ],
                ],
                [
                    'bounds' => [
                        'x' => 1667,
                        'y' => 0,
                        'width' => 834,
                        'height' => 843,
                    ],
                    'action' => [
                        'type' => 'uri',
                        'label' => 'Learnal公式HP',
                        'uri' => 'https://www.learnal.website/'
                    ],
                ],
            ],
        ];

        // リッチメニュー作成リクエスト
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $channelAccessToken,
            'Content-Type' => 'application/json',
        ])->post('https://api.line.me/v2/bot/richmenu', $richMenuData);

        if ($response->successful()) {
            return response()->json(['richMenuId' => $response->json('richMenuId')]);
        } else {
            return response()->json(['error' => $response->getBody()], $response->status());
        }
    }

    public function uploadRichMenuImage(Request $request, $richMenuId)
    {
        // アップロードされたファイル情報をログに記録
        \Log::info($request->file('image'));
    
        // ファイルが存在するか確認
        if (!$request->hasFile('image')) {
            return response()->json(['error' => 'No file uploaded.'], 400);
        }
    
        $channelAccessToken = config('services.line.message.channel_token');
        $imagePath = $request->file('image')->getRealPath(); // 一時ファイルのパスを取得
    
        // ここで$imagePathが正しいか確認する
        \Log::info("Image path: " . $imagePath);
    
        // リッチメニュー画像アップロードリクエスト
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $channelAccessToken,
            'Content-Type' => $request->file('image')->getClientMimeType(), // 動的にMIMEタイプを設定
        ])->attach(
            'imageFile', file_get_contents($imagePath), basename($imagePath)
        )->post("https://api-data.line.me/v2/bot/richmenu/{$richMenuId}/content");
    
        if ($response->successful()) {
            return response()->json(['message' => 'Image uploaded successfully']);
        } else {
            return response()->json(['error' => $response->getBody()], $response->status());
        }
    }
    
    

    public function setDefaultRichMenu($richMenuId)
    {
        $channelAccessToken = config('services.line.message.channel_token');

        // デフォルトリッチメニュー設定リクエスト
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $channelAccessToken,
        ])->post("https://api.line.me/v2/bot/user/all/richmenu/{$richMenuId}");

        if ($response->successful()) {
            return response()->json(['message' => 'Default rich menu set successfully']);
        } else {
            return response()->json(['error' => $response->getBody()], $response->status());
        }
    }
}
