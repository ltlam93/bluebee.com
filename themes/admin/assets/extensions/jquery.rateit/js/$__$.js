$__$.on("html-appended",function($html){
	var selector = ".rate-it";
	var doneClass = "rate-it-done";
	$html.find(selector).andSelf().filter(selector).each(function(){
		var $self = $(this);
		$self.addClass(doneClass);
		$self.rateit();
	});
});