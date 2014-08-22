<script type="text/javascript">
    function UserController($scope, $http) {
        $http.get('<?php echo Yii::app()->createAbsoluteUrl('bbbackend/user/listUser') ?>').success(function(data) {
            $scope.users = data;
        });
        $scope.removeRow = function(user_id) {
            $http({
                method: 'POST',
                url: '<?php echo Yii::app()->createAbsoluteUrl('bbbackend/user/deleteUser') ?>',
                data:$.param(user_id),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}  // set the headers so angular passing info as form data (not request payload)
            })
                    .success(function(data) {
                        console.log(data);
                        if (!data.success) {
                            // if not successful, bind errors to error variables

                        } else {
                            // if successful, bind success message to message
                            $scope.message = data.message;
                        }
                    });
            var index = -1;
            var userArr = eval($scope.users);
            for (var i = 0; i < userArr.length; i++) {
                if (userArr[i].user_id === user_id) {
                    index = i;
                    break;
                }
            }
            if (index === -1) {
                alert("Something gone wrong");
            }
            $scope.users.splice(index, 1);
        };

    }
</script>

<script>
    // create angular controller and pass in $scope and $http
    function formEditController($scope, $http) {

        // create a blank object to hold our form information
        // $scope will allow this to pass between controller and view
        $scope.formData = $scope.user; // dong nay THIS LINE :V
        // process the form
        $scope.processForm = function() {
            // console.log($scope.formData);

            $http({
                method: 'POST',
                url: '<?php echo Yii::app()->createAbsoluteUrl('bbbackend/user/editUser') ?>',
                data: $.param($scope.formData), // pass in data as strings
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}  // set the headers so angular passing info as form data (not request payload)
            })
                    .success(function(data) {
                        console.log(data);
                        if (!data.success) {
                            // if not successful, bind errors to error variables

                        } else {
                            // if successful, bind success message to message
                            $scope.message = data.message;
                        }
                    });
        };
    }
</script>

<h1><center>Quản lý người dùng</center></h1>
<div class="container-fluid" ng-controller="UserController">
    <div class="container">
        <table class="table table-bordered" >
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID Facebook</th>
                    <th>Email</th>
                    <th>Tên hiển thị</th>
                    <th>Ảnh đại diện</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="user in users">
                    <td>{{user.user_id}}</td>
                    <td>{{user.user_id_fb}}</td>
                    <td>{{user.username}}</td>
                    <td>{{user.user_real_name}}</td>
                    <td ng-if="user.user_active == 1">Đã kích hoạt</td>
                    <td ng-if="user.user_active == 0">Chưa kích hoạt (đang bị phạt)</td>
                    <td>
                        <img ng-src="{{user.user_avatar}}" height="120" width="120" class="img-circle"/>
                    </td>
                    <td>
                        <a data-toggle="modal" data-target="#view-{{user.user_id}}">
                            <i class="glyphicon glyphicon-eye-open"></i>
                        </a>
                        <div class="modal fade" id="view-{{user.user_id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Chi tiết người dùng: {{user.user_real_name}}</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="portlet">                                                 
                                                    <div class="form-body">
                                                        <div class="form-group">
                                                            <label for=" ">ID người dùng:</label>
                                                            <span>{{user.user_id}}</span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for=" ">Email:</label>
                                                            <span>{{user.username}}</span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for=" ">Tên hiển thị:</label>
                                                            <span>{{user.user_real_name}}</span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for=" ">Avatar:</label></br>
                                                            <img class="img-circle" width = "120" height = "120" ng-src="{{user.user_avatar}}"/>
                                                        </div>  
                                                        <div class="form-group">
                                                            <label for=" ">Ngày tháng năm sinh:</label>
                                                            <span>{{user.user_dob}}</span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for=" ">Quê quán:</label>
                                                            <span>{{user.user_hometown}}</span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for=" ">Trích dẫn:</label>
                                                            <span>{{user.user_qoutes}}</span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for=" ">Active:</label>
                                                            <span ng-if="user.user_active == 1">Đã kích hoạt</span>
                                                            <span ng-if="user.user_active == 0">Chưa kích hoạt (đang bị phạt)</span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for=" ">Ngày tham gia:</label>
                                                            <span>{{user.user_date_attend}}</span>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" data-dismiss="modal" class="btn btn-default">Canel</button>
                                                        </div>
                                                    </div>
                                                </div
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                        <a data-toggle="modal" data-target="#edit-{{user.user_id}}" style="padding-left: 5px; padding-right: 5px;">
                            <i class="glyphicon glyphicon-pencil"></i>
                        </a>
                        <div class="modal fade" id="edit-{{user.user_id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Chỉnh sửa người dùng: {{user.user_real_name}}</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="portlet"> 

                                                    <div class="form-body">
                                                        <form ng-submit="processForm()"  ng-controller="formEditController">
                                                            <input  type="text" class="form-control" ng-model="formData.user_id"  name="user_id"  style="display: none;"> 
                                                            <div class="form-group">
                                                                <label>Email:</label>
                                                                <input  type="text" class="form-control" ng-model="formData.username" placeholder="{{user.username}}"  name="username"> 
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Tên hiển thị:</label>
                                                                <input  type="text" class="form-control" ng-model="formData.user_real_name" placeholder="{{user.user_real_name}}"  name="user_real_name">
                                                            </div>
                                                            <div class="form-group">
                                                                <label >Ngày tháng tham gia:</label>
                                                                <input  type="date" class="form-control" ng-model="formData.user_date_attend" placeholder="{{user.user_date_attend}}"  name="user_date_attend">
                                                            </div>                                        
                                                            <div class="form-group">
                                                                <label>Trạng thái</label>
                                                                <select class="form-control" id="is_active" ng-model="formData.user_active">
                                                                    <option value = "1">Kích hoạt</option>
                                                                    <option value = "0">Phạt</option>
                                                                </select>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy</button>
                                                                <button type="submit" data-dismiss="modal"class="btn" ng-click="submit">Lưu thay đổi</button>
                                                            </div>
                                                        </form>
                                                    </div>

                                                </div
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                        <a>
                            <i class="glyphicon glyphicon-trash" ng-click="removeRow(user.user_id)"></i>
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>





<!-- Modal -->

