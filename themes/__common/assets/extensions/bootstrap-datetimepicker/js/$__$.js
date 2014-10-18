/*$__$.on("html-appended",function($html){
	var selector = ".input-datetime";
	var doneClass = "input-datetime-done";
	$html.find(selector).andSelf().filter(selector).each(function(){
		var $self = $(this);
		$self.addClass(doneClass);
		$self.datetimepicker($self.data());
		var timestamp;
		if(timestamp = $self.attr("data-timestamp")){
			$self.data("datetimepicker").setDate(new Date(timestamp*1000));
		}
	});
});*/


$__$.registerJQueryPlugin("inputDate",function(){
	var $self = this;
	var timestamp;

	function updateTimestamp(){
		timestamp = $self.options.timestamp;
	}

	this.onInit = function(){
		updateTimestamp();
		$self.$elem.datetimepicker($self.options);
		$self.update();
	};

	this.onUpdate = function(){
		updateTimestamp();
		$self.update();
	};

	this.update = function(){
		if(!timestamp)
			return;
		$self.$elem.data("datetimepicker").setDate(new Date(timestamp*1000));
	};
},{},"input-datetime");