<?php foreach ($user_detail_info as $user): ?>
    <div class="l-main-h">
        <div class="l-submain">
            <div class="l-submain-h">
                <div class="g-cols">
                    <div class="one-third">
                        <div class="g-cols">
                            <div class="one-third">
                                <a href=""><img class="circular float_left" src="<?php
                                    echo $user->user_avatar;
                                    ?>"/></a>
                            </div>
                            <div class="two-thirds">
                                <div  class="clearfix" style="line-height: 20px;">
                                    <h4 style="font-size: 20px"><strong><?php echo $user['user_real_name'] ?></strong></h4>
                                    <i class="icon-home" style="float: left"></i>
                                    <p style="float: right"><?php echo $user['user_hometown'] ?></p>
                                    <br><br>
                                    <i class="icon-calendar" style="float: left"></i>
                                    <p style="float: right"> <?php echo $user['user_dob'] ?> </p>
                                </div>
                            </div>
                        </div>

                        <div class="underline"></div>
                    </div>

                    <div class="two-thirds">
                        <div class="w-tabs">
                            <div class="w-tabs-h">
                                <div class="w-tabs-list">
                                    <div class="w-tabs-item active">
                                        <span class="w-tabs-item-icon"></span>
                                        <span class="w-tabs-item-title">Tài liệu đã đăng</span>
                                    </div>

                                </div>
                                <div class="w-tabs-section active">
                                    <div class="w-tabs-section-title">
                                        <span class="w-tabs-section-title-icon"></span>
                                        <span class="w-tabs-section-title-text">Tài liệu đã đăng</span>
                                        <span class="w-tabs-section-title-control"></span>
                                    </div>
                                    <div class="w-tabs-section-content" style="">
                                        <div class="w-tabs-section-content-h">
                                            <div class="g-cols">
                                                <div class="full-width">
                                                    <ol class="list_document">
                                                        <?php if (count($user_doc_info) == 0) echo 'Người dùng chưa đăng tài liệu nào'; else foreach ($user_doc_info as $doc): ?>
                                                            <li class="item_document">
                                                                <div class="box_item">
                                                                    <div class="short_info_document clearfix">
                                                                        <div class="document_img">
                                                                            <img src="<?php echo $doc->doc_url ?>">
                                                                            <a href="<?php echo Yii::app()->createAbsoluteUrl('viewDocument?doc_id=') . $doc->doc_id ?>" class="document_img_hover">
                                                                                <span class="describe_document"><?php echo $doc->doc_description ?></span>
        <!--                                                                                <em class="timestamp"><i class="icon-time"></i>&nbsp;June 26, 2014</em>-->
                                                                            </a>
                                                                        </div>
                                                                        <!--                                                                        <ul class="document_status clearfix">
                                                                                                                                                    <li class="score"><i class="icon-heart"></i>2000</li>
                                                                                                                                                    <li class="view"><i class="icon-eye-open"></i>1999</li>
                                                                                                                                                    <li class="comment"><i class="icon-comment"></i>1203</li>
                                                                                                                                                </ul>-->

                                                                    </div>
                                                                </div>
                                                                <div class="name_document"><a class="name_document" href=""><strong><?php echo $doc['doc_name'] ?></strong></a></div>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ol>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!--cmt facebook-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
endforeach;
