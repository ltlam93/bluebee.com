var loads = [];
if(TableConfig.dragToReOrder)
    loads.push("ui");
var app = angular.module('AjaxTable',loads);
$__$.angular.init(app);
app.controller('AjaxTableController',function($scope,$http) {
    tt = $scope;
	var url = TableConfig.url;
    $scope.rows = [];
    $scope.count = 0;
    $scope.pages = [];
    $scope.pageLast = 0;
    $scope.refreshCount = 0;
    $scope.TableConfig = TableConfig;
    $scope.loading = false;
    $.each(TableConfig.default,function(k,v){
    	$scope[k] = v;
    });

    $scope.$on("form-edit",function(e,data,$elem){
        //console.log(data);
        $(".modal-backdrop").remove();
        refreshTable();
    });

    $scope.$on("form-create",function(e,data,$elem){
        //console.log(data);
        $(".modal-backdrop").remove();
        refreshTable();
    });

    $scope.$on("form-delete",function(e,data,$elem){
        //console.log(data);
        $(".modal-backdrop").remove();
        refreshTable();
    });

    $scope.refresh = function(){
    	refreshTable();
    };
    $scope.changeOrder = function(orderBy)
    {
    	if(orderBy==$scope.orderBy)
    	{
    		$scope.orderBy = orderBy;
    		$scope.orderType = $scope.orderType=="desc" ? "asc" : "desc";
    	}
    	else
    	{
    		$scope.orderBy = orderBy;
    		$scope.orderType = "desc";
    	}
    	refreshTable();
    };
    $scope.changePage = function(page)
    {
    	$scope.page = page;
    	refreshTable();
    };
    $scope.parseInt = function(val)
    {
        return window.parseInt(val);
    }
    $scope._link_template = function(row,template){
        $.each(row,function(key,value){
            template = template.replace(new RegExp("%"+key,"g"),value);
        });
        return template;
    }
    $scope.checkAll = function(){
        $.each($scope.rows,function(k,r){
            r.__checked = $scope.__checkedAll;
        });
    }
    $scope.doAction = function(url,confirm){
        var ids = getSelected();
        if(!ids.length)
            return;
        var callback = function(){
            $.ajax({
                url : url,
                type : "post",
                data : {
                    id : ids
                },
                success : function(){
                    refreshTable();
                }
            });
        };

        if(!confirm)
            callback();
        else
            $__$.confirm(confirm,{
                callback : function(r){
                    console.log(r);
                    if(r)
                        callback();
                }
            });
    };

    //

    var getSelected = function(){
        var ids = [];
        $.each($scope.rows,function(k,r){
            if(!r.__checked)
                return;
            ids.push(r.id);
        });
        return ids;
    }

    var refreshTable = function(callback){
        $scope.loading = true;
    	$http.get(getListUrl(url)).success(function(obj) {
    		var data = obj.data;t=data.data;
            $scope.rows = data.data;
            $scope.count = parseInt(data.count);
            $scope.pageLast = getPageLast();
            $scope.pages = getPages();
            if($scope.refreshCount==0 && $scope.page==0)
            {
                $scope.countAll = $scope.count;
            }
            $scope.refreshCount++;
            callback && callback();
            $scope.loading = false;
		});
    };
    var getPageLast = function(){
    	return Math.ceil($scope.count / $scope.per_page);
    };
    var getPages = function(){
    	if(!$scope.count)
    		return [];
    	var pages = [];
    	// do
    	var pageLast = $scope.pageLast;
    	var current = $scope.page;
    	var leftSize = 2;
    	var rightSize = 2;
    	var leftSizeAllowed = current - 1;
    	var rightSizeAllowed = pageLast - current;
    	leftSize = Math.min(leftSize,leftSizeAllowed);
    	rightSize = Math.min(rightSize,rightSizeAllowed);
    	for(var i=current-leftSizeAllowed;i<current;i++)
    	{
    		pages.push({
    			num : i,
    			active : false
    		});
    	}
    	pages.push({
    		num : current,
    		active : true
    	});
    	for(var i=current+1;i<=current+rightSizeAllowed;i++)
    	{
    		pages.push({
    			num : i,
    			active : false
    		});
    	}
    	//
    	return pages;
    };
    function encodeQueryData(data)
	{
	   var ret = [];
	   for (var d in data)
	      ret.push(encodeURIComponent(d) + "=" + encodeURIComponent(data[d]));
	   return ret.join("&");
	};
    var getListUrl = function(url){
    	// order, page, per_page, search, search_advanced
    	// default
    	var order = $scope.orderBy + " " + $scope.orderType;
    	var page = $scope.page;
    	var per_page = $scope.per_page;
    	var search = $scope.search;
    	var search_advanced = $scope.search_advanced;
    	// parse variable from table

    	// return url
    	var q = {
    		order : order,
    		page : page,
    		per_page : per_page,
    		search : search
    	};
    	$(".query-input").each(function(){
    		var val = $.trim($(this).val());
    		if(!val)
    			return;
    		q[$(this).attr("name")] = val;
    	});
    	var query = encodeQueryData(q);
    	if(TableConfig.condition)
    	{
    		query += "&"+TableConfig.condition;
    	}
        query += "&action=data";
    	return url+"?"+query;

    };

    $scope.displayDatetime = function(datetime){
        var date = new Date(datetime*1000);
        return date.toDateString();
    }

    $scope.displayTimestamp = function(datetime){
        var date = new Date(datetime*1000);
        return date.toDateString();
    }

    // drag to reorder
    if(TableConfig.dragToReOrder)
    {
        var urlOrder = TableConfig.urlOrder;
        $scope.orderUpdateable = false;
        $scope.sortableOptions = {
            axis: 'y',
            update: function(){
                $scope.orderUpdateable = true;
            }
        };
        $scope.updateOrder = function(){
            var orders = [];
            $("#tableDraggable.table-draggable tr").each(function(k,v){
                orders.push({
                    index : k,
                    id : $(v).attr("data-id")
                });
            });
            $.ajax({
                type : "post",
                url : urlOrder,
                data : {
                    orders : orders
                },
                success : function(){
                    $scope.$apply(function(){
                        $scope.orderUpdateable = false;
                    });
                }
            });
        };
    }

    // start
    refreshTable();
});
app.directive('ngEnter', function () {
    return function (scope, element, attrs) {
        element.bind("keydown keypress", function (event) {
            if(event.which === 13) {
                scope.$apply(function (){
                    scope.$eval(attrs.ngEnter);
                });

                event.preventDefault();
            }
        });
    };
});
app.directive("ngTinymce",["$timeout",function($timeout){
    var id = 0;
    return {
        restrict : "A",
        scope : {
            
        },
        link : function($scope,$element){
            $element.hide();
            $timeout(function(){
                $element.show();
                id++;
                var idText = "tinymce-textarea-"+id;
                $element.attr("id",idText);
                tinymce.init({
                    selector: "#"+idText
                 });
            });
        }
    };
}]);
/*app.directive('ngBindHtmlUnsafe', function($compile){
    return function( $scope, $element, $attrs ) {
        var compile = function( newHTML ) { // Create re-useable compile function
            newHTML = '<div>'+newHTML+'</div>';
            newHTML = $compile(newHTML)($scope); // Compile html
            $element.html('').append(newHTML); // Clear and append it
        };
        var htmlName = $attrs.ngBindHtmlUnsafe;
        $scope.$watch(htmlName, function( newHTML ){
            if(!newHTML) return;
            compile(newHTML);   // Compile it
        });
    };
})*/