
<?php foreach ($detail_doc as $detail): ?>
 <link href="<?php echo $detail->doc_url ?>" rel="image_src"/>
    <div id="content">
        <div class="l-submain">
            <div class="l-submain-h">
                <p>Thông báo: Nếu bạn không xem được tài liệu đề nghị bạn thoát tài khoản google ra</p>
                <div class="g-cols">
                    <div style="margin-bottom: 20px">
                        <style>
                            .information_document {
                                min-height: 50px;
                                margin: 0 0 20px 0;
                                font-size: .9em;
                                position: relative;
                            }
                            .information_document h1 {
                                font-size: 23px;
                                line-height: 1.1;
                                font-weight: bold;
                                margin: 0 0 5px 60px;
                            }
                            .information_document h1 p{
                                font-size: 16px;
                                font-weight: normal;
                                margin: 10px 5px 0 0;
                            }
                            .shot-byline {
                                margin-left: 60px;
                                font-size: 13px;
                                line-height: 1.4;
                                color: #999;
                            }
                            .shot-byline-user {
                                float: left;
                            }
                            .time_post {
                                float: left;
                                margin: 2px 0 0 12px;
                                font-size: 12px;
                                color: #999;
                            }
                            .related_document {
                            }
                        </style>
                        <div class="information_document">
                            <a href="<?php echo Yii::app()->createUrl('user?id=') . $detail->doc_author ?>" class="url_user" title="<?php echo $detail->doc_author_name ?>">
                                <?php $user_info = User::model()->findByAttributes(array('user_id' => $detail->doc_author)) ?>
                                <img class="photo_user" src="<?php echo $user_info->user_avatar ?>" style="width: 50px; max-height: 50px">
                            </a>
                            <h1>
                                <span><?php echo $detail->doc_name ?></span>

                                <button onclick="window.location.href = '<?php echo "listOfSubject/subject?subject_id=" . $subject->subject_id ?>';" class="g-btn type_primary size_small" style="float: right; text-transform: none; font-size: 14px; font-weight: normal;"><span><?php echo $subject->subject_name ?></span></button>

                                <p style="float: right">Môn học:</p>
                            </h1>
                            <div class="shot-byline">
                                <div class="attribution ">
                                    <span class="shot-byline-user">
                                        đăng bởi <a href="<?php echo Yii::app()->createUrl('user?id=') . $detail->doc_author ?>" class="url_user" title="<?php echo $detail->doc_author_name ?>"><?php echo $detail->doc_author_name ?></a>
                                    </span>
                                </div>
    <!--                            <span class="time_post">
                                    Jul 17, 2014
                                </span>-->
                            </div>
                            <!--                        <ul class="document_status clearfix" style="float:left; margin:2px 0 0 0;">
                                                        <li class="score" style="font-size: 14px;"><i class="icon-heart"></i>2000 likes</li>
                                                        <li class="view" style="font-size: 14px;"><i class="icon-eye-open"></i>1999 views</li>
                                                        <li class="comment" style="font-size: 14px;"><i class="icon-comment"></i>1203 comments</li>
                                                    </ul>-->
                        </div>
                        <div class="fb-like" data-href="<?php echo Yii::app()->createAbsoluteUrl('viewDocument?doc_id=') . $detail->doc_id ?>" data-layout="standard" data-action="like" data-show-faces="false" data-share="true" style="margin-bottom: 10px;"></div>
                        </br>
                        <div class="l-content">
                            <?php
                            if ($detail->doc_type == 2) {
                                echo '<iframe src="http://docs.google.com/viewer?url=' . $detail->doc_path . '&embedded=true" height="600" style="border-radius: 3px; box-shadow: 0 0 2px rgba(0, 0, 0, 0.3); width: 100%; "></iframe></br></br>' . '<a href="' . $detail->doc_path . '"' . 'download="' . $detail->doc_name . '" style = "font-size:25px;"><button class="g-btn type_primary size_small">Download</button></a>';
                            } else {
                                if ($detail->doc_type == 1) {
                                    echo '<img style="width: auto; height: auto; " src="' . $detail->doc_path . '" /></br></br>' . '<a href="' . $detail->doc_path . '"' . 'download="' . $detail->doc_name . '" style = "font-size:25px;"><button class="g-btn type_primary size_small">Download</button></a>';
                                } else {
                                    if ($detail->doc_type == 3) {
                                        echo '<p>Tài liệu nén không xem trước được các bạn vui lòng bấm nút download để tải</p>';
                                        echo '<a href="' . $detail->doc_path . '"' . 'download="' . $detail->doc_name . '" style = "font-size:25px;"><button class="g-btn type_primary size_small">Download</button></a>';
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="one-third" style="margin-left: 0;">
                        <h3>Miêu tả về tài liệu:</h3>
                        <p><?php echo $detail->doc_description ?></p>
                    </div>
                    <div class="two-thirds">
                        <div class="related_document">
                            <span style="font-size: 16px; padding-bottom: 10px;">Tài liệu liên quan:</span>
                            <ol class="list_document">
    <?php foreach ($related_doc as $related_doc): ?>
                                    <li class="item_document">
                                        <div class="box_item">
                                            <div class="short_info_document clearfix">
                                                <div class="document_img">
                                                    <img src="<?php echo $related_doc->doc_url ?>"/>
                                                    <a href="<?php echo Yii::app()->createAbsoluteUrl('viewDocument?doc_id=') . $related_doc->doc_id ?>" action="" class="document_img_hover">
                                                        <span class="describe_document"><?php $related_doc->doc_description ?></span>
        <!--                                                <em class="timestamp"><i class="icon-time"></i>&nbsp;June 26, 2014</em>-->
                                                    </a>
                                                </div>
                                                <!--                                        <ul class="document_status clearfix">
                                                                                            <li class="score"><i class="icon-heart"></i>2000</li>
                                                                                            <li class="view"><i class="icon-eye-open"></i>1999</li>
                                                                                            <li class="comment"><i class="icon-comment"></i>1203</li>
                                                                                        </ul>-->
                                                <span class="attribution-user">
                                                    <a href="<?php echo Yii::app()->createUrl('user?id=') . $related_doc->doc_author ?>" class="url_user" title="<?php echo $related_doc->doc_author_name ?>">
                                                        <img class="photo_user" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/img/default-avatar.png"> <?php echo $related_doc->doc_author_name ?>
                                                    </a>
                                                </span>
                                            </div>
                                        </div>
                                        <a class="name_document" href=""><strong><?php echo $related_doc->doc_name ?></strong></a>
                                    </li>
    <?php endforeach; ?>
                            </ol>
                        </div>
                    </div>
                </div>
                <div>
                    <h3>Bình luận</h3>
                    <div class="fb-comments" data-href="<?php echo Yii::app()->createAbsoluteUrl('viewDocument?doc_id=') . $detail->doc_id ?>" data-width="1000" data-numposts="8" data-colorscheme="light"></div>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
