<script type="text/javascript">
    // var $j = jQuery.noConflict(); 
    $(document).ready(function() {

        jQuery("a.subject").click(function() {
            var $self = $(this);
            jQuery.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('listOfSubject/listOfSubjectInfoView') ?>",
                beforeSend: function() {
                    $('#loading-image').show();
                },
                success: function(data) {
                    var json = data;
                    var result = data;
                    jQuery('.three-fourths').html(data).hide().fadeIn(800);
                    var faculty_id = $self.attr("faculty-id");
                    var dept_id = $self.attr("dept-id");
                    var subject_type = $self.attr("subject-type");
                    jQuery.ajax({
                        type: "POST",
                        url: "<?php echo Yii::app()->createUrl('listOfSubject/listOfSubjectInfo') ?>",
                        data: {subject_dept: dept_id, subject_faculty: faculty_id, subject_type: subject_type},
                        beforeSend: function() {
                            jQuery('.three-fourths').hide();
                        },
                        success: function(data) {

                            var result = $.parseJSON(data);
                            $("#loading-image").hide();
                            jQuery('.three-fourths').show();
                            jQuery.each(result.subject_group_type, function(key, value) {
                                jQuery('#subject_type_tab').append(
                                        '<div class="w-tabs-item" subject_type_id=' + this.subject_type_id + '>' +
                                        '<span class="w-tabs-item-icon"></span>' +
                                        '<span class="w-tabs-item-title">' + this.subject_group_type + '</span>' +
                                        '</div>').hide().fadeIn(800);
                                jQuery('#subject_type_details').append(
                                        '<div class="w-tabs-section">' +
                                        ' <div class="w-tabs-section-title">' +
                                        '<span class="w-tabs-section-title-icon"></span>' +
                                        '<span class="w-tabs-section-title-text">' + this.subject_group_type + '</span>' +
                                        '<span class="w-tabs-section-title-control"></span>' +
                                        '</div>' +
                                        '<div class="w-tabs-section-content" style="">' +
                                        '<div class="w-tabs-section-content-h">' + this.detail + '</div>' +
                                        '</div>' +
                                        '</div>').hide().fadeIn(1000);
                            });

                            jQuery('#subject_type_tab').children().first().addClass('active');
                            jQuery.each(result.subject_data, function(key, value) {
                                jQuery('#listsubject').append(
                                        '<tr style="border-bottom: 1px solid #d0d6d9">' +
                                        '<td><a href = "<?php echo Yii::app()->createUrl('listOfSubject/subject') ?>' +'/'+ this.subject_id +'/'+ updateKey(this.subject_name) + '"</a>'+this.subject_name+'</td>' +
                                        '<td>' + this.subject_credits + '</td>' +
                                        '<td>' + this.subject_credit_hour + '</td>' +
                                        '<td>' + this.subject_code + '</td>' +
                                        '</tr>').hide().fadeIn(800);
                            });
                            var list = result.subject_type;
                            jQuery.each(result.subject_type, function(i, item) {
                                $('#subject_type_name').html(item.subject_type_name).hide().fadeIn(800);
                                console.log(item.subject_type_name);
                            });
                            jQuery(".w-tabs").wTabs();
                            $("#content-tabs.w-tabs .w-tabs-section").first().addClass('active');
                            $('#loading-image').hide();
                        }
                    });
                }
            });
        });
    });
</script>
<script type="text/javascript">
    // var $j = jQuery.noConflict(); 
    $(document).ready(function() {
        $('#loading-image').hide();
        jQuery("a.dept").click(function() {
            var $self = $(this);
            jQuery.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('listOfSubject/deptInfoView') ?>",
                beforeSend: function() {
                    $('#loading-image').show();
                },
                success: function(data) {
                    var json = data;
                    var faculty_id = $self.attr("faculty-id");
                    var dept_id = $self.attr("dept-id");
                    jQuery('.three-fourths').html(data).hide().fadeIn(800);
                    jQuery("#tab_acc.w-tabs").wTabs();
                    jQuery.ajax({
                        type: "POST",
                        url: "<?php echo Yii::app()->createUrl('listOfSubject/DeptInfo') ?>",
                        data: {faculty_id: faculty_id, dept_id: dept_id},
                        beforeSend: function() {
                            jQuery('.three-fourths').hide();
                        },
                        success: function(data) {
                            jQuery('.three-fourths').show();
                            var json = $.parseJSON(data);
                            //  $('#subject_type_tab').html('');
                            var list = json.dept_data;
                            $.each(list, function(i, item) {
                                $('#head_subject').html(item.dept_name).hide().fadeIn(800);
                                $('#dept_detail').html(item.dept_target).hide().fadeIn(800);
                                $('#dept_knowledge').html(item.dept_knowleadge).hide().fadeIn(800);
                                $('#dept_skill').html(item.dept_skill).hide().fadeIn(800);
                                $('#dept_behavior').html(item.dept_behavior).hide().fadeIn(800);
                                $('#dept_name').html(item.dept_name).hide().fadeIn(800);
                                $('#dept_in_standart').html(item.dept_in_standart).hide().fadeIn(800);
                                $('#dept_out_standard').html(item.dept_out_standard).hide().fadeIn(800);
                                $('#dept_contact').html(item.dept_contact).hide().fadeIn(800);
                                $('#dept_credits').html(item.dept_credits).hide().fadeIn(800);
                                $('#dept_language').html(item.dept_language).hide().fadeIn(800);
                                $('#dept_out_standard').html(item.dept_out_standard).hide().fadeIn(800);
                                $('#dept_code').html(item.dept_code).hide().fadeIn(800);
                                $('#target_detail').html(item.dept_target).hide().fadeIn(800);
                            });
                            jQuery("#tab_acc.w-tabs").wTabs();
                            $('#loading-image').hide();
                        }
                    });
                }
            });
        });
    });
</script>



<script type="text/javascript">
    // var $j = jQuery.noConflict(); 
    $(document).ready(function() {
        $('#loading-image').hide();
        jQuery("a.faculty").click(function() {
            var $self = $(this);
            jQuery.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('listOfSubject/facultyInfoView') ?>",
                beforeSend: function() {
                    //  $('#loading-image').show();
                },
                success: function(data) {
                    var json = data;
                    var faculty_id = $self.attr("faculty-id");
                    jQuery('.three-fourths').html(data).hide().fadeIn(800);
                    jQuery("#detail_faculty.w-tabs").wTabs();
                    jQuery.ajax({
                        type: "POST",
                        url: "<?php echo Yii::app()->createUrl('listOfSubject/facultyInfo') ?>",
                        data: {faculty_id: faculty_id},
                        beforeSend: function() {
                            jQuery('.three-fourths').hide();
                        },
                        success: function(data) {
                            jQuery('.three-fourths').show();
                            var result = $.parseJSON(data);
                            //  $('#subject_type_tab').html('');
                            jQuery.each(result.teacher_faculty_position, function(key, value) {
                                var item = $('<div style="height: 250px">' +
                                        '<div class="w-team-member">' +
                                        '<div class="w-team-member-h">' +
                                        '<div class="w-team-member-image">' +
                                        '<a href = "<?php echo Yii::app()->createUrl('share/teacher') ?>' + '/'+this.teacher_id +'/'+updateKey(this.teacher_name)+ '">' +
                                        '<img src="' + this.teacher_avatar + '" />' +
                                        '</a>' +
                                        '</div>' +
                                        '<div class="w-team-member-meta">' +
                                        '<h5 class="w-team-member-name">' + this.teacher_acadamic_title + ' ' + this.teacher_name + '</h5>' +
                                        '<div class="w-team-member-role">' + this.teacher_position + '</div>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>'

                                        ).hide().fadeIn(500);
                                jQuery('#teacher_lead').append(item
                                        );
                            });
                            jQuery.each(result.faculty_data, function(key, value) {
                                jQuery('#faculty_name').append(
                                        this.faculty_name);
                                jQuery('#research').append(
                                        this.faculty_research);
                                jQuery('#faculty_lab').append(
                                        this.faculty_lab);
                            });
                            $('#loading-image').hide();
                        }
                    });
                }
            });
        });
    });
</script>

<script>
    function test() {
        var $self = $(this);
        jQuery.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('listOfSubject/facultyInfoView') ?>",
            beforeSend: function() {
                //  $('#loading-image').show();
            },
            success: function(data) {
                var json = data;
                var faculty_id = '1';
                jQuery('.three-fourths').html(data).hide().fadeIn(800);
                jQuery("#detail_faculty.w-tabs").wTabs();
                jQuery.ajax({
                    type: "POST",
                    url: "<?php echo Yii::app()->createUrl('listOfSubject/facultyInfo') ?>",
                    data: {faculty_id: faculty_id},
                    beforeSend: function() {
                        jQuery('.three-fourths').hide();
                    },
                    success: function(data) {
                        jQuery('.three-fourths').show();
                        var result = $.parseJSON(data);
                        //  $('#subject_type_tab').html('');
                        jQuery.each(result.teacher_faculty_position, function(key, value) {
                            jQuery('#teacher_lead').append(
                                    '<div class="w-team-member">' +
                                    '<div class="w-team-member-h">' +
                                    '<div class="w-team-member-image">' +
                                     '<a href = "<?php echo Yii::app()->createUrl('share/teacher') ?>' + '/'+this.teacher_id +'/'+updateKey(this.teacher_name)+ '">' +
                                    '<img src="' + this.teacher_avatar + '" />' +
                                    '</a>' +
                                    '</div>' +
                                    '<div class="w-team-member-meta">' +
                                    '<h5 class="w-team-member-name">' + this.teacher_acadamic_title + ' ' + this.teacher_name + '</h5>' +
                                    '<div class="w-team-member-role">' + this.teacher_position + '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>'

                                    ).hide().fadeIn(500);
                        });
                        jQuery.each(result.faculty_data, function(key, value) {
                            jQuery('#faculty_name').append(
                                    this.faculty_name);
                            jQuery('#research').append(
                                    this.faculty_research);
                            jQuery('#faculty_lab').append(
                                    this.faculty_lab);
                        });
                        $('#loading-image').hide();
                    }
                });
            }
        });
    }

    jQuery(document).ready(function($) {
        var hash = window.location.hash;
        if (hash.length != 0) {
            $('a[href="' + hash + '"]').trigger("click");
        } else {
            window.onload = test;
            //console.log(hash);
        }
    });
</script>


<div class="l-submain">
    <div class="l-submain-h i-cf">
        <div class="g-cols">
            <?php $this->renderPartial("partial/bar_left", array('category_father' => $category_father, 'subject_type' => $subject_type)); ?>
            <div class="three-fourths">
                <img id="loading-image" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/img/ajax_loader_blue_128.gif" style="margin-left: 400px;"/>
                <!--cmt facebook-->
            </div>
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
            </br>
            </br>
            </br>
            </br>
            <div class="full-width">
                <div class="fb-like" data-href="<?php echo Yii::app()->createAbsoluteUrl('listOfSubject') ?>" data-layout="standard" data-action="like" data-show-faces="false" data-share="true"></div>
                <div class="fb-comments" data-href="<?php echo Yii::app()->createAbsoluteUrl('listOfSubject') ?>" data-width="1000" data-numposts="8" data-colorscheme="light"></div>
            </div>
        </div>
    </div>
</div><!--
