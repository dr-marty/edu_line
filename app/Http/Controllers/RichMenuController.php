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
use LINE\Clients\MessagingApi\Model\CreateRichMenuAliasRequest;



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

        $aliasIdA = 'richmenu-alias-a-' . time();
        $aliasIdB = 'richmenu-alias-b-' . time();

        $richMenu_A = new RichMenuRequest([
            'size' => new RichMenuSize(['width' => 2500, 'height' => 1686]),
            'selected' => false,
            'name' => $name,
            'chatBarText' => 'タブを開く',
            'areas' => [
                //1列目
                new RichMenuArea([
                    'bounds' => new RichMenuBounds(['x' => 833, 'y' => 0, 'width' => 834, 'height' => 282]),
                    'action' => [
                        'type' => 'richmenuswitch',
                        "richMenuAliasId"=> $aliasIdB,
                        "data"=> "richmenu-changed-to-b"
                        ]
                ])
            ]
        ]);
        $richMenu_B = new RichMenuRequest([
            'size' => new RichMenuSize(['width' => 2500, 'height' => 1686]),
            'selected' => false,
            'name' => $name,
            'chatBarText' => 'タブを開く',
            'areas' => [
                //1列目
                new RichMenuArea([
                    'bounds' => new RichMenuBounds(['x' => 0, 'y' => 0, 'width' => 833, 'height' => 282]),
                    'action' => [
                        'type' => 'richmenuswitch',
                        "richMenuAliasId"=> $aliasIdA,
                        "data"=> "richmenu-changed-to-a"
                        ]
                ]),
                new RichMenuArea([
                    'bounds' => new RichMenuBounds(['x' => 833, 'y' => 0, 'width' => 834, 'height' => 282]),
                    'action' => ['type' => 'message', 'text' => "tab2"]
                ]),
                new RichMenuArea([
                    'bounds' => new RichMenuBounds(['x' => 1667, 'y' => 0, 'width' => 833, 'height' => 282]),
                    'action' => ['type' => 'message', 'text' => "tab3"]
                ]),
                //2列目
                new RichMenuArea([
                    'bounds' => new RichMenuBounds(['x' => 0, 'y' => 282, 'width' => 833, 'height' => 702]),
                    'action' => ['type' => 'message', 'text' => $text_context1]
                ]),
                new RichMenuArea([
                    'bounds' => new RichMenuBounds(['x' => 833, 'y' => 282, 'width' => 834, 'height' => 702]),
                    'action' => ['type' => 'message', 'text' => $text_context2]
                ]),
                new RichMenuArea([
                    'bounds' => new RichMenuBounds(['x' => 1667, 'y' => 282, 'width' => 833, 'height' => 702]),
                    'action' => ['type' => 'message', 'text' => $text_context3]
                ]),
                //3列目
                new RichMenuArea([
                    'bounds' => new RichMenuBounds(['x' => 0, 'y' => 984, 'width' => 833, 'height' => 702]),
                    'action' => ['type' => 'message', 'text' => $text_context4]
                ]),
                new RichMenuArea([
                    'bounds' => new RichMenuBounds(['x' => 833, 'y' => 984, 'width' => 834, 'height' => 702]),
                    'action' => ['type' => 'message', 'text' => $text_context5]
                ]),
                new RichMenuArea([
                    'bounds' => new RichMenuBounds(['x' => 1667, 'y' => 984, 'width' => 833, 'height' => 702]),
                    'action' => ['type' => 'message', 'text' => "ai_start"]
                ])
            ]
            
        ]);

        // dd($richMenu_B);
        // $response = $messagingApi->createRichMenu($richMenu);
        // dd($response);

        try {
            $response_A = $messagingApi->createRichMenu($richMenu_A);
            $response_B = $messagingApi->createRichMenu($richMenu_B);

            $richMenuA_Id = $response_A->getRichMenuId();
            $richMenuB_Id = $response_B->getRichMenuId();
            // dd($richMenuB_Id);
            // dd($response);
            print($richMenuA_Id);
            print($richMenuB_Id);



            // 画像のアップロード処理
            if ($request->hasFile('imageA') && $request->hasFile('imageB')) {

                $imagePath_A = $request->file('imageA')->path();
                $imagePath_B = $request->file('imageB')->path();
                // dd($imagePath_A);
                $contentType = $request->file('imageB')->getMimeType();

                print($imagePath_B);
                $imageContent_A = file_get_contents($imagePath_A);
                $imageContent_B = file_get_contents($imagePath_B);
                // dd($imageContent_B);

            //    try {
            //     $res_A=$messagingApiBlob->setRichMenuImageWithHttpInfo(
            //         $richMenuA_Id,
            //         $imageContent_A,
            //         null,
            //         [],
            //         $contentType
            //     );


                // 画像をリッチメニューにアップロード
                $res_A=$messagingApiBlob->setRichMenuImageWithHttpInfo(
                    $richMenuA_Id,
                    $imageContent_A,
                    null,
                    [],
                    $contentType
                );
                $res_B=$messagingApiBlob->setRichMenuImageWithHttpInfo(
                    $richMenuB_Id,
                    $imageContent_B,
                    null,
                    [],
                    $contentType
                );
                // dd($res_B);
                // $aliasIdA = 'richmenu-alias-a-' . time();
                // $aliasIdB = 'richmenu-alias-b-' . time();

                $createAliasRequestA = new CreateRichMenuAliasRequest([
                    'richMenuId' => $richMenuA_Id,
                    'richMenuAliasId' => $aliasIdA
                ]);
    
                $createAliasRequestB = new CreateRichMenuAliasRequest([
                    'richMenuId' => $richMenuB_Id,
                    'richMenuAliasId' => $aliasIdB
                ]);
    

                // dd($createAliasRequestA);
    
                try {
                    $aliasA = $messagingApi->createRichMenuAlias($createAliasRequestA);
               } catch (\Exception $e) {
                    \Log::error('リッチメニューエイリアス作成エラー: ' . $e->getMessage());
                    dd($e->getMessage()); // エラーメッセージを表示
               }
               try {
                $aliasB = $messagingApi->createRichMenuAlias($createAliasRequestB);
           } catch (\Exception $e) {
                \Log::error('リッチメニューエイリアス作成エラー: ' . $e->getMessage());
                dd($e->getMessage()); // エラーメッセージを表示
           }
    
                // $aliasA = $messagingApi->createRichMenuAlias($createAliasRequestA);
                // $aliasB = $messagingApi->createRichMenuAlias($createAliasRequestB);
    
                // dd($aliasA);
                // オプション: リッチメニューをデフォルトとして設定
                $result =$messagingApi->setDefaultRichMenu($richMenuA_Id);

                // dd($result);
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
