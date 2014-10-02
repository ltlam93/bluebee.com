<div class="one-fourth" style="
     border-right-style: double;
     border-right-width: thick;
     border-color: #d0d6d9;
     ">

    <h3 id="second">Các ngành học</h3>            
    <div class="widget">
        <nav class="w-nav">
            <div class="w-nav-h">
                <div class="w-nav-list layout_ver level_1">
                    <div class="w-nav-list-h">
                        <?php foreach ($category_father as $category): ?>
                            <div class="w-nav-item level_1 active">

                                <?php $dept = Dept::model()->findAllByAttributes(array('dept_faculty' => $category->faculty_id)); ?>
                                <div class="w-nav-item-h">
                                    <a href="#faculty-<?php echo $category->faculty_id ?>" class="w-nav-anchor level_1 faculty" faculty-id="<?php echo $category->faculty_id ?>"><?php echo $category->faculty_name ?> 
                                        <span class="w-nav-title " ></span>
                                    </a>

                                    <?php
                                    if ($dept) {
                                        foreach ($dept as $dept_detail):
                                            ?>
                                            <div class="w-tabs layout_accordion type_toggle">
                                                <div class="w-tabs-h">
                                                    <div class="w-tabs-section with_icon">

                                                        <div class="w-tabs-section-title">
                                                            <a href="#dept-<?php echo $category->faculty_id ?>-<?php echo $dept_detail->dept_id ?>"class="w-tabs-section-title-text dept" style="margin-left: 32px" faculty-id="<?php echo $category->faculty_id ?>" dept-id="<?php echo $dept_detail->dept_id ?>">- <?php echo $dept_detail->dept_name ?></a>
                                                        </div>
                                                        <div class="w-tabs-section-content">
                                                            <div class="w-tabs-section-content-h">
                                                                <?php foreach ($subject_type as $subject_detail): ?>
                                                                    <div class="w-nav-item level_2 active">
                                                                        <div class="w-nav-item-h">
                                                                            <a href="#subject-<?php echo $category->faculty_id ?>-<?php echo $dept_detail->dept_id ?>-<?php echo $subject_detail->id ?>" class="w-nav-anchor level_2 subject" faculty-id="<?php echo $category->faculty_id; ?>" dept-id="<?php echo $dept_detail->dept_id ?>" subject-type="<?php echo $subject_detail->id ?>">
                                                                                <span class="w-nav-title " >
                                                                                    <?php echo $subject_detail->subject_type_name ?>
                                                                                </span>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php } else { ?>
                                        <div class="w-tabs-section-content">
                                            <div class="w-tabs-section-content-h">
                                                <?php foreach ($subject_type as $subject_detail): ?>
                                                    <div class="w-nav-item level_2 active">
                                                        <div class="w-nav-item-h">

                                                            <a href="#subject-<?php echo $category->faculty_id ?>-<?php echo $dept_detail->dept_id ?>-<?php echo $subject_detail->id ?>" class="w-nav-anchor level_2 subject" faculty-id="<?php echo $category->faculty_id; ?>" dept-id="<?php echo $dept_detail->dept_id ?>" subject-type="<?php echo $subject_detail->id ?>">
                                                                <span class="w-nav-title" >
                                                                    <?php echo $subject_detail->subject_type_name ?>
                                                                </span>
                                                            </a>

                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>

                                            </div>
                                        </div>
                                    <?php } ?>

                                </div>
                            </div>

                        <?php endforeach; ?>  
                    </div>
                </div>
            </div>
        </nav>
    </div>
</div>

<script type="text/javascript">
    
    $(document).ready(function() {

        jQuery("a.subject").click(function() {
            var $self = $(this);
            var faculty_id = $self.attr("faculty-id");
            var dept_id = $self.attr("dept-id");
            var subject_type = $self.attr("subject-type");
            jQuery.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('lab/listdocument') ?>",
                data: {subject_dept: dept_id, subject_faculty: faculty_id, subject_type: subject_type},
                beforeSend: function() {
                    $('#loading-image').show();
                },
                success: function(data) {
                    var result = $.parseJSON(data);
                    jQuery('#list_document').empty();
                    jQuery.each(result.doc_data, function(key, value) {
                        jQuery('#list_document').append(
                                '<li class="item_document">' +
                                '<div class="box_item">' +
                                '<div class="short_info_document clearfix">' +
                                '<div class="document_img">' +
                                '<img src="' + this.doc_url + '" height = "166">' +
                                '<a href="<?php echo Yii::app()->createAbsoluteUrl('viewDocument') ?>?doc_id=' + this.doc_id + '" class="document_img_hover">' +
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
                                '<div class="name_document"><a href="<?php echo Yii::app()->createAbsoluteUrl('viewDocument') ?>?doc_id=' + this.doc_id + '"><strong>' + this.doc_name + '</strong></a></div>' +
                                '</li>'
                                ).hide().fadeIn(500);
                    });
                    $('#list_document').paginate({itemsPerPage: 15});  
                    jQuery('#filter_subject').html('');
                    jQuery.each(result.subject_data, function(key, value) {

                        jQuery('#filter_subject').append(
                                '<label class="checkbox-styled">' +
                                '<input type="checkbox"/>' +
                                '<span class = "subject_filter" subject-id-filter = ' + this.subject_id + ' onclick="loaddoc(' + this.subject_id + ')">' + this.subject_name + '</span>' +
                                '</label>').hide().fadeIn(500);
                    });
                }
            });
        });
    });
</script>

<script type="text/javascript">
    // var $j = jQuery.noConflict();
    $(document).ready(function() {

        jQuery("a.dept").click(function() {
            var $self = $(this);
            var faculty_id = $self.attr("faculty-id");
            var dept_id = $self.attr("dept-id");
            var subject_type = $self.attr("subject-type");
            jQuery.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('lab/listdocumentdept') ?>",
                data: {subject_dept: dept_id, subject_faculty: faculty_id, subject_type: subject_type},
                beforeSend: function() {
                    $('#loading-image').show();
                },
                success: function(data) {
                    var result = $.parseJSON(data);
                    jQuery('#list_document').empty();
                    jQuery.each(result.doc_data, function(key, value) {
                        jQuery('#list_document').append(
                                '<li class="item_document">' +
                                '<div class="box_item">' +
                                '<div class="short_info_document clearfix">' +
                                '<div class="document_img">' +
                                '<img src="' + this.doc_url + '" height = "166">' +
                                '<a href="<?php echo Yii::app()->createAbsoluteUrl('viewDocument') ?>?doc_id=' + this.doc_id + '" class="document_img_hover">' +
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
                                '<div class="name_document"><a href="<?php echo Yii::app()->createAbsoluteUrl('viewDocument') ?>?doc_id=' + this.doc_id + '"><strong>' + this.doc_name + '</strong></a></div>' +
                                '</li>'
                                ).hide().fadeIn(500);
                    });
                    $('#list_document').paginate({itemsPerPage: 15});  
                    jQuery('#filter_subject').html('');
                    jQuery.each(result.subject_data, function(key, value) {

                        jQuery('#filter_subject').append(
                                '<label class="checkbox-styled">' +
                                '<input type="checkbox"/>' +
                                '<span class = "subject_filter" subject-id-filter = ' + this.subject_id + ' onclick="loaddoc(' + this.subject_id + ')">' + this.subject_name + '</span>' +
                                '</label>').hide().fadeIn(500);
                    });
                }
            });
        });
    });
</script>

<script type="text/javascript">
    // var $j = jQuery.noConflict();
    $(document).ready(function() {

        jQuery("a.faculty").click(function() {
            var $self = $(this);
            var faculty_id = $self.attr("faculty-id");
            var dept_id = $self.attr("dept-id");
            var subject_type = $self.attr("subject-type");
            jQuery.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('lab/listdocumentfaculty') ?>",
                data: {subject_dept: dept_id, subject_faculty: faculty_id, subject_type: subject_type},
                beforeSend: function() {
                    $('#loading-image').show();
                },
                success: function(data) {
                    var result = $.parseJSON(data);
                    jQuery('#list_document').empty();
                    jQuery.each(result.doc_data, function(key, value) {
                        jQuery('#list_document').append(
                                '<li class="item_document">' +
                                '<div class="box_item">' +
                                '<div class="short_info_document clearfix">' +
                                '<div class="document_img">' +
                                '<img src="' + this.doc_url + '" height = "166">' +
                                '<a href="<?php echo Yii::app()->createAbsoluteUrl('viewDocument') ?>?doc_id=' + this.doc_id + '" class="document_img_hover">' +
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
                                '<div class="name_document"><a href="<?php echo Yii::app()->createAbsoluteUrl('viewDocument') ?>?doc_id=' + this.doc_id + '"><strong>' + this.doc_name + '</strong></a></div>' +
                                '</li>'
                                ).hide().fadeIn(500);
                    });
                    $('#list_document').paginate({itemsPerPage: 15});  
                    jQuery('#filter_subject').html('');
//Scrip Loc theo mon hoc - bar-right
                    jQuery.each(result.subject_data, function(key, value) {

                        jQuery('#filter_subject.filter_subjects').append(
                                '<label class="checkbox-styled">' +
                                '<input type="checkbox"/>' +
                                '<span class = "subject_filter" subject-id-filter = ' + this.subject_id + ' onclick="loaddoc(' + this.subject_id + ')">' + this.subject_name + '</span>' +
                                '</label>').hide().fadeIn(500);
                    });
//
                }
            });
        });
    });
</script>

<script type="text/javascript">
    // var $j = jQuery.noConflict();
    function loaddocpage() {

        var $self = $(this);
        var faculty_id = $self.attr("faculty-id");
        var dept_id = $self.attr("dept-id");
        var subject_type = $self.attr("subject-type");
        jQuery.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('lab/listdocumentfaculty') ?>",
            data: {subject_dept: 1, subject_faculty: 1, subject_type: 1},
            beforeSend: function() {
                $('#loading-image').show();
            },
            success: function(data) {
                var result = $.parseJSON(data);
                jQuery('#list_document').empty();
                jQuery.each(result.doc_data, function(key, value) {
                    jQuery('#list_document').append(
                            '<li class="item_document">' +
                            '<div class="box_item">' +
                            '<div class="short_info_document clearfix">' +
                            '<div class="document_img">' +
                            '<img src="' + this.doc_url + '" height = "166">' +
                            '<a href="<?php echo Yii::app()->createAbsoluteUrl('viewDocument') ?>?doc_id=' + this.doc_id + '" class="document_img_hover">' +
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
                            '<div class="name_document"><a href="<?php echo Yii::app()->createAbsoluteUrl('viewDocument') ?>?doc_id=' + this.doc_id + '"><strong>' + this.doc_name + '</strong></a></div>' +
                            '</li>'
                            ).hide().fadeIn(500);
                });
                $('#list_document').paginate({itemsPerPage: 15});  
                jQuery('#filter_subject').html('');
                jQuery.each(result.subject_data, function(key, value) {

                    jQuery('#filter_subject.filter_subjects').append(
                            '<label class="checkbox-styled">' +
                            '<input type="checkbox"/>' +
                            '<span class = "subject_filter" subject-id-filter = ' + this.subject_id + ' onclick="loaddoc(' + this.subject_id + ')">' + this.subject_name + '</span>' +
                            '</label>').hide().fadeIn(500);
                });
            }
        });

    }
    ;
   jQuery(document).ready(function($) {
        var hash = window.location.hash;
        if (hash.length !== 0) {
            $('a[href="' + hash + '"]').trigger("click");
        } else {
            window.onload = loaddocpage();
            //console.log(hash);
        }
    });
</script>



