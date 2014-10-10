<div class="one-third" style="float: right;">
<?php $this->renderPartial('partial/upload', array('subject_info' => $subject_info));?>
    <div class="wrap_fliter">
        <div class="clearfix">
            <script type="text/javascript">
            $(document).ready(function() {
                $('input[type="checkbox"]').on('change', function() {
                    $('input[type="checkbox"]').not(this).prop('checked', false);
                });
            });

            </script>
        </div>
        <div class="clearfix" style="margin-top: 10px">
            <span class="">Lọc theo Môn học</span>
<!--            <button class="g-btn type_outline size_small"><span><i class="icon-home"></i>Theo dõi</span></button>-->
            <div class="filter_subjects" id="filter_subject">

            </div>
        </div>
    </div>
    <div>

    </div>
</div>

<script type="text/javascript">
    function loaddoc(id) {
        var $self = $(this);
         

        jQuery.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('document/FilterDocumentBySubject')?>",
            data: {subject_id: id},
            success: function(data) {
                var result = $.parseJSON(data);
                jQuery('#list_document').empty();
                jQuery.each(result.doc_data, function(key, value) {
                    jQuery('#list_document').append(
                            '<li class="item_document">' +
                            '<div class="box_item">' +
                            '<div class="short_info_document clearfix">' +
                            '<div class="document_img">' +
                            '<img src="' + this.doc_url + '" height = "166px">' +
                            '<a href="<?php echo Yii::app()->createAbsoluteUrl('viewDocument') ?>/' + this.doc_id +'/'+updateKey(''+this.doc_name+'')+'" class="document_img_hover">' +
                            '<span class="describe_document">' + this.doc_description + '</span>' +
                            '</a>' +
                            '</div>' +
                            ' <ul class="document_status clearfix">' +
                            '</ul>' +
                            '<span class="attribution-user">' +
                            '<a href="/sonvn" class="url_user" title="' + this.doc_author_name + '">' +
                            '</a>' +
                            '</span>' +
                            '</div>' +
                            '</div>' +
                             '<div class="name_document"><a href="<?php echo Yii::app()->createAbsoluteUrl('viewDocument') ?>/' + this.doc_id +'/'+updateKey(''+this.doc_name+'')+'"><strong>' + this.doc_name + '</strong></a></div>' +
                            '</li>'
                            ).hide().fadeIn(500);
                });
              $('#list_document').paginate({itemsPerPage: 15});  
              window.location.hash = 'subject-filter-'+id;
            }
        });

    }
    ;
</script>

