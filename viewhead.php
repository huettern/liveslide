<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="css/slide.css">
	<script type="text/javascript">
		function Slideshow( element ) {
			this.el = document.querySelector( element );
			this.init();
		}
		Slideshow.prototype = {
			init: function() {
				this.wrapper = this.el.querySelector( ".slider-wrapper" );
				this.slides = this.el.querySelectorAll( ".slide" );
				this.previous = this.el.querySelector( ".slider-previous" );
				this.next = this.el.querySelector( ".slider-next" );
				this.index = 0;
				this.total = this.slides.length;
				this.timer = null;

				this.action();
				this.stopStart();	
			},
			_slideTo: function( slide ) {
				var currentSlide = this.slides[slide];
				currentSlide.style.opacity = 1;

				for( var i = 0; i < this.slides.length; i++ ) {
					var slide = this.slides[i];
					if( slide !== currentSlide ) {
						slide.style.opacity = 0;
					}
				}
			},
			action: function() {
				var self = this;
				self.timer = setInterval(function() {
					self.index++;
					if( self.index == self.slides.length ) {
						self.index = 0;
					}
					self._slideTo( self.index );

				}, 7000);
			},
			stopStart: function() {
				var self = this;
				self.el.addEventListener( "mouseover", function() {
					clearInterval( self.timer );
					self.timer = null;

				}, false);
				self.el.addEventListener( "mouseout", function() {
					self.action();

				}, false);
			}
		};
	</script>
</head>