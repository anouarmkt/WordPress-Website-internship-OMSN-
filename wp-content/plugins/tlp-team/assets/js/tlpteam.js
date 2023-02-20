(function ($) {
	'use strict';
	var mdModalWrap = $("#tlp-modal");
	var elementThumbInstances = [];
	window.initTlpTeam = function () {
		$(".rt-team-container").each(function (index) {
			var container = $(this),
				str = container.attr("data-layout"),
				popupBg = container.attr("data-popup-bg"),
				id = $.trim(container.attr('id')),
				randId = id.replace("rt-team-container-", ""),
				scID = $.trim(container.attr("data-sc-id")),
				mdPopup = $('.ttp-single-md-popup', container),
				multiPopup = $('.ttp-multi-popup', document),
				$default_order_by = $('.rt-order-by-action .order-by-default', container),
				$default_order = $('.rt-sort-order-action .rt-sort-order-action-arrow', container),
				$taxonomy_filter = $('.rt-filter-item-wrap.rt-tax-filter', container),
				$pagination_wrap = $('.rt-pagination-wrap', container),
				$loadmore = $('.rt-loadmore-action', container),
				$infinite = $('.rt-infinite-action', container),
				$page_prev_next = $('.rt-cb-page-prev-next', container),
				$page_numbers = $('.rt-page-numbers', container),
				html_loading = '<div class="rt-loading-overlay"></div><div class="rt-loading rt-ball-clip-rotate"><div></div></div>',
				preLoader = container.find('.ttp-pre-loader'),
				loader = container.find(".rt-content-loader"),
				contentLoader = container.children(".rt-row.rt-content-loader"),
				search_wrap = container.find(".rt-search-filter-wrap"),
				ttp_order = '',
				ttp_order_by = '',
				ttp_taxonomy = '',
				ttp_term = '',
				ttp_search = '',
				ttp_paged = 1,
				temp_total_pages = parseInt($pagination_wrap.attr('data-total-pages'), 10),
				ttp_total_pages = typeof (temp_total_pages) != 'undefined' && temp_total_pages != '' ? temp_total_pages : 1,
				temp_posts_per_page = parseInt($pagination_wrap.attr('data-posts-per-page'), 10),
				ttp_posta_per_page = typeof (temp_posts_per_page) != 'undefined' && temp_posts_per_page != '' ? temp_posts_per_page : 3,
				infinite_status = 0,
				paramsRequest = {},
				mIsotopeWrap = '',
				IsotopeWrap = '',
				isMasonry = $('.rt-row.rt-content-loader.tpg-masonry', container),
				isIsotope = $(".tlp-team-isotope", container),
				IsoButton = $(".ttp-isotope-buttons", container),
				IsoDropDownFilter = $("select.isotope-dropdown-filter", container),
				isCarousel = $('.rt-carousel-holder', container),
				caroThumb = $('.rttm-carousel-main', container),
				isSpecial = $('.rt-special-wrapper', container),
				placeholder_loading = function () {
					if (loader.find('.rt-loading-overlay').length == 0) {
						loader.addClass('ttp-pre-loader');
						loader.append(html_loading);
					}
				},
				remove_placeholder_loading = function () {
					loader.find('.rt-loading').fadeOut(300);
					loader.removeClass('ttp-pre-loader');
					$loadmore.removeClass('rt-lm-loading');
					$page_numbers.removeClass('rt-lm-loading');
					$infinite.removeClass('rt-active-elm');
					search_wrap.find('input').prop("disabled", false);
				},
				check_query = function () {
					if ($taxonomy_filter.length > 0) {
						ttp_taxonomy = $taxonomy_filter.attr('data-taxonomy');
						var term;
						if ($taxonomy_filter.hasClass('rt-filter-button-wrap')) {
							term = $taxonomy_filter.find('.rt-filter-button-item.selected').attr('data-term');
						} else {
							term = $taxonomy_filter.find('.term-default').attr('data-term');
						}
						if (typeof (term) != 'undefined' && term != '') {
							ttp_term = term;
						}
					}
					if ($default_order_by.length > 0) {
						var order_by_param = $default_order_by.attr('data-order-by');
						if (typeof (order_by_param) != 'undefined' && order_by_param != '' && (order_by_param.toLowerCase())) {
							ttp_order_by = order_by_param;
						}
					}
					if ($default_order_by.length > 0) {
						var order_param = $default_order.attr('data-sort-order');
						if (typeof (order_param) != 'undefined' && order_param != '' && (order_param == 'DESC' || order_param == 'ASC')) {
							ttp_order = order_param;
						}
					}
					if (search_wrap.length > 0) {
						ttp_search = $.trim(search_wrap.find('input').val());
					}
					paramsRequest = {
						'scID': scID,
						'order': ttp_order,
						'order_by': ttp_order_by,
						'taxonomy': ttp_taxonomy,
						'term': ttp_term,
						'paged': ttp_paged,
						'action': 'ttp_Layout_Ajax_Action',
						'search': ttp_search,
						'tlp_nonce': ttp.nonce
					};
				},
				infinite_scroll = function () {
					if (infinite_status == 1 || $infinite.hasClass('rt-hidden-elm') || $pagination_wrap.length == 0) {
						return;
					}
					var ajaxVisible = $pagination_wrap.offset().top,
						ajaxScrollTop = $(window).scrollTop() + $(window).height();

					if (ajaxVisible <= (ajaxScrollTop) && (ajaxVisible + $(window).height()) > ajaxScrollTop) {
						infinite_status = 1; //stop inifite scroll
						ttp_paged = ttp_paged + 1;
						$infinite.addClass('rt-active-elm');
						ajax_action(true, true);
					}
				},
				generateData = function (number) {
					var result = [];
					for (var i = 1; i < number + 1; i++) {
						result.push(i);
					}
					return result;
				},
				createPagination = function () {
					if ($page_numbers.length > 0) {
						$page_numbers.pagination({
							dataSource: generateData(ttp_total_pages * parseFloat(ttp_posta_per_page)),
							pageSize: parseFloat(ttp_posta_per_page),
							autoHidePrevious: true,
							autoHideNext: true,
							prevText: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>',
							nextText: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>'
						});
						$page_numbers.addHook('beforePaging', function (pagination) {
							infinite_status = 1;
							ttp_paged = pagination;
							$page_numbers.addClass('rt-lm-loading');
							$page_numbers.pagination('disable');
							ajax_action(true, false);
						});
						if (ttp_total_pages <= 1) {
							$page_numbers.addClass('rt-hidden-elm');
						} else {
							$page_numbers.removeClass('rt-hidden-elm');
						}
					}
				},
				getItemsArray = function (contentLoader) {
					return contentLoader.find('.rt-grid-item').map(function () {
						var _self = $(this);
						if (_self.is(":visible")) {
							return _self.attr("data-id");
						}
					}).get();
				},
				animateAction = function () {
					var $postItem = $('.rt-grid-item:not(.rt-ready-animation, .isotope-item)', container);
					$postItem.addClass('rt-ready-animation animated fadeIn');
				},
				ajax_action = function (page_request, append) {
					page_request = page_request || false;
					append = append || false;
					if (!page_request) {
						ttp_paged = 1;
					}
					check_query();
					if (page_request == true && ttp_total_pages > 1 && paramsRequest.paged > ttp_total_pages) {
						remove_placeholder_loading();
						return;
					}
					$.ajax({
						url: ttp.ajaxurl,
						type: 'POST',
						data: paramsRequest,
						cache: false,
						beforeSend: function () {
							placeholder_loading();
						},
						success: function (data) {
							if (!data.error) {
								ttp_paged = data.paged;
								ttp_total_pages = data.total_pages;
								if (data.paged >= ttp_total_pages) {
									if ($loadmore.length) {
										$loadmore.addClass('rt-hidden-elm');
									}
									if ($infinite.length) {
										infinite_status = 1;
										$infinite.addClass('rt-hidden-elm');
									}
									if ($page_prev_next.length) {
										if (!page_request) {
											$page_prev_next.addClass('rt-hidden-elm');
										} else {
											$page_prev_next.find('.rt-cb-prev-btn').removeClass('rt-disabled');
											$page_prev_next.find('.rt-cb-next-btn').addClass('rt-disabled');
										}
									}
								} else {
									if ($loadmore.length) {
										$loadmore.removeClass('rt-hidden-elm');
									}
									if ($infinite.length) {
										infinite_status = 0;
										$infinite.removeClass('rt-hidden-elm');
									}
									if ($page_prev_next.length) {
										if (!page_request) {
											$page_prev_next.removeClass('rt-hidden-elm');
										} else {
											if (data.paged == 1) {
												$page_prev_next.find('.rt-cb-prev-btn').addClass('rt-disabled');
												$page_prev_next.find('.rt-cb-next-btn').removeClass('rt-disabled');
											} else {
												$page_prev_next.find('.rt-cb-prev-btn').removeClass('rt-disabled');
												$page_prev_next.find('.rt-cb-next-btn').removeClass('rt-disabled');
											}
										}
									}
								}
								if (append) {
									if (isIsotope.length) {
										IsotopeWrap.append(data.data)
											.isotope('appended', data.data)
											.isotope('reloadItems')
											.isotope('updateSortData')
											.isotope();
										IsotopeWrap.imagesLoaded(function () {
											preFunction();
											IsotopeWrap.isotope();
										});
									} else if (isMasonry.length) {
										mIsotopeWrap.append(data.data).isotope('appended', data.data).isotope('updateSortData').isotope('reloadItems');
										mIsotopeWrap.imagesLoaded(function () {
											mIsotopeWrap.isotope();
										});
									} else {
										contentLoader.append(data.data);
									}
								} else {
									if (isMasonry.length) {
										mIsotopeWrap.html(data.data);
										mIsotopeWrap.imagesLoaded(function () {
											mIsotopeWrap.isotope();
										});
									} else {
										contentLoader.html(data.data);
									}
								}
								contentLoader.imagesLoaded(function () {
									preFunction();
									remove_placeholder_loading();
								});
								if (!page_request) {
									createPagination();
								}
								animateAction();
							} else {
								remove_placeholder_loading();
							}
						},
						error: function () {
							remove_placeholder_loading();
						}
					});
					if ($('.paginationjs-pages .paginationjs-page', $page_numbers).length > 0) {
						$page_numbers.pagination('enable');
					}
				},
				specialLayoutEqualHeight = function () {
					var target = $('.special-selected-top-wrap > .img');
					target.imagesLoaded(function () {
						var imgHeight = target.outerHeight();
						$('.special-selected-top-wrap .ttp-label, .special-selected-bottom-wrap').height(imgHeight);
					});
				},
				scrollToTopMember = function () {
					if ($(window).width() < 768) {
						$('html, body').animate({
							scrollTop: $('#special-selected-wrapper').offset().top - 35
						}, 200);
					}
				};

			switch ($pagination_wrap.attr('data-type')) {
				case 'load_more':
					$loadmore.on('click', function () {
						$(this).addClass('rt-lm-loading');
						ttp_paged = ttp_paged + 1;
						ajax_action(true, true);
					});
					break;
				case 'pagination_ajax':
					createPagination();
					break;
				case 'pagination':
					break;
				case 'load_on_scroll':
					$(window).on('scroll load', function () {
						infinite_scroll();
					});
					break;
				case 'page_prev_next':
					if (ttp_paged == 1) {
						$page_prev_next.find('.rt-cb-prev-btn').addClass('rt-disabled');
					}
					if (ttp_paged == ttp_total_pages) {
						$page_prev_next.find('.rt-cb-next-btn').addClass('rt-disabled');
					}
					if (ttp_total_pages == 1) {
						$page_prev_next.addClass('rt-hidden-elm');
					}
					break;
			}

			if (str) {
				var qsRegex, buttonFilter;
				if (preLoader.find('.rt-loading-overlay').length == 0) {
					preLoader.append(html_loading);
				}

				if (isCarousel.length) {
					isCarousel.imagesLoaded(function () {
						if (str === "carousel10") {
							var carouselOptions = caroThumb.data('options');

							RTElementThumbCarousel(container, carouselOptions, elementThumbInstances, index);
							remove_placeholder_loading();
						} else {
							rtSliderInit($);
						}

						$(document).on('rttm_slider_loaded', function () {
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
							masonry: { columnWidth: '.isotope-item' },
							filter: function () {
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

				} else if (isSpecial.length) {
					var selected = container.find('.special-items-wrapper .rt-grid-item.selected'),
						selectedId = selected.attr('data-id');
					selected.remove();
					target = container.find('#special-selected-wrapper');
					if (selectedId) {
						$.ajax({
							url: ttp.ajaxurl,
							type: 'POST',
							data: { memberId: selectedId, scID: scID, action: 'rtGetSpecialLayoutData' },
							cache: false,
							beforeSend: function () {
								placeholder_loading();
							},
							success: function (data) {
								if (!data.error) {
									target.html(data.data);
									target.attr('data-id', selectedId);
									specialLayoutEqualHeight();
									remove_placeholder_loading();
								}
							},
							error: function () {
								remove_placeholder_loading();
							}
						}
						);
					}

					$(document).on('click', '.single-team-item.image-wrapper', function () {
						var self = $(this),
							id = self.attr('data-id'),
							toggleId = target.attr('data-id');
						$.ajax({
							url: ttp.ajaxurl,
							type: 'POST',
							data: { memberId: id, toggleId: toggleId, scID: scID, action: 'rtGetSpecialLayoutData' },
							cache: false,
							beforeSend: function () {
								placeholder_loading();
							},
							success: function (data) {
								if (!data.error) {
									target.attr('data-id', id);
									target.html(data.data);
									self.attr('data-id', toggleId);
									self.parent().attr('data-id', toggleId);
									self.html("<img class='img-responsive rt-profile-img' src='" + data.toggle_image_src + "' />");
									specialLayoutEqualHeight();
									remove_placeholder_loading();
									scrollToTopMember();
								}
							},
							error: function () {
								remove_placeholder_loading();
							}
						}
						);
					});

					$(window).on('resize', function () {
						specialLayoutEqualHeight();
					});

				} else if (container.find('.rt-row.rt-content-loader.ttp-masonry').length) {
					var masonryTarget = $('.rt-row.rt-content-loader.ttp-masonry', container);
					mIsotopeWrap = masonryTarget.imagesLoaded(function () {
						preFunction();
						mIsotopeWrap.isotope({
							itemSelector: '.masonry-grid-item',
							masonry: { columnWidth: '.masonry-grid-item' }
						});
						remove_placeholder_loading();
					});
				} else {
					var target = $('.rt-row.rt-content-loader.ttp-masonry', container);
					target.imagesLoaded(function () {
						preFunction();
						remove_placeholder_loading();
					});
				}
			}

			$('#' + id).on('click', '.rt-search-filter-wrap .rt-action', function (e) {
				search_wrap.find('input').prop("disabled", true);
				ajax_action();
			});
			$('#' + id).on('keypress', '.rt-search-filter-wrap .rt-search-input', function (e) {
				if (e.which == 13) {
					search_wrap.find('input').prop("disabled", true);
					ajax_action();
				}
			});
			$('#' + id).on('click', '.rt-filter-dropdown-wrap', function (event) {
				var self = $(this);
				self.toggleClass('active-dropdown');
			});// Dropdown click
			$('#' + id).on('click', '.term-dropdown-item', function (event) {
				$loadmore.addClass('rt-lm-loading');
				var $this_item = $(this),
					default_target = $taxonomy_filter.find('.rt-filter-dropdown-default'),
					old_param = default_target.attr('data-term'),
					old_text = default_target.find('.rt-text').html();
				$this_item.parents('.rt-filter-dropdown-wrap').removeClass('active-dropdown');
				$this_item.parents('.rt-filter-dropdown-wrap').toggleClass('active-dropdown');
				default_target.attr('data-term', $this_item.attr('data-term'));
				default_target.find('.rt-text').html($this_item.html());
				$this_item.attr('data-term', old_param);
				$this_item.html(old_text);
				ajax_action();
			});//term
			$('#' + id).on('click', '.order-by-dropdown-item', function (event) {
				$loadmore.addClass('rt-lm-loading');
				var $this_item = $(this),
					old_param = $default_order_by.attr('data-order-by'),
					old_text = $default_order_by.find('.rt-text-order-by').html();

				$this_item.parents('.rt-order-by-action').removeClass('active-dropdown');
				$this_item.parents('.rt-order-by-action').toggleClass('active-dropdown');
				$default_order_by.attr('data-order-by', $this_item.attr('data-order-by'));
				$default_order_by.find('.rt-text-order-by').html($this_item.html());
				$this_item.attr('data-order-by', old_param);
				$this_item.html(old_text);
				ajax_action();
			});//Order By

			//Sort Order
			$('#' + id).on('click', '.rt-sort-order-action', function (event) {
				$loadmore.addClass('rt-lm-loading');
				var $this_item = $(this),
					$sort_order_elm = $('.rt-sort-order-action-arrow', $this_item),
					sort_order_param = $sort_order_elm.attr('data-sort-order');
				if (typeof (sort_order_param) != 'undefined' && sort_order_param.toLowerCase() == 'desc') {
					$default_order.attr('data-sort-order', 'ASC');
				} else {
					$default_order.attr('data-sort-order', 'DESC');
				}
				ajax_action();
			});//Sort Order

			$taxonomy_filter.on('click touchstart', '.rt-filter-button-item', function () {
				var self = $(this);
				self.parents('.rt-filter-button-wrap').find('.rt-filter-button-item').removeClass('selected');
				self.addClass('selected');
				ajax_action();
			});

			$page_prev_next.on('click', '.rt-cb-prev-btn', function (event) {
				if (ttp_paged <= 1) {
					return;
				}
				ttp_paged = ttp_paged - 1;
				ajax_action(true, false);
			});
			$page_prev_next.on('click', '.rt-cb-next-btn', function (event) {
				if (ttp_paged >= ttp_total_pages) {
					return;
				}
				ttp_paged = ttp_paged + 1;
				ajax_action(true, false);
			});

			// md Popup
			$(document).on('click', '.ttp-single-md-popup', function (e) {
				e.preventDefault();
				var id = $(this).attr("data-id");
				var data = "action=tlp_md_popup_single&id=" + id;
				var modalWrapper = ".tlp-modal-" + scID;

				$.ajax({
					type: "post",
					url: ttp.ajaxurl,
					data: data,
					beforeSend: function () {
						mdModalWrap.addClass("tlp-modal-" + scID);
						// mdModalWrap.addClass("tlp-modal-" + randId);
						mdModalWrap.addClass('md-show');
						mdModalWrap.find('.tlp-md-content-holder').html('<div class="tlp-md-loading">Loading...</div>');
						var modalWrapper = ".tlp-modal-" + scID;
						// var modalWrapper = ".tlp-modal-" + randId;
						$(modalWrapper + " .md-content").css("background-color", popupBg);
						// $(modalWrapper + " .tlp-md-content").css("background-color", popupBg);

					},
					success: function (data) {
						mdModalWrap.find('.tlp-md-content-holder').html(data.data);
					},
					complete: function () {
						// $(modalWrapper + " .md-content > .tlp-md-content-holder .tlp-md-content").css("background-color", popupBg);
					},
					error: function (e) {
						console.log(e);
					}
				});
				return false;
			});

			// Smart Popup
			$(document).on('click', '.ttp-smart-popup', function (e) {
				e.preventDefault();
				var self = $(this),
					id = $(this).attr("data-id"),
					container = self.parents('.rt-team-container'),
					containerId = container.attr("data-sc-id"),
					contentLoader = $('.rt-row.rt-content-loader', container),
					smartModal = {
						currentId: id,
						ids: getItemsArray(contentLoader) || [],
						wrap: '',
						wrapper: "<div id='rt-smart-modal-container' class='rt-modal-" + containerId + "'><div class='rt-smart-modal-main'>" +
							"<div class='rt-smart-modal-header'>" +
							"<span class='rt-smart-modal-nav'>" +
							"<a href='#' class='rt-smart-nav-left rt-smart-nav-item'><i class='fa fa-angle-left' aria-hidden='true'></i></a>" +
							"<a href='#' class='rt-smart-nav-right rt-smart-nav-item'><i class='fa fa-angle-right' aria-hidden='true'></i></a>" +
							"</span>" +
							"<a href='#' class='rt-smart-modal-close'><i class='fa fa-times'> </i></a>" +
							"</div>" +
							"<div class='rt-smart-modal'><div class='rt-smart-modal-main-content-wrapper'></div></div>" +
							"</div></div>",
						setWrap: function () {
							this.wrap = $('body > #rt-smart-modal-container');
							return this;
						},
						addModal: function () {
							$('html').addClass('rt-smart-modal-on');
							this.setWrap();
							if (!this.wrap.length) {
								$('body').append(this.wrapper);
								this.setWrap();
							}
							if (!this.wrap.hasClass('ready')) {
								this.wrap.append("<span class='rt-spinner'></span>");
							}
							this.wrap.addClass('open');
						},
						removeModal: function () {
							$('html').removeClass('rt-smart-modal-on');
							this.wrap.removeClass('open loading ready').find('.rt-smart-modal-main-content-wrapper').html('');
						},
						addLoading: function () {
							this.wrap.addClass('loading').find('.rt-smart-modal-main-content-wrapper').html('<span class="rt-spinner"></span>');
							return this;
						},
						removeLoading: function () {
							this.wrap.removeClass('loading').find('.rt-spinner').remove();
							return this;
						},
						addData: function (data) {
							this.wrap.find('.rt-smart-modal-main-content-wrapper').html(data);
							return this;
						},
						setNextID: function () {
							var index = this.ids.indexOf(this.currentId);
							index++;
							if (index >= this.ids.length)
								index = 0;
							this.currentId = this.ids[index];
						},
						setPrevId: function () {
							var index = this.ids.indexOf(this.currentId);
							index--;
							if (index < 0)
								index = this.ids.length - 1;
							this.currentId = this.ids[index];
						},
						renderScript: function () {
							$('.tlp-tooltip', this.wrap).rtTooltip();
							$('.tlp-team-skill', this.wrap).find('.fill').css('width', '0%');
							$('.tlp-team-skill .fill', this.wrap).each(function () {
								var k = 0, f = $(this), p = f.attr('data-progress-animation'), w = f.width();
								if (w == 0) {
									p = p.substring(0, p.length - 1);
									var go = function () {
										return k >= p || k >= 100 ? (false) : (k += 1, f.css('width', k + '%'), setTimeout(go, 20))
									};
									go();
								}
							});
							rtSliderInit(jQuery);
							// this.wrap.find('.rt-smart-modal-main-content-wrapper').mCustomScrollbar();
							this.wrap.find('.rt-smart-modal-main-content').mCustomScrollbar();
							this.wrap.find('.rt-smart-modal-main-content').css('opacity', 1);
						},
						rightClick: function () {
							this.setNextID();
							this.requestData();
						},
						leftClick: function () {
							this.setPrevId();
							this.requestData();
						},
						requestData: function () {
							var data = {
								action: 'tlp_team_smart_popup',
								id: this.currentId
							},
								modal = this;
							data[ttp.nonceID] = ttp.nonce;
							$.ajax({
								type: "post",
								url: ttp.ajaxurl,
								data: data,
								beforeSend: function () {
									modal.addModal();
									modal.addLoading();
								},
								success: function (response) {
									modal.removeLoading();
									modal.addData(response.data);
									modal.wrap.addClass('ready');
									modal.renderScript();
								},
								error: function (e) {
									console.log(e);
								}
							});
						}
					};
				smartModal.requestData();
				smartModal.wrap.find('.rt-smart-modal-close').on('click', function (e) {
					e.preventDefault();
					smartModal.removeModal();
					return false;
				});

				smartModal.wrap.find('.rt-smart-nav-right').on('click', function (e) {
					e.preventDefault();
					smartModal.rightClick();
					return false;
				});
				smartModal.wrap.find('.rt-smart-nav-left').on('click', function (e) {
					e.preventDefault();
					smartModal.leftClick();
					return false;
				});

				return false;
			});

			$(document).on('click', '.ttp-multi-popup', function () {
				var self = $(this),
					current = self.attr("data-id"),
					contentLoader = self.parents('.rt-team-container').children('.rt-row.rt-content-loader'),
					itemArray = getItemsArray(contentLoader),
					data = "action=tlp_multi_popup_single&id=" + current,
					popupWrap, popupContainer;

				$.ajax({
					type: "post",
					url: ttp.ajaxurl,
					data: data,
					beforeSend: function () {
						initPopupTeamPro();
						setLevelTeamPro(current, itemArray);
						popupWrap = $("#tlp-popup-wrap");
						popupWrap.addClass("tlp-popup-wrap-" + scID);
						// popupWrap.addClass("tlp-popup-wrap-" + randId);
						popupContainer = $(".tlp-popup-content", popupWrap);
						var modalWrapper = ".tlp-popup-wrap-" + scID;
						// var modalWrapper = ".tlp-popup-wrap-" + randId;
						$(modalWrapper + " .tlp-popup-navigation-wrap").css("background-color", popupBg);
					},
					success: function (data) {
						popupContainer.html(data.data);
					},
					error: function (e) {
						console.log(e);
						popupContainer.html("<p>Loading error!!!</p>");
					}
				});

				popupWrap.find('.tlp-popup-next').on('click', function () {
					rightClick();
				});
				popupWrap.find('.tlp-popup-prev').on('click', function () {
					leftClick();
				});
				popupWrap.find('.tlp-popup-close').on('click', function () {
					ttpAnimation();
				});

				$(window).bind('keydown', function (event) {
					if (event.keyCode === 27) { // Esc
						ttpAnimation();
					} else if (event.keyCode === 37) { // left arrow
						leftClick();
					} else if (event.keyCode === 39) { // right arrow
						rightClick();
					}
				});

				function rightClick() {
					var nextId = nextItem(current, itemArray);
					current = nextId;
					var data = ttp.nonceID + "=" + ttp.nonce + "&action=tlp_multi_popup_single&id=" + current;
					$.ajax({
						type: "post",
						url: ttp.ajaxurl,
						data: data,
						beforeSend: function () {
							setLevelTeamPro(current, itemArray);
							popupContainer.html('<div class="tlp-popup-loading"></div>');
						},
						success: function (data) {
							popupContainer.html(data.data);
						},
						error: function (e) {
							console.log(e);
						}
					});
				}

				function leftClick() {
					var prevId = prevItem(current, itemArray);
					current = prevId;
					var data = ttp.nonceID + "=" + ttp.nonce + "&action=tlp_multi_popup_single&id=" + current;
					$.ajax({
						type: "post",
						url: ttp.ajaxurl,
						data: data,
						beforeSend: function () {
							setLevelTeamPro(current, itemArray);
							popupContainer.html('<div class="tlp-popup-loading"></div>');
						},
						success: function (data) {
							popupContainer.html(data.data);
						},
						error: function (e) {
							console.log(e);
						}
					});
				}

				return false;
			});


		});
	};
	initTlpTeam();
	function preFunction() {
		HeightResize();
	}

	function equalHeight4Layout4() {
		var $maxH = $(".rt-row.rt-content-loader.layout4 .layout4item").height();
		$(".rt-row.rt-content-loader.layout4 .layout4item .layoutInner .rt-img-holder img,.rt-row.rt-content-loader.layout4 .layout4item .layoutInner.layoutInner-content").height($maxH + "px");
	}

    function HeightResize() {
        if($(".rt-team-container[data-layout*='layout']").length > 0) {
            return;
        }

        var wWidth = $(window).width();
        $(".rt-team-container").each(function () {
            var self = $(this),
                dCol = self.data('desktop-col'),
                tCol = self.data('tab-col'),
                mCol = self.data('mobile-col'),
                target = $(this).find('.rt-row.rt-content-loader.ttp-even');
            if ((wWidth >= 992 && dCol > 1) || (wWidth >= 768 && tCol > 1) || (wWidth < 768 && mCol > 1)) {
                target.imagesLoaded(function () {
                    var tlpMaxH = 0;
                    target.find('.even-grid-item').height('auto');
                    target.find('.even-grid-item').each(function () {
                        var $thisH = $(this).outerHeight();
                        if ($thisH > tlpMaxH) {
                            tlpMaxH = $thisH;
                        }
                    });
                    target.find('.even-grid-item').height(tlpMaxH + "px");
                });
            } else {
                target.find('.even-grid-item').height('auto');
            }

        });
        if ($(".rt-row.rt-content-loader.layout4").length) {
            equalHeight4Layout4();
        }
    }

	function ttpAnimation() {
		var $pHolder = jQuery('#tlp-popup-wrap');
		$pHolder.animate({
			marginLeft: parseInt($pHolder.css('marginLeft'), 10) == 0 ?
				$pHolder.outerWidth() : 0,
		}).promise().done(function () {
			if (parseInt($pHolder.css('marginLeft')) > 0) {
				$pHolder.remove();
			}
		});
	}

	function initPopupTeamPro() {
		var html = '<div id="tlp-popup-wrap" class="tlp-popup-wrap tlp-popup-singlePage-sticky tlp-popup-singlePage">' +
			'<div class="tlp-popup-content">' +
			'<div class="tlp-popup-loading"></div>' +
			'</div>' +
			'<div class="tlp-popup-navigation-wrap">' +
			'<div class="tlp-popup-navigation">' +
			'<div class="tlp-popup-prev" title="Previous (Left arrow key)" data-action="prev"> <i class="fas fa-angle-left"></i></div>' +
			'<div class="tlp-popup-close" title="Close (Esc arrow key)" data-action="close"> <i class="fas fa-times"></i> </div>' +
			'<div class="tlp-popup-next" title="Next (Right arrow key)" data-action="next"> <i class="fas fa-angle-right"></i> </div>' +
			'<div class="tlp-popup-singlePage-counter"><span class="ccurrent"></span> ' + ttp.lan.of + ' <span class="ctotal"></span></div>' +
			'</div>' +
			'</div>' +
			'</div>';
		$("body").append(html);
		var $pHolder = $('#tlp-popup-wrap');
		$pHolder.css('display', 'block');
		var navHeight = $pHolder.find('.tlp-popup-navigation-wrap').height();
		$pHolder.find('.tlp-popup-content').css('padding-top', navHeight + "px");
		ttpAnimation();
	}

	function nextItem(current, list) {
		var index = list.indexOf(current);
		index++;
		if (index >= list.length)
			index = 0;
		return list[index];
	}

	function prevItem(current, list) {
		var index = list.indexOf(current);
		index--;
		if (index < 0)
			index = list.length - 1;
		return list[index];
	}

	function setLevelTeamPro(current, list) {
		var index = list.indexOf(current) + 1,
			count = list.length;
		$(".ccurrent").text(index);
		$(".ctotal").text(count);
	}

	function navResize() {
		var $pHolder = jQuery('#tlp-popup-wrap');
		$pHolder.css('display', 'block');
	}

	$(window).on('load resize', function () {
		navResize();
		HeightResize();
		if ($(".tlp-md-content").length) {
			$(".tlp-md-content").mCustomScrollbar({
				scrollbarPosition: "outside"
			});
		}
	});
	$.fn.alterClass = function (removals, additions) {
		var self = this;
		if (removals.indexOf('*') === -1) {
			// Use native jQuery methods if there is no wildcard matching
			self.removeClass(removals);
			return !additions ? self : self.addClass(additions);
		}
		var patt = new RegExp('\\s' +
			removals.replace(/\*/g, '[A-Za-z0-9-_]+').split(' ').join('\\s|\\s') +
			'\\s', 'g');
		self.each(function (i, it) {
			var cn = ' ' + it.className + ' ';
			while (patt.test(cn)) {
				cn = cn.replace(patt, ' ');
			}
			it.className = $.trim(cn);
		});
		return !additions ? self : self.addClass(additions);
	};

	$('.md-close').on('click', function (e) {
		e.preventDefault();
		mdModalWrap.removeClass('md-show');
		mdModalWrap.alterClass('tlp-modal-*');
		mdModalWrap.find('.tlp-md-content-holder').html('');
	});

	skillAnimation();

	if( $('.tlp-tooltip').length > 0 ) {
		$('.tlp-tooltip').rtTooltip();
	}

	var RttmSlider = function ($slider) {
		this.$slider = $slider;
		this.slider = this.$slider.get(0);
		this.swiperSlider = this.slider.swiper || null;
		this.defaultOptions = {
			breakpointsInverse: true,
			observer: true,
			navigation: {
				nextEl: this.$slider.find('.swiper-button-next').get(0),
				prevEl: this.$slider.find('.swiper-button-prev').get(0),
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
			if(this.$slider.data('options').lazy) {
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

function rtSliderInit($) {
	$('.rttm-carousel-slider').each(function () {
		$(this).rttm_slider();
	});
}

function skillAnimation() {
	jQuery('.tlp-team-skill').find('.fill').css('width', '0%');
	jQuery(window).bind('load', function () {
		jQuery('.tlp-team-skill').each(function () {
			jQuery(this).find('.fill').each(function () {
				var k = 0, f = jQuery(this), p = f.attr('data-progress-animation'), w = f.width();
				if (w == 0) {
					p = p.substring(0, p.length - 1);
					var go = function () {
						return k >= p || k >= 100 ? (false) : (k += 1, f.css('width', k + '%'), setTimeout(go, 20))
					};
					go();
				}
			});
		});
	});
}

function mdPopUpSkillAnimation() {
	rtSliderInit(jQuery);
	jQuery('#tlp-modal .tlp-md-content').mCustomScrollbar({
		scrollbarPosition: 'outside'
	});
	jQuery('#tlp-modal .tlp-tooltip').rtTooltip();
	jQuery('#tlp-modal .tlp-md-content .tlp-team-skill').find('.fill').css('width', '0%');

	jQuery('#tlp-modal .tlp-md-content .tlp-team-skill').each(function () {
		jQuery(this).find('.fill').each(function () {
			var k = 0, f = jQuery(this), p = f.attr('data-progress-animation'), w = f.width();
			if (w == 0) {
				p = p.substring(0, p.length - 1);
				var go = function () {
					return k >= p || k >= 100 ? (false) : (k += 1, f.css('width', k + '%'), setTimeout(go, 20))
				};
				go();
			}
		});
	});
}

function tlpSingleTeamScript() {
	jQuery('#tlp-popup-wrap .tlp-tooltip').rtTooltip();
	jQuery('#tlp-popup-wrap .tlp-popup-content .tlp-team-skill').find('.fill').css('width', '0%');
	jQuery('#tlp-popup-wrap .tlp-popup-content .tlp-team-skill .fill').each(function () {
		var k = 0, f = jQuery(this), p = f.attr('data-progress-animation'), w = f.width();
		if (w == 0) {
			p = p.substring(0, p.length - 1);
			var go = function () {
				return k >= p || k >= 100 ? (false) : (k += 1, f.css('width', k + '%'), setTimeout(go, 20))
			};
			go();
		}
	});
	rtSliderInit(jQuery);
}

function RTElementThumbCarousel(container, options, instance, index) {
	// Params
	var mainSlider = container.find('.rttm-carousel-main').addClass('instance-' + index),
		navSlider = container.find('.ttp-carousel-thumb').addClass('instance-' + index),
		navPrev = '.swiper-button-prev',
		navNext = '.swiper-button-next',
		dotsEl = '.swiper-pagination';

	container.find(navPrev).addClass('prev-' + index);
	container.find(navNext).addClass('next-' + index);

	// Main Slider
	var mainSliderOptions = {
		loop: true,
		speed: options.speed ? options.speed : 1000,
		loopedSlides: 5,
		autoHeight: options.autoHeight ? options.autoHeight : false,
		navigation: {
			nextEl: navNext,
			prevEl: navPrev
		},
		pagination: {
			el: dotsEl,
			type: 'bullets',
			clickable: true
		}
	};

	var main = new Swiper(mainSlider[0], mainSliderOptions);

	// Navigation Slider
	var navSliderOptions = {
		loop: true,
		speed: options.speed ? options.speed : 1000,
		observer: true,
		observerParents: true,
		slidesPerView: options.slidesPerView ? options.slidesPerView : 5,
		centeredSlides: true,
		spaceBetween: 0,
		touchRatio: 0.2,
		slideToClickedSlide: true,
		loopedSlides: 5,
		watchSlidesProgress: true,
		breakpoints: options.breakpoints
	};

	if (options.autoplay) {
		navSliderOptions.autoplay = {
			delay: options.autoplay.delay,
			pauseOnMouseEnter: options.autoplay.pauseOnMouseEnter,
			disableOnInteraction: false
		};
	}

	if (options.lazy) {
		navSliderOptions.preloadImages = false;
		navSliderOptions.lazy = true;
	}

	var thumb = new Swiper(navSlider[0], navSliderOptions);

	// Syncing the sliders
	main.controller.control = thumb;
	thumb.controller.control = main;

	instance[index] = [main, thumb];
}
