<?php
require_once './function.php';

// 获取学生信息列表
$json = do_post_request("http://localhost:9091/get_process_list");
$list = json_decode($json, true);
?>

<!-- 渲染网页 -->
<!DOCTYPE html>
<html>
    <head>
        <title>操作系统</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="css/bootstrap.css">
		<script language="JavaScript">
			// function myrefresh()
			// {
			// 	window.location.reload();
			// }
			// setTimeout('myrefresh()',5000); //指定5秒刷新一次
			</script>
    </head>

    <body>
        <div class="container">
            <h3>操作系统大作业</h3>
            <div class="row">
                <!-- <a class="btn btn-success btn-sm" id="temp">添加</a> -->
                <button class="btn btn-success btn-sm" data-toggle="modal" data-target=".bs-example-modal-sm">
        添加
        </button>
				<a class="btn btn-success btn-sm" id="byebye">结束所有</a>
            </div>
            <br>
            <div class="row">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>进程名</th>
                                <th>进程id</th>
                                <th>剩余时间</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($list as $item) {
                            //判断状态显示按钮
								if($item['status'] == 'RUNNING'){
									echo ' <tr>
									<td>' . $item['name'] . '</td>
									<td>' . $item['id'] . '</td>
									<td>' . $item['remainTime'] . '</td>
									<td style="color:Lime">' . $item['status'] . '</td>
									<td>
										<a href="#" style="color:blue" onclick="process_wait(' . $item['id'] . ')">等待</a>&nbsp;&nbsp;
										<a href="#" style="color:Turquoise" onclick="process_ready(' . $item['id'] . ')">就绪</a>&nbsp;&nbsp;
										<a href="#" style="color:red" onclick="process_remove(' . $item['id'] . ')">删除</a>
									</td>
								</tr>';
								}else if($item['status'] == 'READY'){
									echo ' <tr>
									<td>' . $item['name'] . '</td>
									<td>' . $item['id'] . '</td>
									<td>' . $item['remainTime'] . '</td>
									<td style="color:Turquoise">' . $item['status'] . '</td>
									<td>
										<a href="#" style="color:blue" onclick="process_wait(' . $item['id'] . ')">等待</a>&nbsp;&nbsp;
										<a href="#" style="color:Lime" onclick="process_run(' . $item['id'] . ')">运行</a>&nbsp;&nbsp;
										<a href="#" style="color:red" onclick="process_remove(' . $item['id'] . ')">删除</a>
									</td>
								</tr>';
								}else{
									echo ' <tr>
									<td>' . $item['name'] . '</td>
									<td>' . $item['id'] . '</td>
									<td>' . $item['remainTime'] . '</td>
									<td style="color:blue">' . $item['status'] . '</td>
									<td>
										<a href="#" style="color:Turquoise" onclick="process_ready(' . $item['id'] . ')">就绪</a>&nbsp;&nbsp;
										<a href="#" style="color:red" onclick="process_remove(' . $item['id'] . ')">删除</a>
									</td>
								</tr>';
								}
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <script src="js/jquery-1.12.0.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script>

			function process_run(id) {
                $.get("http://localhost:9091/run_process",
                        {id: id},
                        function () {
                            window.location.reload();
                        },
                        "text");
            }

			function process_ready(id) {
                $.get("http://localhost:9091/ready_process",
                        {id: id},
                        function () {
                            window.location.reload();
                        },
                        "text");
            }

			function process_wait(id) {
                $.get("http://localhost:9091/wait_process",
                        {id: id},
                        function () {
                            window.location.reload();
                        },
                        "text");
            }

            function process_remove(id) {
                $.get("http://localhost:9091/remove_process",
                        {id: id},
                        function () {
                            window.location.reload();
                        },
                        "text");
            }
        </script>
        <script>
            $(function () {
                $("#ok").click(function () {
                    var name = $('#name').val();
                    var time = $('#time').val();

                    $.post(
                            "http://localhost:9091/add_process",
                            {
                                name: name,
								                time:time
                            },
                            function (data) {
                                window.location.reload();
                            },
                            "text");
                });
            });

			$(function () {
                $("#byebye").click(function () {
                    $.post(
                            "http://localhost:9091/delete_all_process",
                            {},
                            function () {
                                window.location.reload();
                            },
                            "text");
                });
            });


        </script>
        <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">请输入进程信息</h4>
            </div>
            <div class="modal-body">
 <input type="text" id="name" class="form-control" placeholder="请输入进程名">
   <br/>
                 <input type="text" id="time" class="form-control" placeholder="请输入运行时间">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" id="ok" class="btn btn-primary" >确定</button>
            </div>
        </div>
    </div>
</div>
    </body>
</html>
