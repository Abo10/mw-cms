<?php
$contact_us_data = CFrontCategoryPost::GetDatas(9);

?>
<section class="row contact-form" id="contact">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="row section-header wow fadeInUp">
                    <h2><?= $contact_us_data['category_title'] ?></h2>
                    <div class="text-center section-descr"><?= $contact_us_data['category_content'] ?></div>
                </div>
                <form action="" id="org_c_form" method="POST" novalidate="novalidate">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <input type="text" name="your-name" class="form-control" placeholder="Name" required=""
                                   aria-required="true">
                        </div>
                        <div class="form-group col-sm-6">
                            <input type="email" name="your-email" class="form-control" placeholder="Email Address"
                                   required="" aria-required="true">
                        </div>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" name="your-message" placeholder="Message" required=""
                                  aria-required="true"></textarea>
                    </div>
                    <div class="form-group contact-button-g">
                        <button type="button" id="js-contact-btn" class="btn btn-pink btn-lg">SUBMIT</button>
                    </div>
                    <div id="js-contact-result" data-success-msg="Form submitted successfully."
                         data-error-msg="Oops. Something went wrong."></div>
                </form>
            </div>
        </div>
    </div>
</section>
<section class="row footer-section">
    <div class="container hidden-xs">
        <div class="row">
            <div class="col-md-4 col-xs-4">
                <h4 class="text-left">Terms and Conditions</h4>
            </div>
            <div class="col-md-4 col-xs-4" style="text-align: center">
                <a href="<?= CWebApp::$_pageProp['fb_link'] ?>" target="_blank">
                    <div class="social-cont">
                        <div class="social-img">
                            <i class="fa fa-facebook" aria-hidden="true"></i>
                        </div>
                    </div>
                </a>
                <a href="<?= CWebApp::$_pageProp['tw_link'] ?>" target="_blank">
                    <div class="social-cont">
                        <div class="social-img">
                            <i class="fa fa-twitter" aria-hidden="true"></i>
                        </div>
                    </div>
                </a>
                <a href="<?= CWebApp::$_pageProp['gg_link'] ?>" target="_blank">
                    <div class="social-cont">
                        <div class="social-img">
                            <i class="fa fa-google-plus" aria-hidden="true"></i>
                        </div>
                    </div>
                </a>
                <a href="<?= CWebApp::$_pageProp['yt_link'] ?>" target="_blank">
                    <div class="social-cont">
                        <div class="social-img">
                            <i class="fa fa-youtube" aria-hidden="true"></i>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-xs-4">
                <h4 class="text-right">© 2016 Moon by 1-Ring</h4>
            </div>
        </div>
    </div>
    <div class="container visible-xs">
        <div class="row">
            <div class="col-xs-12" style="text-align: center">
                <a href="<?= CWebApp::$_pageProp['fb_link'] ?>" target="_blank">
                    <div class="social-cont">
                        <div class="social-img">
                            <i class="fa fa-facebook" aria-hidden="true"></i>
                        </div>
                    </div>
                </a>
                <a href="<?= CWebApp::$_pageProp['tw_link'] ?>" target="_blank">
                    <div class="social-cont">
                        <div class="social-img">
                            <i class="fa fa-twitter" aria-hidden="true"></i>
                        </div>
                    </div>
                </a>
                <a href="<?= CWebApp::$_pageProp['gg_link'] ?>" target="_blank">
                    <div class="social-cont">
                        <div class="social-img">
                            <i class="fa fa-google-plus" aria-hidden="true"></i>
                        </div>
                    </div>
                </a>
                <a href="<?= CWebApp::$_pageProp['yt_link'] ?>" target="_blank">
                    <div class="social-cont">
                        <div class="social-img">
                            <i class="fa fa-youtube" aria-hidden="true"></i>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-xs-6">
                <h4 class="text-left ft-txt">Terms and Conditions</h4>
            </div>
            <div class="col-md-6 col-xs-6">
                <h4 class="text-right ft-txt">© 2016 Moon by 1-Ring</h4>
            </div>
        </div>
    </div>
</section>

<div id="subscr_success_modal" class="modal fade m-modal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <h2>Thank You !</h2>
                <p>You will get soon special offer link for Pre-order.</p>
                <div class="text-center s-check">
                    <i class="fa fa-check-circle" aria-hidden="true"></i>
                </div>
            </div>
        </div>

    </div>
</div>
<div id="subscr_modal" class="modal fade m-modal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <h2>Soon on <span>INDIEGOGO</span></h2>
                <div class="subscribe-text">
                    <p class="text-center">Subscribe to get special offer on Campaign start.</p>
                </div>
                <div class="subscribe-block-container">
                    <div class="input-group subscribe-block" data-callback="1">
                        <input type="text" class="form-control" placeholder="Email">

                        <div class="subscribe-mobile visible-xs">
                            <button type="button" class="btn btn-pink dropdown-toggle">
                                Subscribe
                            </button>
                        </div>
                        <div class="input-group-btn hidden-xs">
                            <button type="button" class="btn btn-pink dropdown-toggle">
                                Subscribe
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<div id="contact_modal" class="modal fade m-modal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <h2>Thank You !</h2>
                <p>We will contact You soon.</p>
            </div>
        </div>

    </div>
</div>
<div id="err_modal" class="modal fade m-modal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p class="msg"> asldkja sldkj alksd alskdj la</p>
            </div>
        </div>

    </div>
</div>