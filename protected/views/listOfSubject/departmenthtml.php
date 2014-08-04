
<div class="w-gallery type_slider">
    <div class="w-gallery-h">
        <div class="w-gallery-main">
            <div class="w-gallery-main-h flexslider flex-loading">
                <ul class="slides">
                    <li>
                        <img width="728" height="410" alt="" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/img/demo/department_1.jpg" />
                    </li>
                    <li>
                        <img width="728" height="410" alt="" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/img/demo/department_3.jpg" />
                    </li>
                </ul>
                <ul class="flex-direction-nav">
                    <li>
                        <a href="#" class="flex-prev">Previous</a>
                    </li>
                    <li>
                        <a href="#" class="flex-next">Next</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(window).ready(function() {
//$(document).ready(function(){
        jQuery(".flexslider").flexslider({
            directionalNav: false,
            controlNav: false,
            smoothHeight: true,
            start: function() {
                jQuery(".flexslider").removeClass(".flex-loading");
            }
        });
    });
</script>

<div class="g-cols">
    <div class="one-third" id="teacher_lead">
        <h3>Nhân sự lãnh đạo</h3>
    </div>
    <div class="two-thirds">

        <h3>Thông tin chi tiết</h3>

        <div class="w-tabs" id="detail_faculty">
            <div class="w-tabs-h">
                <div class="w-tabs-list">
                    <div class="w-tabs-item active">
                        <span class="w-tabs-item-icon"></span>
                        <span class="w-tabs-item-title">Lĩnh vực nghiên cứu</span>
                    </div>
                    <div class="w-tabs-item">
                        <span class="w-tabs-item-icon"></span>
                        <span class="w-tabs-item-title">Các bộ môn - phòng thí nghiệm</span>
                    </div>

                </div>
                <div class="w-tabs-section active">
                    <div class="w-tabs-section-title">
                        <span class="w-tabs-section-title-icon"></span>
                        <span class="w-tabs-section-title-text">Lĩnh vực nghiên cứu</span>
                        <span class="w-tabs-section-title-control"></span>
                    </div>
                    <div class="w-tabs-section-content" style="">
                        <div class="w-tabs-section-content-h" id="research">
<!--                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sagittis, sem quis lacinia faucibus, orci ipsum gravida tortor, vel interdum mi sapien ut justo. Nulla varius consequat magna, id molestie ipsum volutpat quis.</p>
                            <blockquote><p>At delectus doloremque dolores explicabo laudantium minima qui. Animi aperiam aspernatur atque debitis distinctio impedit inventore iure labore modi omnis, optio rerum ut veritatis voluptatum?</p></blockquote>
                            <p>Suspendisse consectetur fringilla suctus. Pellentesque ipsum erat, facilisis ut venenatis eu, sodales vel dolor. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>-->
                        </div>
                    </div>
                </div>

                <div class="w-tabs-section">
                    <div class="w-tabs-section-title">
                        <span class="w-tabs-section-title-icon"></span>
                        <span class="w-tabs-section-title-text">Các bộ môn - phòng thí nghiệm</span>
                        <span class="w-tabs-section-title-control"></span>
                    </div>
                    <div class="w-tabs-section-content">
                        <div class="w-tabs-section-content-h" id="faculty_lab">
                            
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
<!--cmt facebook-->
