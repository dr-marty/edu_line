<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use LINE\Clients\MessagingApi\Api\MessagingApiApi;
use LINE\Clients\MessagingApi\Api\MessagingApiBlobApi;
use LINE\Clients\MessagingApi\Configuration;
use LINE\Clients\MessagingApi\Model\RichMenuRequest;
use LINE\Clients\MessagingApi\Model\RichMenuArea;
use LINE\Clients\MessagingApi\Model\RichMenuBounds;
use LINE\Clients\MessagingApi\Model\RichMenuSize;



class RichMenuController extends Controller
{
    public function index()
    {
        return view('rich-menu.create');
    }

    public function create(Request $request)
    {
        $name = $request->name;
        $image = $request ->image;
        $text_context1 = $request->text_context1;
        $text_context2 = $request->text_context2;
        $text_context3 = $request->text_context3;
        $text_context4 = $request->text_context4;
        $text_context5 = $request->text_context5;
        $text_context6 = $request->text_context6;

        // dd($request);

        $client = new Client();
        $config = new Configuration();
        $config->setAccessToken(config('services.line.message.channel_token'));

        // dd($config);

        $messagingApi = new MessagingApiApi(
            client: $client,
            config: $config
        );
        $messagingApiBlob = new MessagingApiBlobApi(
            client: $client,
            config: $config
        );

        $richMenu = new RichMenuRequest([
            'size' => new RichMenuSize(['width' => 2500, 'height' => 1686]),
            'selected' => false,
            'name' => $name,
            'chatBarText' => 'タブを開く',
            'areas' => [
                new RichMenuArea([
                    'bounds' => new RichMenuBounds(['x' => 0, 'y' => 0, 'width' => 833, 'height' => 843]),
                    'action' => ['type' => 'message', 'text' => $text_context1]
                ]),
                new RichMenuArea([
                    'bounds' => new RichMenuBounds(['x' => 833, 'y' => 0, 'width' => 834, 'height' => 843]),
                    'action' => ['type' => 'message', 'text' => $text_context2]
                ]),
                new RichMenuArea([
                    'bounds' => new RichMenuBounds(['x' => 1667, 'y' => 0, 'width' => 833, 'height' => 843]),
                    'action' => ['type' => 'message', 'text' => $text_context3]
                ]),
                new RichMenuArea([
                    'bounds' => new RichMenuBounds(['x' => 0, 'y' => 843, 'width' => 833, 'height' => 843]),
                    'action' => ['type' => 'message', 'text' => $text_context4]
                ]),
                new RichMenuArea([
                    'bounds' => new RichMenuBounds(['x' => 833, 'y' => 843, 'width' => 834, 'height' => 843]),
                    'action' => ['type' => 'message', 'text' => $text_context5]
                ]),
                new RichMenuArea([
                    'bounds' => new RichMenuBounds(['x' => 1667, 'y' => 843, 'width' => 833, 'height' => 843]),
                    'action' => ['type' => 'message', 'text' => "ai_start"]
                ])
            ]
            
        ]);

        // dd($richMenu);
        // $response = $messagingApi->createRichMenu($richMenu);
        // dd($response);

        try {

            $response = $messagingApi->createRichMenu($richMenu);
            // dd($response);
            $richMenuId = $response->getRichMenuId();

            // dd($response);
            print($richMenuId);

            // 画像のアップロード処理
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->path();
                $contentType = $request->file('image')->getMimeType();

                print($imagePath);
                $imageContent = file_get_contents($imagePath);
                // dd($imageContent);

                // 画像をリッチメニューにアップロード
                $res=$messagingApiBlob->setRichMenuImageWithHttpInfo(
                    $richMenuId,
                    $imageContent,
                    null,
                    [],
                    $contentType
                );
                // dd($res);


                // オプション: リッチメニューをデフォルトとして設定
                $messagingApi->setDefaultRichMenu($richMenuId);

                // dd($res);
                print("リッチメニューを設定しました");

                $defaultRichMenuId = $messagingApi->getDefaultRichMenuId();
                // dd($defaultRichMenuId);

                return redirect()->back()->with('success', 'リッチメニューが作成され、画像がアップロードされました。');
            } else {
                return redirect()->back()->with('error', '画像ファイルが選択されていません。');
            }
        } catch (\Exception $e) {
            \Log::error('リッチメニュー作成エラー: ' . $e->getMessage());
            return redirect()->back()->with('error', 'リッチメニューの作成に失敗しました。エラー: ' . $e->getMessage());
        }
    }
}
