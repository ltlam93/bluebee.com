$__$.on("html-appended",function($html){
	var selector = ".input-date";
	var doneClass = "input-date-done";
	$html.find(selector).andSelf().filter(selector).each(function(){
		var $self = $(this);
		$self.addClass(doneClass);
		$self.datepicker($self.data());
		var timestamp;
		if(timestamp = $self.attr("data-timestamp")){
			$self.data("datepicker").setDate(new Date(timestamp*1000));
		}
	});
});