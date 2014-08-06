
<?php foreach ($teacher_detail_info as $teacher): ?>
    <div class="l-main-h">
        <div class="l-submain">
            <div class="l-submain-h g-html">
                <div class="g-cols">
                    <div class="one-third">
                        <a href=""><img class="rectan" src="<?php
                                    if (Yii::app()->session['teacher_avatar'] == "") {
                                        echo Yii::app()->theme->baseUrl, "/assets/img/logo.jpg";
                                    } else {

                                        echo Yii::app()->session['teacher_avatar'];
                                    }
                                    ?>"/></a>
<script type="text/javascript">
    $(document).ready(function(){
        $(".star").click(function(){
            var score = $(this).attr("data-rating-value")
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('share/Rating') ?>",
                data: {rating_score: score},
                success: function(data){
                    var result = $.parseJSON(data);
                    console.log(result.score);
                }
            });
        });

    });  
</script>
                        <div class="input select rating-f read-only">
                            <select class="teacher-block-rating-outside" id="rating" name="rating" style="display: none; float: right">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                        <a href=""><button class="g-btn type_outline size_small"><span>Danh sách giáo viên</span></button></a>

                        <div class="rounded1 color_alternate" style="margin-top: 50px">
                            <h6>Môn học đang dạy</h6>
<?php foreach($subject_teacher as $subject_teacher): ?>                            
                            <div style="margin-top:10px">
                                <span class="dataItem1"><a href="<?php echo Yii::app()->createAbsoluteUrl('listOfSubject/subject?subject_id=').$subject_teacher->subject_id ?>"><?php echo $subject_teacher->subject_name ?></a></span>
                                <span class="dataTitle1"><?php echo $subject_teacher->subject_code ?></span>
                            </div>
<?php endforeach; ?>                          
                        </div>
                    </div>

                    <div class="two-thirds">
                        <div class="box1">
                            <div>
                                <h1><?php echo $teacher['teacher_acadamic_title'].". ".$teacher['teacher_name']?></h1>
                                <br/>
                            </div>
                            <div>
                            <span class="dataTitle">Website</span>
                            <span class="dataItem"><?php echo $teacher['teacher_personal_page'] ?></span>
                            </div>
                            <div>
                            <span class="dataTitle">Ngày sinh</span>
                            <span class="dataItem"><?php echo $teacher['teacher_birthday'] ?></span>
                            </div>
                            <br/>

                            <div class="dataTitle" style="float: left;">Thông tin thêm:</div>
                            <br>
                            <div class="type_long"><?php echo $teacher['teacher_description']?></div>
                        </div>


                        <div style="margin-top: 3%">
                            <h2>Sơ lược</h2>
                        </div>
                        <div class="g-hr type_long">
                            <span class="g-hr-h">
                                <i class="icon-arrow-down"></i>
                            </span>
                        </div>

                        <div class="g-cols">
                            <div class="one-half">
                                <div class="w-iconbox icon_left">
                                    <div class="w-iconbox-h">
                                        <div class="w-iconbox-icon">
                                            <i class="icon-magic"></i>
                                        </div>
                                        <div class="w-iconbox-text">
                                            <h3 class="w-iconbox-text-title">Tính cách</h3>
                                            <div class="w-iconbox-text-description">
                                                <p>Imagination is more important than knowledge. Knowledge is limited. Imagination encircles the world.</p>
                                            </div>
                                            <a class="w-iconbox-text-link" href="#"><span>Learn more</span></a>
                                        </div>
                                    </div>
                                </div>
                                <br/>
                                <br/>
                                <div class="w-iconbox icon_left">
                                    <div class="w-iconbox-h">
                                        <div class="w-iconbox-icon">
                                            <i class="icon-code"></i>
                                        </div>
                                        <div class="w-iconbox-text">
                                            <h3 class="w-iconbox-text-title">Lời khuyên</h3>
                                            <div class="w-iconbox-text-description">
                                                <p>Imagination is more important than knowledge. Knowledge is limited. Imagination encircles the world.</p>
                                            </div>
                                            <a class="w-iconbox-text-link" href="#"><span>Learn more</span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="one-half">
                                <div class="w-iconbox icon_left">
                                    <div class="w-iconbox-h">
                                        <div class="w-iconbox-icon">
                                            <i class="icon-trophy"></i>
                                        </div>
                                        <div class="w-iconbox-text">
                                            <h3 class="w-iconbox-text-title">Công trình nghiên cứu</h3>
                                            <div class="w-iconbox-text-description">
                                                <p>Imagination is more important than knowledge. Knowledge is limited. Imagination encircles the world.</p>
                                            </div>
                                            <a class="w-iconbox-text-link" href="#"><span>Learn more</span></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="g-hr type_invisible">
                                    <span class="g-hr-h">
                                        <i class="icon-star"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!--cmt facebook-->
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
