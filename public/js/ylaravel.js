// 设置全局
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
})


// 上传图片
// https://www.kancloud.cn/wangfupeng/wangeditor2/123689
// var editor = new wangEditor('content');
//
// if (editor.config) {
//     editor.config.uploadImgUrl = "/posts/image/upload" // 上传的链接
//     editor.config.uploadHeaders = {
//         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//     };
//     editor.config.hideLinkImg = true; // 禁止使用网络图片
//     editor.config.printLog = false; // 禁止打印日志
//
//     // 自定义load事件
//     editor.config.uploadImgFns.onload = function (resultText, xhr) {
//
//         resultText = decodeURI(resultText)
//         // resultText 服务器端返回的text
//         // xhr 是 xmlHttpRequest 对象，IE8、9中不支持
//
//         // 上传图片时，已经将图片的名字存在 editor.uploadImgOriginalName
//         var originalName = editor.uploadImgOriginalName || '';
//
//         // 如果 resultText 是图片的url地址，可以这样插入图片：
//         editor.command(null, 'insertHtml', '<img src="' + resultText + '" alt="' + originalName + '" style="max-width:100%;"/>');
//         // 如果不想要 img 的 max-width 样式，也可以这样插入：
//         // editor.command(null, 'InsertImage', resultText);
//     };
//
//     editor.create();
// }




$('.preview_input').change(function (event) {
    var file = event.currentTarget.files[0]
    var url = window.URL.createObjectURL(file);
    $(event.target).next('.preview_img').attr('src', url)
})


// 点击成功的边框，将其隐藏
$('.success-status').click(function (event) {
    event.target.remove(event.target)
})


// 关注/取消关注
$('.like-button').click(function (event) {
    var target = $(event.target)
    var current_like = target.attr('like-value')
    var user_id = target.attr('like-user')
    if (current_like == 1) {
        // 取消关注
        $.ajax({
            url: "/user/" + user_id + "/unfan",
            method: 'POST',
            dataType: 'json',
            success: function (data) {
                if (data.error != 0) {
                    alert(data.msg)
                    return;
                }
                alert('取消关注成功')
                location.reload()
            }
        })
    } else {
        // 关注
        $.ajax({
            url: "/user/" + user_id + "/fan",
            method: 'POST',
            dataType: 'json',
            success: function (data) {
                if (data.error != 0) {
                    alert(data.msg)
                    return;
                }
                alert('关注成功')
                location.reload()
            }
        })
    }

})

// 查询
$('#search').click(function (event) {
    var target = $('#search-text').val()
    window.location.href = '/posts/search?query=' + target
})



