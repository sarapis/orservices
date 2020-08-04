<footer>
	<div class="container">
  		<div class="footer-menu bg-primary-color">
			<div class="row">
				<div class="col-md-8 ">
					<h3 class="feedback-title m-0">{{$layout->contact_text}}</h3>
				</div>
				<div class="col-md-4 feedback-btn text-right">
					<a href="{{$layout->contact_btn_link}}" target="_blank" class="btn btn-raised btn-lg btn_darkblack">{{$layout->contact_btn_label}}</a>
				</div>
			</div>
		</div>
		<div class="footer-content text-white">
			{!! $layout->footer !!}
			<!-- 	<div class="row">
				<div class="col-sm-12 col-md-7 col-lg-7">
					<p>CONSUL, 2019&nbsp; |&nbsp; <a href="#"> Privacy Policy </a>&nbsp; | &nbsp;<a href="#"> Terms and conditions of use </a>&nbsp; | &nbsp;<a href="#"> Accessibility &nbsp;</a></p>
				</div>
				<div class="col-sm-12 col-md-5 col-lg-5 text-right">
					<p>2019  @Copyright All Reserved</p>
				</div>
			</div> -->
		</div>
    </div>
</footer>