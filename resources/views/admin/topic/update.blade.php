@extends("admin.layout.main")

@section('content')
    <style>
        input[type="file"] {
            color: transparent;
        }
    </style>
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-10 col-xs-6">
                <div class="box">

                    <!-- /.box-header -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">修改专题</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form role="form" action="/admin/topics/{{$topic->id}}/edit" method="POST"  enctype="multipart/form-data">
                            {{csrf_field()}}
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">专题名</label>
                                    <input type="text" class="form-control" name="name" value="{{$topic->name}}">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputDes">描述</label>
                                    <input type="text" class="form-control" name="des" value="{{$topic->des}}">
                                </div>
                                <div class="form-group">
                                    <label  for="exampleInputImage">背景</label>
                                    <div>
                                        <input class=" file-loading preview_input topic_image" value="用户名" type="file"
                                               style="width:72px" name="avatar">
                                        @if ($image)
                                            <img class="preview_img" src="{{$image->url}}" alt="" class="img-rounded"
                                             style="border-radius:10%;width:200px;height:200px;margin-top: 20px;">
                                        @else
                                            <img class="preview_img" src="" alt="" class="img-rounded"
                                                 style="border-radius:10%;width:200px;height:200px;margin-top: 20px;">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @include("admin.layout.error")
                        <!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary">修改</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection