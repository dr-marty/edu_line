<!DOCTYPE html>
<html>
    <h1>Richmenu create画面です</h1>
    <form action="{{ url('/rich-menu/create') }}" method="POST" enctype="multipart/form-data">
    @csrf
        <div>
            <label for="name">リッチメニュー名:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div>
            <a>[エリア1]</a>
            <!-- <label for="action_select1">アクションを選択してください</label>
            <select id="action_select1" name="action_select1">
                <option value="message">メッセージ</option>
                <option value="uri">URI</option>
                <option value="postback">ポストバック</option>

            </select> -->
            <br>
            <label for="text_context1">送信内容:</label>
            <input type="text" id="text_context1" name="text_context1" required>
            <!-- <br>
            <label for="area_1_x">X座標:</label>
            <input type="number" id="area_1_x" name="area_1_x" required>
            <label for="area_1_y">Y座標:</label>
            <input type="number" id="area_1_y" name="area_1_y" required>
            <label for="area_1_width">幅:</label>
            <input type="number" id="area_1_width" name="area_1_width" required>
            <label for="area_1_height">高さ:</label>
            <input type="number" id="area_1_height" name="area_1_height" required> -->
        </div>
        <div>
            <label for="text_context2">[エリア2]送信内容:</label>
            <input type="text_context2" id="text_context2" name="text_context2" required>
        </div>
        <div>
            <label for="text_context3">[エリア3]送信内容:</label>
            <input type="text_context3" id="text_context3" name="text_context3" required>
        </div>
        <div>
            <label for="text_context4">[エリア4]送信内容:</label>
            <input type="text_context4" id="text_context4" name="text_context4" required>
        </div>
        <div>
            <label for="text_context5">[エリア5]送信内容:</label>
            <input type="text_context5" id="text_context5" name="text_context5" required>
        </div>
        <div>
            <label for="text_context6">[エリア6]送信内容:</label>
            <input type="text_context6" id="text_context6" name="text_context6" required>
        </div>
        <div>
            <label for="imageA">画像A:</label>
            <input type="file" id="imageA" name="imageA" required>
        </div>
        <div>
            <label for="imageB">画像B:</label>
            <input type="file" id="imageB" name="imageB" required>
        </div>
        <button type="submit">作成</button>
    </form>

</html>