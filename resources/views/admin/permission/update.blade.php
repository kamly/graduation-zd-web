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
                            <h3 class="box-title">修改权限</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form role="form" action="/admin/permissions/{{$permission->id}}/edit" method="POST">
                            {{csrf_field()}}
                            <div class="box-body">
                                <div class="form-group">
                                    <label>权限名</label>
                                    <input type="text" class="form-control" name="name" disabled="true"
                                           value="{{$permission->name}}">
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="form-group">
                                    <label>描述</label>
                                    <input type="text" class="form-control" name="description"
                                           value="{{$permission->description}}">
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