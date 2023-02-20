/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports) {

(function ($) {
  'use strict';

  window.initTlpElTeam = function () {
    $('.rt-elementor-container').each(function () {
      var container = $(this),
          str = container.attr('data-layout'),
          html_loading = '<div class="rt-loading-overlay"></div><div class="rt-loading rt-ball-clip-rotate"><div></div></div>',
          preLoader = container.find('.ttp-pre-loader'),
          loader = container.find('.rt-content-loader'),
          IsotopeWrap = '',
          isIsotope = $('.tlp-team-isotope', container),
          IsoButton = $('.ttp-isotope-buttons', container),
          isCarousel = $('.rt-carousel-holder', container),
          remove_placeholder_loading = function remove_placeholder_loading() {
        container.find('.rt-content-loader').removeClass('element-loading'); // loader.find('.rt-loading').fadeOut(300);

        loader.find('.rt-loading, .rt-loading-overlay').fadeOut(100, function () {
          $(this).remove();
        });
        loader.removeClass('ttp-pre-loader');
      };

      if (str) {
        var buttonFilter;

        if (preLoader.find('.rt-loading-overlay').length == 0) {
          preLoader.append(html_loading);
        }

        if (isCarousel.length) {
          isCarousel.imagesLoaded(function () {
            rtSliderInit();
            $(document).on('rttm_slider_loaded', function () {
              isCarousel.removeClass('slider-loading');
              remove_placeholder_loading();
            });
          });
        } else if (isIsotope.length) {
          if (!buttonFilter) {
            buttonFilter = IsoButton.find('button.selected').data('filter');
          }

          IsotopeWrap = isIsotope.imagesLoaded(function () {
            preFunction();
            IsotopeWrap.isotope({
              itemSelector: '.isotope-item',
              masonry: {
                columnWidth: '.isotope-item'
              },
              filter: function filter() {
                return buttonFilter ? $(this).is(buttonFilter) : true;
              }
            });
            setTimeout(function () {
              IsotopeWrap.isotope();
              remove_placeholder_loading();
            }, 100);
          });
          IsoButton.on('click touchstart', 'button', function (e) {
            e.preventDefault();
            buttonFilter = $(this).attr('data-filter');
            IsotopeWrap.isotope();
            $(this).parent().find('.selected').removeClass('selected');
            $(this).addClass('selected');
          });
        } else {
          container.imagesLoaded(function () {
            preFunction();
            remove_placeholder_loading();
          });
        }
      }
    });
  };

  $(document).on('ready', function () {
    initTlpElTeam();
  });
  $(window).on('load resize', function () {
    HeightResize();
  });

  function preFunction() {
    HeightResize();
  }

  function HeightResize() {
    var wWidth = $(window).width();
    $(".rt-elementor-container[data-layout*='isotope']").each(function () {
      var self = $(this),
          dCol = self.data('desktop-col'),
          tCol = self.data('tab-col'),
          mCol = self.data('mobile-col'),
          target = $(this).find('.rt-row.rt-content-loader.ttp-even');

      if (wWidth >= 992 && dCol > 1 || wWidth >= 768 && tCol > 1 || wWidth < 768 && mCol > 1) {
        target.imagesLoaded(function () {
          var tlpMaxH = 0;
          target.find('.even-grid-item').height('auto');
          target.find('.even-grid-item').each(function () {
            var $thisH = $(this).outerHeight();

            if ($thisH > tlpMaxH) {
              tlpMaxH = $thisH;
            }
          });
          target.find('.even-grid-item').height(tlpMaxH + 'px');
        });
      } else {
        target.find('.even-grid-item').height('auto');
      }
    });
  }

  function rtSliderInit() {
    $('.rttm-carousel-slider').each(function () {
      $(this).rttm_slider();
    });
  }

  var RttmSlider = function RttmSlider($slider) {
    this.$slider = $slider;
    this.slider = this.$slider.get(0);
    this.swiperSlider = this.slider.swiper || null;
    this.defaultOptions = {
      breakpointsInverse: true,
      observer: true,
      navigation: {
        nextEl: this.$slider.find('.swiper-button-next').get(0),
        prevEl: this.$slider.find('.swiper-button-prev').get(0)
      },
      pagination: {
        el: this.$slider.find('.swiper-pagination').get(0),
        type: 'bullets',
        clickable: true
      }
    };
    this.slider_enabled = 'function' === typeof Swiper;
    this.options = Object.assign({}, this.defaultOptions, this.$slider.data('options') || {});

    this.initSlider = function () {
      if (!this.slider_enabled) {
        return;
      }

      if (this.options.rtl) {
        this.$slider.attr('dir', 'rtl');
      }

      if (this.swiperSlider) {
        this.swiperSlider.parents = this.options;
        this.swiperSlider.update();
      } else {
        this.swiperSlider = new Swiper(this.$slider.get(0), this.options);
      }
    };

    this.imagesLoaded = function () {
      if (this.$slider.data('options').lazy) {
        this.$slider.trigger('rttm_slider_loaded', this);
        return;
      }

      var that = this;

      if (!$.isFunction($.fn.imagesLoaded) || $.fn.imagesLoaded.done) {
        this.$slider.trigger('rttm_slider_loading', this);
        this.$slider.trigger('rttm_slider_loaded', this);
        return;
      }

      this.$slider.imagesLoaded().progress(function (instance, image) {
        that.$slider.trigger('rttm_slider_loading', [that]);
      }).done(function (instance) {
        that.$slider.trigger('rttm_slider_loaded', [that]);
      });
    };

    this.start = function () {
      var that = this;
      this.$slider.on('rttm_slider_loaded', this.init.bind(this));
      setTimeout(function () {
        that.imagesLoaded();
      }, 1);
    };

    this.init = function () {
      this.initSlider();
    };

    this.rtSwiper = function () {
      return new Swiper(this.$slider.get(0), this.options);
    };

    this.start();
  };

  $.fn.rttm_slider = function () {
    new RttmSlider(this);
    return this;
  };
})(jQuery);

/***/ })
/******/ ]);