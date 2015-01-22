
<?php foreach ($teacher_detail_info as $teacher): ?>
    <div class="l-main-h">
        <div class="l-submain">
            <div class="l-submain-h g-html">
                <div class="g-cols" style="height: auto">
                    <div class="one-third" >
                        <a href=""><img class="rectan" src="<?php
                            if ($teacher['teacher_avatar'] == "") {
                                echo Yii::app()->theme->baseUrl, "/assets/img/logo.jpg";
                            } else {
                                echo $teacher['teacher_avatar'];
                            }
                            ?>"/></a>
                        <!--rating function script when click rating star  -->
                        <script type="text/javascript">
                            $(document).ready(function() {
                                $(".star").click(function() {
                                    var score = $(this).attr("data-rating-value");
                                    $.ajax({
                                        type: "POST",
                                        url: "<?php echo Yii::app()->createUrl('share/Rating') ?>",
                                        data: {rating_score: score, teacher_id: <?php echo $teacher['teacher_id'] ?>},
                                        success: function(data) {
                                            var result = $.parseJSON(data);
                                            if (result.checkRatingStatus === 0) {
                                                var i;
                                                console.log(result.score);
                                                console.log(result.aver);
                                                console.log(result.count);
                                                $("#number_rator").html(result.count);
                                                $("#average_score").html(result.aver);
                                                for (i = 1; i <= 5; i++) {
                                                    if (i <= result.score)
                                                        $('a[data-rating-value=' + i + ']').addClass("br-selected");
                                                    else
                                                        $('a[data-rating-value=' + i + ']').removeClass("br-selected");
                                                }
                                            } else {
                                                alert(result.message);
                                            }
                                        }
                                    });
                                    ;
                                });
                            });
                        </script>

                        <!--loading star corresponds to teacher's rating score-->
                        <script>
                            $(document).ready(function() {
                                for (i = 1; i <= 5; i++) {
                                    if (i <= <?php echo round($teacher['teacher_rate']) ?>)
                                        $('a[data-rating-value=' + i + ']').addClass("br-selected");
                                    else
                                        $('a[data-rating-value=' + i + ']').removeClass(".br-selected");
                                }
                                $("#average_score").html("<?php echo $teacher->teacher_rate ?>");
                                $("#number_rator").html("<?php echo " " . $countVote ?>");
                            });
                        </script>

                        <h5>Đánh giá giáo viên:</h5>
                        <div style="width:50%;float:left">
                            <div class="input select rating-f">
                                <label for="example-f"></label>
                                <select id="example-f" name="rating">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>
                        </div>
                        <div style="float: left;width:20%">
                            <strong style="font-size: 150%" id="average_score"></strong>
                        </div>
                        <div style="margin-left: 43px;width:30%">
                            <div ><p><i class="icon-user" id="number_rator"></i></p></div>
                        </div>        
                        <a href="<?php echo Yii::app()->createAbsoluteUrl('share/teacherListPage') ?>"><button class="g-btn type_outline size_small"><span>Danh sách giáo viên</span></button></a>

                        <div class="rounded1 color_alternate" style="margin-top: 20px">
                            <h6>Môn học đang dạy</h6>
                        </div>
                        <?php foreach ($subject_teacher as $subject_teacher): ?>                            
                            <div style="margin-top:10px">
                                <span class="dataItem1"><a href="<?php echo Yii::app()->createAbsoluteUrl('listOfSubject/subject') . "/" . $subject_teacher->subject_id . "/" . StringHelper::makeUrlString($subject_teacher->subject_name) ?>"><?php echo $subject_teacher->subject_name ?></a></span>
                                <span class="dataTitle1"><?php echo $subject_teacher->subject_code ?></span>
                            </div>
                        <?php endforeach; ?>                          

                    </div>

                    <div class="two-thirds">
                        <div>
                            <h1><?php echo $teacher['teacher_acadamic_title'] . ". " . $teacher['teacher_name'] ?></h1>
                            <br/>
                            <span class="dataTitle">Website</span>
                            <span class="dataItem"><a href="http://<?php echo $teacher['teacher_personal_page'] ?>"><?php echo $teacher['teacher_personal_page'] ?></a></span>
                            <br/>
                            <span class="dataTitle">Ngày sinh</span>
                            <span class="dataItem"><?php echo $teacher['teacher_birthday'] ?></span>
                            <br/>
                            <span class="dataTitle">Thông tin thêm:</span>
                            <span class="dataItem"><?php echo $teacher['teacher_description'] ?></span>
                            <br/>
                        </div>


                        <div style="margin-top: 100px">
                            <h2>Sơ lược</h2>
                        </div>
                        <div class="g-hr type_long">
                            <span class="g-hr-h">
                                <i class="icon-arrow-down"></i>
                            </span>
                        </div>

                        <div class="g-cols">
                            <div class="full-width">
                                <div class="w-iconbox icon_left">
                                    <div class="w-iconbox-h">
                                        <div class="w-iconbox-icon">
                                            <i class="icon-magic"></i>
                                        </div>
                                        <div class="w-iconbox-text">
                                            <h3 class="w-iconbox-text-title">Tính cách</h3>
                                            <div class="w-iconbox-text-description">
                                                <p><?php echo $teacher['teacher_personality'] ?></p>
                                            </div>

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
                                                <p><?php echo $teacher['advices'] ?></p>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <br/>
                                <br/>
                                <div class="w-iconbox icon_left">
                                    <div class="w-iconbox-h">
                                        <div class="w-iconbox-icon">
                                            <i class="icon-trophy"></i>
                                        </div>
                                        <div class="w-iconbox-text">
                                            <h3 class="w-iconbox-text-title">Công trình nghiên cứu</h3>
                                            <div class="w-iconbox-text-description">
                                                <p><?php echo $teacher['teacher_research'] ?></p>
                                            </div>

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
                    <div>
                        <h3>Bình luận</h3>

                        <div class="fb-like" data-href="<?php echo Yii::app()->createAbsoluteUrl('share/teacher') . "/" . $teacher['teacher_id'] . "/" . StringHelper::makeUrlString($teacher['teacher_name']) ?>" data-layout="standard" data-action="like" data-show-faces="false" data-share="true"></div>
                        <div class="fb-comments" data-href="<?php echo Yii::app()->createAbsoluteUrl('share/teacher') . "/" . $teacher['teacher_id'] . "/" . StringHelper::makeUrlString($teacher['teacher_name']) ?>" data-width="1000" data-numposts="8" data-colorscheme="light"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<?php endforeach; ?>
