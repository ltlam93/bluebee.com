
<?php foreach($detail_doc as $detail): ?>
<div id="content">
    <div class="l-submain">
        <div class="l-submain-h">
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
                        <a href="/sonvn" class="url_user" title="Sonvn">
                            <img class="photo_user" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/img/default-avatar.png" style="width: 50px; max-height: 50px">
                        </a>
                        <h1>
                            <span><?php echo $detail->doc_name ?></span>
                            <button href="#" class="g-btn type_primary size_small" style="float: right; text-transform: none; font-size: 14px; font-weight: normal;"><span>Scribd</span></button>
                            <p style="float: right">Môn học:</p>
                        </h1>
                        <div class="shot-byline">
                            <div class="attribution ">
                                <span class="shot-byline-user">
                                    by <a href="/Sonvn" class="url_user" title="Sonvn">Sonvn</a>
                                </span>
                            </div>
                            <span class="time_post">
                                Jul 17, 2014
                            </span>
                        </div>
                        <ul class="document_status clearfix" style="float:left; margin:2px 0 0 0;">
                            <li class="score" style="font-size: 14px;"><i class="icon-heart"></i>2000 likes</li>
                            <li class="view" style="font-size: 14px;"><i class="icon-eye-open"></i>1999 views</li>
                            <li class="comment" style="font-size: 14px;"><i class="icon-comment"></i>1203 comments</li>
                        </ul>
                    </div>
                    <div class="l-content">
                        <iframe class="scribd_iframe_embed" src="//www.scribd.com/embeds/<?php echo $detail->doc_scribd_id ?>/content?start_page=1&view_mode=scroll&access_key=key-1jb30g90t74etya1jsvh&show_recommendations=true" data-auto-height="false" data-aspect-ratio="0.75" scrolling="no" id="doc_76746" width="100%" height="600" frameborder="0"></iframe>
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
                            <li class="item_document">
                                <div class="box_item">
                                    <div class="short_info_document clearfix">
                                        <div class="document_img">
                                            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/img/demo/photo-11.jpg">
                                            <a href="/viewdocument" class="document_img_hover">
                                                <span class="describe_document">blah blah blah</span>
                                                <em class="timestamp"><i class="icon-time"></i>&nbsp;June 26, 2014</em>
                                            </a>
                                        </div>
                                        <ul class="document_status clearfix">
                                            <li class="score"><i class="icon-heart"></i>2000</li>
                                            <li class="view"><i class="icon-eye-open"></i>1999</li>
                                            <li class="comment"><i class="icon-comment"></i>1203</li>
                                        </ul>
                                        <span class="attribution-user">
                                            <a href="/sonvn" class="url_user" title="Sonvn">
                                                <img class="photo_user" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/img/default-avatar.png"> Sonvn
                                            </a>
                                        </span>
                                    </div>
                                </div>
                                <a class="name_document" href=""><strong>Công nghệ phần mềm</strong></a>
                            </li>
                            <li class="item_document">
                                <div class="box_item">
                                    <div class="short_info_document clearfix">
                                        <div class="document_img">
                                            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/img/demo/photo-11.jpg">
                                            <a href="/viewdocument" class="document_img_hover">
                                                <span class="describe_document">blah blah blah</span>
                                                <em class="timestamp"><i class="icon-time"></i>&nbsp;June 26, 2014</em>
                                            </a>
                                        </div>
                                        <ul class="document_status clearfix">
                                            <li class="score"><i class="icon-heart"></i>2000</li>
                                            <li class="view"><i class="icon-eye-open"></i>1999</li>
                                            <li class="comment"><i class="icon-comment"></i>1203</li>
                                        </ul>
                                        <span class="attribution-user">
                                            <a href="/sonvn" class="url_user" title="Sonvn">
                                                <img class="photo_user" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/img/default-avatar.png"> Sonvn
                                            </a>
                                        </span>
                                    </div>
                                </div>
                                <a class="name_document" href=""><strong>Công nghệ phần mềm</strong></a>
                            </li>
                            <li class="item_document">
                                <div class="box_item">
                                    <div class="short_info_document clearfix">
                                        <div class="document_img">
                                            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/img/demo/photo-11.jpg">
                                            <a href="/viewdocument" class="document_img_hover">
                                                <span class="describe_document">blah blah blah</span>
                                                <em class="timestamp"><i class="icon-time"></i>&nbsp;June 26, 2014</em>
                                            </a>
                                        </div>
                                        <ul class="document_status clearfix">
                                            <li class="score"><i class="icon-heart"></i>2000</li>
                                            <li class="view"><i class="icon-eye-open"></i>1999</li>
                                            <li class="comment"><i class="icon-comment"></i>1203</li>
                                        </ul>
                                        <span class="attribution-user">
                                            <a href="/sonvn" class="url_user" title="Sonvn">
                                                <img class="photo_user" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/img/default-avatar.png"> Sonvn
                                            </a>
                                        </span>
                                    </div>
                                </div>
                                <a class="name_document" href=""><strong>Công nghệ phần mềm</strong></a>
                            </li>
                    </div>
                </div>
            </div>
            <div>
                <h3>Bình luận</h3>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>