$__$.on("html-appended",function($html){
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
});