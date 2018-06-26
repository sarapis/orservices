<footer class="footer-menu">
  	<div class="row footer-feedback">
    	<div class="col-md-8 text-center" style="padding-left: 85px;padding-right: 85px;">
    		<h3 class="feedback-title">{{$layout->contact_text}}</h3>
    	</div>
    	<div class="col-md-4 feedback-btn">
    		<button class="btn btn-block btn-feedback waves-effect waves-classic" style="padding: 0;"><a href="{{$layout->contact_btn_link}}" target="_blank" style="display: block;line-height: 45px;">{{$layout->contact_btn_label}}</a></button>
    	</div>
    </div>
    <div class="footer-content text-white" style="margin-right: -1.0715rem;margin-left: -1.0715rem;">
        {!! $layout->footer !!}
    </div>
</div>