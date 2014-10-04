<?php 
	$this->add_asset_extension("jquery-ui","jquery.ui.js",array(
	    "jquery-ui.css",
	    "jquery.ui.theme.css"
	));
?>

<?php $this->asset_start_js_code(); ?>
<script>
	$(function(){
		$(".spinner").each(function(){
			var obj = {
	            spin : function(){
	                $(this).trigger("change")
	            },
	            change : function(){
	                $(this).trigger("change")
	            }
	        };
	        if($(this).data("max")!=undefined)
	        	obj.max = parseInt($(this).data("max"));
	        if($(this).data("min")!=undefined)
	        	obj.min = parseInt($(this).data("min"));
	        $(this).spinner(obj);
	        $(this).keyup(function(){
	           $(this).trigger("change");
	        });
	        $(this).keydown(function(e){
	            var keyCode = e.which ? e.which : e.keyCode;
	            var valid = (keyCode >= 48 && keyCode <=57) || keyCode==8;
	            if(!valid)
	            {
	                e.preventDefault();
	                e.stopPropagation();
	                return false;
	            }
	        });
	    });
	    $__$.on("")
	});
</script>
<?php $this->asset_end_js_code(); ?>