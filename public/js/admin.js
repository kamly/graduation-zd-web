// 设置全局
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
})

// 点击成功的边框，将其隐藏
$('.success-status').click(function (event) {
    event.target.remove(event.target)
})


// 文章状态
$('.post-audit').click(function (event) {
    target = $(event.target)
    var post_id = target.attr('post-id')
    var status = target.attr('post-action-status')
    $.ajax({
        url: "/admin/posts/" + post_id + "/status",
        method: "POST",
        data: {"status": status},
        dataType: 'json',
        success: function (data) {
            if (data.error != 0) {
                alert(data.msg)
                return
            }
            target.parent().parent().remove()
        }

    })
})

$('.resource-delete').click(function (event) {
    if (confirm('确定要删除吗？') == false) {
        return;
    }
    var target = $(event.target)
    event.preventDefault();
    var url = $(target).attr('delete-url')
    $.ajax({
        url: url,
        method: "POST",
        data: {"_method": 'DELETE'},
        dataType: 'json',
        success: function (data) {
            if (data.error != 0) {
                alert(data.msg)
                return
            }
            alert('删除成功');
            window.location.reload()
        }
    })
})


$('.topic_image').change(function (event) {
    var file = event.currentTarget.files[0]
    var url = window.URL.createObjectURL(file);
    $(event.target).next('.preview_img').attr('src', url)
})
