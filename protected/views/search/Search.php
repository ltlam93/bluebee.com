<div class="l-submain-h" style="margin-top: 2%">
    <h1>Kết quả cho: "<?php echo $query ?>"</h1>
    <input class="none-display" type="text" id="styleforsearchbar" value="" placeholder="Bạn muốn tìm gì ?"/>
    <div class="g-cols">
        <div class="one-fourth" >
            <div class="tabs">
                <input id="tab-1" type="radio" name="radio-set" data=".content-1" class="tab-selector-1" checked="checked">
                <label for="tab-1" class="tab-label-1" style="font-size: 16px;">Người dùng (<?php echo $user_count ?> kết quả)</label>
                <input id="tab-2" type="radio" name="radio-set" data=".content-2" class="tab-selector-2">
                <label for="tab-2" class="tab-label-2" style="font-size: 16px;">Giáo viên (<?php echo $teacher_count ?> kết quả)</label>
                <input id="tab-3" type="radio" name="radio-set" data=".content-3" class="tab-selector-3">
                <label for="tab-3" class="tab-label-3" style="font-size: 16px;">Môn học (<?php echo $subject_count ?> kết quả)</label>
                <input id="tab-4" type="radio" name="radio-set" data=".content-4" class="tab-selector-4">
                <label for="tab-4" class="tab-label-4" style="font-size: 16px;">Tài liệu (<?php echo $doc_count ?> kết quả)</label>
                <div class="clear-shadow"></div>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('.content-1').show();
                $('input[type=radio]').on('click', function() {
                    var pos = $(this).attr('data');
                    console.log(pos);
                    $('.child').hide();
                    $(pos).show();
                })
            });
        </script>
        <div class="three-fourths">
            <div class="content">
                <div class="child content-1">
                    <h2>Người dùng</h2>
                    <?php foreach ($user_result as $user): ?>
                        <div class="result-user clearfix">
                            <a class="search-avatar-view relative float-left" href="<?php echo Yii::app()->createUrl('user?id=') . $user->user_id ?>">
                                <img class="" width="70" height="70" src="<?php echo $user->user_avatar ?>" style="opacity: 1;min-height: 70px; min-width: 70px;">
                            </a>
                            <div class="info">
                                <a href="<?php echo Yii::app()->createUrl('user?id=') . $user->user_id ?>">
                                    <span id="el-105"><?php echo $user->user_real_name ?></span>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="child content-2">
                    <h2>Giáo viên</h2>
                    <?php foreach ($teacher_result as $teacher): ?>
                        <div class="result-teacher clearfix">
                            <a class="search-avatar-view relative float-left" href="user" style="margin-top: 5px">
                                <img width="70" height="70" src="<?php echo $teacher->teacher_avatar ?>" style="opacity: 1; ">
                            </a>
                            <div class="info">
                                <a href="<?php echo Yii::app()->createUrl('share/teacher?id=') . $teacher->teacher_id ?>">
                                    <span id="el-105"><?php echo $teacher->teacher_acadamic_title." ".$teacher->teacher_name ?></span>
                                </a>
                                <p><?php echo $teacher->teacher_description ?></p>
                            </div>
                            <!--                            <div class="float_right">
                                                            <div class="input select rating-f read-only">
                                                                <p style="float: left">Độ yêu thích:</p>
                                                                <br>
                                                                <select class="teacher-block-rating-outside" name="rating" style="display: none; float: right">
                                                                    <option value="1">1</option>
                                                                    <option value="2">2</option>
                                                                    <option value="3">3</option>
                                                                    <option value="4">4</option>
                                                                    <option value="5" selected="selected">5</option>
                                                                </select>
                                                            </div>
                                                        </div>-->
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="child content-3">
                    <h2>Môn học</h2>
                    <?php foreach ($subject_result as $subject): ?>
                        <div class="result-class clearfix">
                            <div class="info-teacher float-left">
                                <a href="<?php echo Yii::app()->createUrl('listOfSubject/subject?subject_id=') . $subject->subject_id ?>">
                                    <span style="font-size: 18px"> <?php echo $subject->subject_name ?></span>
                                </a>
                                <p>Mã môn học: <?php echo $subject->subject_code ?></p>
                                <p>Số tín chỉ: <?php echo $subject->subject_credits ?></p>
                            </div>
                            <div class="float_right">
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="child content-4">
                    <h2>Tài Liệu</h2>
                    <ol class="list_document">
                        <?php foreach ($doc_result as $doc): ?>
                            <li class="item_document">
                                <div class="box_item">
                                    <div class="short_info_document clearfix">
                                        <div class="document_img">
                                            <img src="<?php echo $doc->doc_url ?>">
                                            <a href="<?php echo Yii::app()->createAbsoluteUrl('viewDocument?doc_id=') . $doc->doc_id ?>" class="document_img_hover">
                                                <span class="describe_document"><?php echo $doc->doc_description ?></span>
                                            </a>
                                        </div>
                                        <span class="attribution-user">
                                            <a href="<?php echo Yii::app()->createUrl('user?id=') . $doc->doc_author ?>" class="url_user" title="<?php echo $doc->doc_author_name; ?>">

                                            </a>1
                                        </span>
                                    </div>
                                </div>
                                <a class="name_document" href=""><strong><?php echo $doc->doc_name ?></strong></a>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

