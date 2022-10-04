//togel-icon
// document.addEventListener('DOMContentLoaded', function() {
// 'use strict';
// var link = document.querySelector('[data-toggle-menu]');
// link.addEventListener('click', function() {
//     if (link.classList.contains('toggle-menu--clicked')) {
//     link.classList.remove('toggle-menu--clicked');
//     } else {
//     link.classList.add('toggle-menu--clicked');
//     }
// }, false);
// }, false);

//form-reset
function loader_show() {
  var element = document.getElementById("full_page_loader");
  element.classList.remove("d-none");
}

function loader_hide() {
  var element = document.getElementById("full_page_loader");
  element.classList.add("d-none");
}

function myFunction() {
  document.getElementById("thomsenform").reset();
}


$('body').on('hide.bs.modal', '.modal', function () {
  const $modal = $(this);
  // return early if there were no embedded YouTube videos
  if ($modal.find('iframe').length === 0) return;
  const html = $modal.html();
  $modal.html(html);
});

//banner-slider
jQuery('.owl-carousel').owlCarousel({
  loop: false,
  margin: 10,
  autoplay: true,
  nav: true,
  dots: false,
  slideSpeed: 300,
  paginationSpeed: 200,
  animateIn: 'fadeIn',
  mouseDrag: false,
  paginationSpeed: 200,
  transitionStyle: "fade",
  animateIn: 'fadeIn',
  animateOut: 'fadeOut',
  responsive: {
    0: {
      items: 1
    }
  }
})


//icon-fill
// $('.heart-icon-box').on('click', function () {
//   $(this).toggleClass('heart');
// });
// $('.roperty-detail-icon').on('click', function () {
//   $(this).toggleClass('heart');
// });


//sticky header
jQuery(window).scroll(function () {
  var scroll = jQuery(window).scrollTop();

  if (scroll >= 150) {
    jQuery("header").addClass("sticky-header");
  } else {
    jQuery("header").removeClass("sticky-header");
  }
});


//footer
jQuery('.footer-box-url h4').click(function () {
  if (jQuery(window).width() < 575) {
    jQuery(this).next().slideToggle(300);
    jQuery(this).toggleClass("active");
  }
});

//RANGE SLIDER
$(document).ready(function () {
  let minSlider = parseInt($("#price").data('min'));
  let maxSlider = parseInt($("#price").data('max'));
  let minSliderVal = parseInt($("#price").data('val_min'));
  let maxSliderVal = parseInt($("#price").data('val_max'));
  let steps = parseInt($("#price").data('steps'));
  let currency = $("#price").data('curr');
  
  if(!currency) {
    currency = 'KD';
  }

  $("#slider-3").slider({
    range: true,
    min: minSlider,
    max: maxSlider,
    values: [minSliderVal, maxSliderVal],
    step: steps ? steps : 10,
    slide: function (event, ui) {
      $("#price").val(ui.values[0] + ' ' + currency + " - " + ui.values[1] + " " + currency);
      $("#min_price_text").text(ui.values[0] + ' ' + currency);
      $("#max_price_text").text(ui.values[1] + ' ' + currency);

      $("#price").attr('data-val_min',ui.values[0]);
      $("#price").attr('data-val_max',ui.values[1]);

    },
    stop : function(event, ui) {
      let callfunction = $(this).data('onstopcallback');
      window[callfunction](event, ui);
    }
  });
  $("#price").val($("#slider-3").slider("values", 0) + ' ' + currency + " - " + $("#slider-3").slider("values", 1) + " " + currency);
  $("#min_price_text").text($("#slider-3").slider("values", 0) + ' ' + currency);
  $("#max_price_text").text($("#slider-3").slider("values", 1) + " " + currency);
});

//show hide
$(".list-view-map").click(function () {
  $(".featured-list").hide();
});

$(".list-view-map").click(function () {
  $(".featured-map-view").show();
});

$(".list-view-box").click(function () {
  $(".featured-map-view").hide();
});

$(".list-view-box").click(function () {
  $(".featured-list").show();
});

//add-remove-class
$(".list-view-box").click(function () {
  $(".list-view-map").removeClass("active");
});
$(".list-view-box").click(function () {
  $(this).addClass("active");
});

$(".list-view-map").click(function () {
  $(".list-view-box").removeClass("active");
});
$(".list-view-map").click(function () {
  $(this).addClass("active");
});


//fillter 
$(".fillter-show").click(function () {
  $(".filter-box-outer").show();
});

$(".fillter-close").click(function () {
  $(".filter-box-outer").hide();
});

//IMAGE SLIDER

//toglle password

$(".toggle-password").click(function () {
  $(this).toggleClass("show-hide-password");
  input = $(this).parent().find("input");
  if (input.attr("type") == "password") {
    input.attr("type", "text");
  } else {
    input.attr("type", "password");
  }
});

$(document).ready(function () {
  var list = $(".amenities-items li");
  var numToShow = 10;
  var button = $(".show-all");
  var numInList = list.length;
  list.hide();
  if (numInList > numToShow) {
    button.show();
  }
  list.slice(0, numToShow).show();

  button.click(function () {
    var showing = list.filter(':visible').length;
    list.slice(showing - 1, showing + numToShow).fadeIn();
    var nowShowing = list.filter(':visible').length;
    if (nowShowing >= numInList) {
      button.hide();
    }
  });

});

///add class
// $(function() {                       
//   $(".add-property-field li").click(function() { 
//     $(this).toggleClass("active");     
//   });
// });

//checked
$(document).ready(function () {
  $('.add-property-field input').click(function () {
    $('.add-property-field input:not(:checked)').parent().removeClass("checked1");
    $('.add-property-field input:checked').parent().addClass("checked1");
  });
});

$(".edit-detail-box .uploaded-img-box span").click(function () {
  $(".uploaded-img-box").hide();
});

$(".edit-detail-box .uploaded-img-box span").click(function () {
  $(".featured-map-view").show();
});

///
$(document).ready(function () {

  var list = $(".property-type-box .form-check");
  var numToShow = 7;
  var button = $(".filter-box-view-all");
  var numInList = list.length;
  list.hide();
  if (numInList > numToShow) {
    button.removeClass('d-none');
  }
  list.slice(0, numToShow).show();

  button.click(function (e) {
    e.preventDefault();
    var showing = list.filter(':visible').length;
    list.slice(showing - 1, showing + numToShow).fadeIn();
    var nowShowing = list.filter(':visible').length;
    if (nowShowing >= numInList) {
      button.addClass('d-none');
    }
  });

  loader_hide();

  $('#search_form').on('submit', function(e) {
    e.preventDefault();
  });

});

function changeLanguage(e, element, lang_id) {
  loader_show();
  e.preventDefault();
  let __call = $(element).data('sendto');
  $.ajax({
    url: __call,
    type: "post",
    data: { "lang_id": lang_id },
    success: function (response) {
      setTimeout(() => {
        location.reload();
      }, 900);
    },
    error: function (error) {
      console.error({ error });
      loader_hide();
    }
  })
}


var div  = document.getElementById("location");
function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition, showError);
  } else {
    div.innerHTML = "The Browser Does not Support Geolocation";
  }
}

function showPosition(position) {
  // div.innerHTML = "Latitude: " + position.coords.latitude + "<br>Longitude: " + position.coords.longitude;
  document.getElementById('cur_latitude').value = position.coords.latitude;
  document.getElementById('cur_longitude').value = position.coords.longitude;
}

function showError(error) {
  if(error.PERMISSION_DENIED){
      // div.innerHTML = "The User have denied the request for Geolocation.";
  }
}
getLocation();