$(function(){
	$__$.trigger("init");
	$__$.trigger("html-parsing",$("body"));
	$__$.trigger("html-appended",$("body"));
	setTimeout(function(){
		$__$.trigger("html-start-working");
	},200);
	/*$(".modal").modal({
		keyboard : false,
		show : false,
		backdrop : true
	});*/
});
$__$.on("init",function(){
	$("#mobileNavToggle").on("click",function(){
		$(this).toggleClass("active");
		$("body").toggleClass("slide-nav slide-nav-left");
	});
});
$__$.on("form-reload",function(){
	location.reload();
});
$.fn.outer = function(){
	return $("<div>").append($(this).clone(true)).html();
};
// function
/*(function(){
	if($__$.mobile)
		return;
	$__$.alertCallback = null;
	$__$.confirmCallback = null;
	$__$.alert = function(message,options){
		var defaultOptions = {
			title:"$__$.vn",
			callback:function(){}
		};
		options = $.extend({},defaultOptions,options);
		
		var $dialog = $("#dialogModal");
		$dialog.find(".modal-alert").show();
		$dialog.find(".modal-confirm").hide();
		$dialog.find(".modal-title").text(options.title);
		$dialog.find(".modal-message").text(message);
		if($("body").find(".modal-backdrop").length)
		{
			// modal is still displayed
			$dialog.one("hidden.bs.modal",function(){
				$dialog.modal("show");
			})
		}
		else
		{
			$dialog.modal("show");
		}
		$__$.alertCallback = options.callback;
	};
	$__$.confirm = function(message,options){
		var defaultOptions = {
			title:"$__$.vn",
			callback:function(){}
		};
		options = $.extend({},defaultOptions,options);
		
		var $dialog = $("#dialogModal");
		$dialog.find(".modal-alert").hide();
		$dialog.find(".modal-confirm").show();
		$dialog.find(".modal-title").text(options.title);
		$dialog.find(".modal-message").text(message);
		if($("body").find(".modal-backdrop").length)
		{
			// modal is still displayed
			$dialog.one("hidden.bs.modal",function(){
				$dialog.modal("show");
			})
		}
		else
		{
			$dialog.modal("show");
		}
	
		$__$.confirmCallback = options.callback;
	};
	window.dialog_ok = function()
	{
		$__$.alertCallback();	
	}
	window.dialog_yes = function()
	{
		$__$.confirmCallback(true);
	}
	window.dialog_no = function()
	{
		$__$.confirmCallback(false);
	}
})();
*/
// form error inline
(function(){
	window.formErrorInlineBootstrap = function(errors,$form){
		var text = '<div class="alert alert-info h5">\
					  <strong><i class="icon-bell-alt"></i> Lỗi!</strong> <span classs="h4">'+errors[0].message+'</span>\
					</div>';
		$form.find(".form-error").show().html(text);
	};
	window.formErrorInline = function(errors,$form){
		$form.find(".form-error").show().find(".error-content").html(errors[0].message);
	};
	window.formErrorInlineValid = function($form){
		$form.find(".form-error").hide();	
	};
})();
// filter
//
jQuery.fn.hideout = function(options){
	var self = this;
	var defaultOptions = {
		hide:function(){
			$(self).hide();
		}
	};
	options = $.extend({},defaultOptions,options);
	$(document).click(function(e){
		hideout_click(e);
	});
	var hideout_click = function(e)
	{
		var $target = $(e.target);
		if(!$target.parents().andSelf().filter(self).length)
		{
			options.hide();
		}
	}
};
//
$__$.prevent = function(e)
{
	e = e ? e : window.event;
	if(e)
	{
		e.stopPropagation();
		e.preventDefault();
	}
	return false;
}
//
jQuery.fn.hasAttr = function(attr)
{
	return $(this).attr(attr)!=undefined;
}

$__$.on("html-appended",function($html){
	$html.find("[data-toggle='tooltip']").andSelf().filter("[data-toggle='tooltip']").each(function(){
		$(this).attr("done-tooltip","1").tooltip();
	});
	$html.find("[data-toggle='popover']").andSelf().filter("[data-toggle='popover']").each(function(){
		$(this).attr("done-popover","1").popover();
	});
});

function show_error(errors)
{
	$("#formerror").html('\
		<div class="alert alert-warning">\
		  <button type="button" class="close" data-dismiss="alert">&times;</button>\
		  <strong>'+errors[0].message+'\
		</div>\
	');
	errors[0].elem.focus();
};

$__$.attrToObject = function(elem)
{
	var obj = {};
	elem = $(elem).get(0);
	var attributes = $.map(elem.attributes, function(item) {
		return item.name;
	});
	$.each(attributes,function(k,v){
		var valid = typeof k == "string" || typeof k == "number";
		if(!valid)
			return;
		if(v.substring(0,5)=="data-")
		{
			obj[v.substring(5)] = $(elem).attr(v);
		}
	});
	return obj;
};

jQuery.fn.focusEnd = function(){
	var el = $(this).get(0);
    if (typeof el.selectionStart == "number") {
        el.selectionStart = el.selectionEnd = el.value.length;
    } else if (typeof el.createTextRange != "undefined") {
        el.focus();
        var range = el.createTextRange();
        range.collapse(false);
        range.select();
    }
    return $(this);
};

$__$.on("html-appended",function($html){
	$html.find(".href-focus").each(function(){
		$(this).on("click",function(){
			$($(this).data("href")).focus();
			return false;
		});
	});
	$html.find("[data-click]:not(.done)").each(function(){
		var expression = $(this).data("click");
		$(this).addClass("done").attr("onclick",expression);
	});
});

// select dropdown

(function(){
	$.fn.selectDropdown = function(){
		if($__$.mobile)
			return;
		var $select = $(this);
		var $options = $(this).children("option");
		var val = $(this).val();
		var text = $.trim($options.filter(":selected").text());
		// html
		var html = '\
			<div class="btn-group" data-value="'+val+'" data-text="'+text+'">\
				<button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">\
					<span class="select-text">'+text+'</span>\
					<span class="caret"></span>\
				</button>\
				<ul class="dropdown-menu" role="menu" style="left:auto">\
		';
		$options.each(function(){
			var optionVal = $(this).val();
			var optionText = $(this).text();
			html += '<li><a href="#" data-value="'+optionVal+'" data-text="'+optionText+'">'+optionText+'</a></li>';
		});
		html += '\
				</ul>\
			</div>\
		';
		//
		$(this).hide();
		var $html = $(html);
		$html.insertAfter(this).find("ul li a").on("click",function(e){
			var optionVal = $(this).data("value");
			var optionText = $.trim($(this).data("text"));
			$html.removeClass("open").attr({
				"data-value" : optionVal,
				"data-text" : optionText
			}).find(".select-text").text(optionText);
			$select.val(optionVal).trigger("change");
			return $__$.prevent(e);
		});
		$html.children(".dropdown-toggle").click(function(e){
			//$html.dropdown("toggle");
			$html.toggleClass("open");
			return $__$.prevent(e);
		});
	};
	
	$__$.on("html-appended",function($html){
		$html.find(".select-dropdown:not(.done)").each(function(){
			$(this).addClass("done").selectDropdown();
		});
	});

})();

// fix top

(function(){
	$.fn.elemFixedTop = function(options){
		var defaultOptions = {
			top : 0
		};
		options = $.extend({},defaultOptions,options);
		var self = this;
		var isOverflow = false;
		var originTop;
		var originWidth;
		var originHeight = $(self).get(0).offsetHeight;
		var minHeight = parseFloat($("body").css("min-height"));
		if(originHeight > minHeight)
			$("body").css("min-height",originHeight);
		$(this).parent().css("position","relative");
		$(window).scroll(function(){
			if(!$(self).is(":visible"))
				return;
			var top = originTop ? originTop : $(self).offset().top - parseFloat($(self).css('margin-top').replace(/auto/, 0)) - $(self).get(0).offsetHeight;
			//console.log(top);
			var overflow = $(window).scrollTop() > top;
			if((isOverflow && overflow) || (!isOverflow && !overflow))
				return;
			console.log("change");
			isOverflow = overflow;
			if(overflow)
			{
				if(!originTop)
				{
					originTop = top;
				}
				if(!originWidth)
				{
					originWidth = $(self).get(0).offsetWidth;
					$(self).css("width",originWidth);
				}
				$(self).addClass("elemFixedTopClass").css("top",options.top);
			}
			else
			{
				$(self).removeClass("elemFixedTopClass").css("top","auto");
			}
		});
	};
	
	$__$.on("html-appended",function($html){
		$html.find(".elem-fixed-top").each(function(){
			$(this).elemFixedTop($(this).data());
		});
	});

})();

// facebook dynamic update

(function(){
	$__$.on("html-appended",function(){
		if(window.FB)
			FB.XFBML.parse();
	});
})();

(function(){
	$.fn.heightUntilBottom = function(options){
		var defaultOptions = {
			overflowAuto : true
		};
		options = $.extend({},defaultOptions,options);
		var offset = $(this).offset();
		if(!offset || !offset.top)
			return;
		var height = window.innerHeight - offset.top;
		//console.log($(this).offset().top);
		//console.log(height);
		if(isNaN(height))
			return;
		$(this).css("height",height);
		if(options.overflowAuto)
			$(this).css("overflow","auto");
	};
	$__$.on("html-appended",function($html){
		$html.find(".height-until-bottom").each(function(){
			$(this).heightUntilBottom($(this).data());
		});
	});
})();

// doSubmit

(function(){
	$__$.on("html-appended",function($html){
		$html.find(".do-submit").each(function(){
			var target = $(this).data("target");
			$(this).click(function(e){
				$(target).trigger("submit");
				return $__$.prevent(e);
			});
		});
	});
})();

// scroll

(function(){
	$.fn.scrollToMe = function(){
		$('html, body').scrollTop($(this).offset().top);
	};
})();

// angular

(function(){
	$__$.on("angular-init",function(app){
		app.directive('ngBindHtmlUnsafe', function($compile){
		    return function( $scope, $element, $attrs ) {
		        var compile = function( newHTML ) { // Create re-useable compile function
		        	newHTML = '<div>'+newHTML+'</div>';
		            newHTML = $compile(newHTML)($scope);
		            if($element.get(0).moreThanOneTimeLoaded)
		            	$element.htmlPn(newHTML);
		            else
		            {
		            	$element.get(0).moreThanOneTimeLoaded = 1;
		            	$element.html(newHTML);
		            }
		        };
		        var htmlName = $attrs.ngBindHtmlUnsafe;
		        $scope.$watch(htmlName, function( newHTML ){
		            if(!newHTML) return;
		            compile(newHTML);   // Compile it
		        });
		    };
		}).directive("ngRendered",["$timeout",function($timeout){
			return {
				restrict : "A",
				scope : {
					"ngRendered" : "@"
				},
				link : function($scope,$element){
					$timeout(function(){
						$element.css("visibility","visible");
						$element.find(".angular-render").removeClass("angular-render");
						$__$.updateHtml($element);
						if($scope.ngRendered)
						{
							$scope.$parent.$eval($scope.ngRendered);
						}
					});
				}
			};
		}]).directive("ngSuccess",function(){
			var _index = 0;
			return {
				restrict : "A",
				link : function($scope, $element, $attrs){
					var success = $attrs.ngSuccess;
					var index = _index++;
					var attrName = "ng-success-id";
					$element.attr("data-success-event",success);
					$element.attr(attrName,index);
					$__$.on("form-"+success,function(obj){
						if($(obj.elem).attr(attrName)==index)
							$scope.$emit(success,obj.data,$element);
					});
				}
			};
		}).directive("ngClickConfirm",function(){
			var _index = 0;
			return {
				restrict : "A",
				link : function($scope, $element, $attrs){
					var callback = $attrs.ngClickConfirm;
					var confirmMessage = $attrs.ngConfirm ? $attrs.ngConfirm : "Are you sure";
					$element.click(function(e){
						e.preventDefault();
						e.stopPropagation();

						$__$.confirm(confirmMessage,{
							callback : function(result){
								if(result)
									$scope.$parent.$eval(callback);
							}
						})

						return false;
					});
				}
			};
		}).directive("ngShowPopup",function(){
			return {
				restrict : "A",
				link : function($scope,$element,$attrs){
					$element.show_popup();
					$element.on("popup-return-result",function(e){
						var result = $element.data("popup-result");
						$scope.$emit($attrs.ngShowPopup,result,$element);
					});
				}
			}
		}).directive("ngDisplayTime",["$timeout",function($timeout){
			return {
				restrict : "A",
				link : function($scope,$element,$attrs){
					$timeout(function(){
						var timestamp = parseInt($element.text());
						var date = new Date(timestamp*1000);
						$element.attr("data-timestamp",timestamp);
						$element.text(date.toDateString());
					});
				}
			}
		}]);
	});
	$__$.angular = {};
	$__$.angular.apply = function(objectOfScope,objectFromServer){
		$.each(objectFromServer,function(k,v){
			objectOfScope[k] = v;
		});
	};
	$__$.angular.hasInit = false;
	$__$.angular.init = function(app){
		$__$.angular.hasInit = true;
		$__$.trigger("angular-init",app);
		$__$.angular.app = app;
	};
	$__$.angular.dynamic = function(appName){
		angular.bootstrap($("[ng-app='"+appName+"']"),[appName]);
	};
	$__$.angular.data = {};
	$__$.angular.run = function(callback){
		if($__$.angular.hasInit)
			callback($__$.angular.app);
		else
			$__$.on("angular-init",function(app){
				callback(app);
			})
	};
})();

// popup
(function(){
	$.fn.show_popup = function(){
		var $self = $(this);
		var id = $self.data("id");
		var popup_id = "popup-"+id;
		$self.click(function(e){
			e.preventDefault();
			e.stopPropagation();
			//
			var $modal = $("#"+popup_id);
			if($modal.length)
			{
				$modal.modal("show");
			}
			else
			{
				var href = $self.data("href");
				var title = $self.data("title");
				if(!title)
					title = "Popup";
				var cancel_text = $self.data("cancel-text") ? $self.data("cancel-text") : "Hủy";
				var save_text = $self.data("save-text") ? $self.data("save-text") : "Chọn";
				var modal_width = $self.data("modal-width");
				var modal_dialog_class = $self.data("modal-dialog-class");
				$modal = $('\
					<div class="modal fade bs-example-modal-lg" id="'+popup_id+'" tabindex="-1" role="dialog" aria-hidden="true">\
						<div class="modal-dialog">\
							<div class="modal-content">\
								<div class="modal-header">\
									<button type="button" class="close" data-dismiss="modal">&times;</button>\
									<h4 class="modal-title" id="myModalLabel">'+title+'</h4>\
								</div>\
								<div class="modal-body">\
								</div>\
								<div class="modal-footer">\
									<button type="button" class="btn btn-sm btn-default action-cancel" data-dismiss="modal">'+cancel_text+'</button>\
									<button type="button" class="btn btn-sm btn-primary action-save">'+save_text+'</button>\
								</div>\
							</div>\
						</div>\
					</div>'
				);
				if(modal_width)
					$modal.find(".modal-dialog").css("width",modal_width);
				if(modal_dialog_class)
					$modal.find(".modal-dialog").addClass(modal_dialog_class);
				$("body").append($modal);
				$.ajax({
					url : href,
					type : "get",
					success : function(data){
						$modal.find(".modal-body").htmlPn(data);
						var $target = $modal.find(".modal-body").children().first();
						$modal.find(".action-cancel").click(function(){
							$target.trigger("do-cancel");
						});
						$modal.find(".action-save").click(function(){
							// get results
							var e = $.Event("do-save");
							var result = {};
							e.result = result;
							e.closePopup = true;
							$target.trigger(e);
							// close popup
							if(e.closePopup)
								$modal.modal("hide");
							// return results
							$self.data("popup-result",e.result);
							var e2 = $.Event("popup-return-result");
							$self.trigger(e2);
						});
						$modal.modal("show");
					}
				});
			}
			return false;
		});
	};
	$__$.on("html-appended",function($html){
		$html.find(".show-popup").each(function(){
			$(this).show_popup();
		});
	})
})();
