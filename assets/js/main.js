(function($) {
	"use strict";

	var fullHeight = function() {
		$('.js-fullheight').css('height', $(window).height());
		$(window).resize(function(){
			$('.js-fullheight').css('height', $(window).height());
		});
	};
	fullHeight();

	$('#sidebarCollapse').on('click', function () {
      $('#sidebar').toggleClass('active');
  });

})(jQuery);

// Add active class to current sidebar link based on URL
document.addEventListener("DOMContentLoaded", function() {
    const currentPath = window.location.pathname;
    const sidebarLinks = document.querySelectorAll("#sidebar ul li a");
    
    sidebarLinks.forEach(link => {
        if (link.getAttribute("href") && currentPath.includes(link.getAttribute("href").replace('../', ''))) {
            link.parentElement.classList.add("active");
        }
    });
});
