@extends("admin.layout.main")

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-10 col-xs-6">
                <div class="box">

                    <!-- /.box-header -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">修改通知</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form role="form" action="/admin/notices/{{$notice->id}}/edit" method="POST">
                            {{csrf_field()}}
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">标题</label>
                                    <input type="text" class="form-control" name="title" value="{{$notice->title}}">
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">内容</label>
                                    <textarea class="form-control" name="content" rows="10" style="resize:none;">{{$notice->content}}</textarea>
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