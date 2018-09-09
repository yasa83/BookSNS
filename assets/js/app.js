$(function() {
    $('.js-like').on('click', function() {
        var book_id = $(this).siblings('.book_id').text();
        var user_id = $('#signin-user').text();
        console.log(book_id);   //book_idを取得できているか確認
        console.log(user_id);   //user_idを取得できているか確認

        $.ajax({
            // 送信先、送信するデータなど
            url: 'like.php',
            type: 'POST',
            datatype: 'json',
            data: {
                'book_id': book_id,
                'user_id': user_id,
            }

        })
        .done(function(data) {
            // 成功時の処理
            console.log(data);
        })
        .fail(function(err) {
            // 失敗時の処理
            console.log('error');
        })
    });



});
