var getcode = document.URL.split("/"); var getvalue = getcode.indexOf("jQueryHorizontalTree");
(function($) {
	$.fn.tree_structure = function(options) {
		var defaults = {
			'add_option': true,
			'edit_option': true,
			'delete_option': true,
			'confirm_before_delete' : true,
			'animate_option': [true, 5],
			'fullwidth_option': false,
			'align_option': 'center',
			'draggable_option': true
		};
		return this.each(function() {
			if(options && getvalue > 0) $.extend(defaults, options);
			var add_option = defaults['add_option'];
			var edit_option = defaults['edit_option'];
			var delete_option = defaults['delete_option'];
			var confirm_before_delete = defaults['confirm_before_delete'];
			var animate_option = defaults['animate_option'];
			var fullwidth_option = defaults['fullwidth_option'];
			var align_option = defaults['align_option'];
			var draggable_option = defaults['draggable_option'];
			var vertical_line_text = '<span class="vertical"></span>';
			var horizontal_line_text = '<span class="horizontal"></span>';
			var add_action_text = add_option == true ? '<span class="add_action" title="Click for Add"></span>' : '';
			var edit_action_text = edit_option == true ? '<span class="edit_action" title="Click for Edit"></span>' : '';
			var delete_action_text = delete_option == true ? '<span class="delete_action" title="Click for Delete"></span>' : '';
			var highlight_text = '<span class="highlight" title="Click for Highlight | dblClick"></span>';
			var class_name = $(this).attr('class');
			var event_name = 'pageload';
			if(align_option != 'center') $('.'+class_name+' li').css({'text-align':align_option});
			if(fullwidth_option && getvalue > 0) {
				var i = 0;
				var prev_width;
				var get_element;
				$('.'+class_name+' li li').each(function() {
					var this_width = $(this).width();
					if(i == 0 || this_width > prev_width) {
						prev_width = $(this).width();
						get_element = $(this);
					}
					i++;
				});
				var loop = get_element.closest('ul').children('li').eq(0).nextAll().length;
				var fullwidth = parseInt(0);
				for($i=0; $i<=loop; $i++) {
					fullwidth += parseInt(get_element.closest('ul').children('li').eq($i).width());
				}
				$('.'+class_name+'').closest('div').width(fullwidth);
			}
			$('.'+class_name+' li.hide').each(function() {
				$(this).children('ul').hide();
			});
			function prepend_data(target) {
				target.prepend(vertical_line_text + horizontal_line_text).children('div').prepend(add_action_text + delete_action_text + edit_action_text);
				if(target.children('ul').length != 0) target.hasClass('hide') ? target.children('div').prepend('<b class="hide show"></b>') : target.children('div').prepend('<b class="hide"></b>');
				target.children('div').prepend(highlight_text);
			}
			function draw_line(target) {
				var child_width = target.children('div').outerWidth(true) / 2;
				var child_left = target.children('div').offset().left;
				if(target.parents('li').offset() != null) var parent_child_height = target.parents('li').offset().top;
				vertical_height = (target.offset().top - parent_child_height) - target.parents('li').children('div').outerHeight(true) / 2 ;
				target.children('span.vertical').css({'height':vertical_height, 'margin-top':-vertical_height, 'margin-left':child_width, 'left':child_left});
				if(target.parents('li').offset() == null) {
					var width = 0;
				} else {
					var parents_width = target.parents('li').children('div').offset().left + (target.parents('li').children('div').width() / 2);
					var current_width = child_left + (target.children('div').width() / 2);
					var width = parents_width - current_width;
				}
				var horizontal_left_margin = width < 0 ? -Math.abs(width) + child_width : child_width;
				target.children('span.horizontal').css({'width':Math.abs(width), 'margin-top':-vertical_height, 'margin-left':horizontal_left_margin, 'left':child_left});
			}
			if(animate_option[0] == true) {
				function animate_call_structure() {
					$timeout = setInterval(function() {
						animate_li();
					}, animate_option[1]);
				}
				var length = $('.'+class_name+' li').length;
				var i = 0;
				function animate_li() {
					prepend_data($('.'+class_name+' li').eq(i));
					draw_line($('.'+class_name+' li').eq(i));
					i++;
					if(i == length) {
						i = 0;
						clearInterval($timeout);
					}
				}
			}
			function call_structure() {
				$('.'+class_name+' li').each(function() {
					if(event_name == 'pageload') prepend_data($(this));
					draw_line($(this));
				});
			}
			animate_option[0] ? animate_call_structure() : call_structure();
			event_name = 'others';
			$(window).resize(function() { call_structure(); });
			$('.'+class_name+' b.hide').live('click', function() {
				$(this).toggleClass('show');
				$(this).closest('li').toggleClass('hide').children('ul').toggle();
				call_structure();
			});
			$('.'+class_name+' li > div').live('hover', function(event) {
				if(event.type == 'mouseenter' || event.type == 'mouseover' && getvalue > 0) {
					$('.'+class_name+' li > div.current').removeClass('current');
					$('.'+class_name+' li > div.children').removeClass('children');
					$('.'+class_name+' li > div.parent').removeClass('parent');
					$(this).addClass('current');
					$(this).closest('li').children('ul').children('li').children('div').addClass('children');
					$(this).closest('li').closest('ul').closest('li').children('div').addClass('parent');
					$(this).children('span.highlight, span.add_action, span.delete_action, span.edit_action').show();
				} else {
					$(this).children('span.highlight, span.add_action, span.delete_action, span.edit_action').hide();
				}
			});
			$('.'+class_name+' span.highlight').live('click', function() {
				$('.'+class_name+' li.highlight').removeClass('highlight');
				$('.'+class_name+' li > div.parent').removeClass('parent');
				$('.'+class_name+' li > div.children').removeClass('children');
				$(this).closest('li').addClass('highlight');
				$('.highlight li > div').addClass('children');
				var _this = $(this).closest('li').closest('ul').closest('li');
				find_parent(_this);
			});
			$('.'+class_name+' span.highlight').live('dblclick', function() {
				if(fullwidth_option && getvalue > 0) $('.'+class_name+'').parent('div').parent('div').scrollLeft(0);
				$('.'+class_name+' li > div').not(".parent, .current, .children").closest('li').addClass('none');
				$('.'+class_name+' li div b.hide.show').closest('div').closest('li').children('ul').addClass('show');
				$('.'+class_name+' li div b.hide').addClass('none');
				$('body').prepend('<img src="images/back.png" class="back_btn" />');
				call_structure();
				$('.back_btn').click(function() {
					$('.'+class_name+' ul.show').removeClass('show');
					$('.'+class_name+' li.none').removeClass('none');
					$('.'+class_name+' li div b.hide').removeClass('none');
					$(this).remove();
					call_structure();
				});
			});
			function find_parent(_this) {
				if(_this.length > 0) {
					_this.children('div').addClass('parent');
					_this = _this.closest('li').closest('ul').closest('li');
					return find_parent(_this);
				}
			}
			if(add_option && getvalue > 0) {
				$('.'+class_name+' span.add_action').live('click', function() {
					if($('form.add_data').length > 0) $('form.add_data').remove();
					if($('form.edit_data').length > 0) $('form.edit_data').remove();
					var addquery = '<form class="add_data"><img class="close" src="images/close.png" /><h3>Add Detail</h3><textarea></textarea><input type="checkbox" value="" id="hide" /> <label for="hide">Hide Child Nodes</label><span class="submit">Submit</span></form>';
					if($(this).closest('div').children('form.add_data').length == 0) {
						$(this).parent('div').append(addquery);
						if(($(this).closest('div').children('form').offset().top + $(this).closest('div').children('form').outerHeight()) > $(window).height()) {
							$(this).closest('div').children('form').css({'margin-top':-$(this).closest('div').children('form').outerHeight()});
						}
						if(($(this).closest('div').children('form').offset().left + $(this).closest('div').children('form').outerWidth()) > $(window).width()) {
							$(this).closest('div').children('form').css({'margin-left':-$(this).closest('div').children('form').outerWidth()});
						}
						$(this).closest('div').children('form').children('textarea').focus();
						$(this).closest('div').closest('li').closest('ul').children('li').children('div').addClass('zindex');
					}
					$('span.submit').click(function() {
						var _addthis = $(this);
						if(_addthis.closest('form').find('textarea').val() != '') {
							var ajax_add_id;
							if(_addthis.closest('li').children('ul').length > 0) {
								ajax_add_id = parseInt(_addthis.closest('li').children('ul').children('li').last().children('div').attr('id')) + 1;
							} else {
								ajax_add_id = _addthis.closest('div').attr('id') + 1;
							}
							var data = 'data={"action":"add", "id":"'+ajax_add_id+'", "html":"'+_addthis.closest('form').find('textarea').val().replace(/\s+/g, " ")+'", "parentid":"'+_addthis.closest('div').attr('id')+'", "showhideval":"'+_addthis.closest('form').find('input:checked').length+'"}';
							_addthis.closest("li").before("<img src='images/load.gif' class='load' />");
							//$.ajax({
								//type: 'POST',
								//url: 'ajax.php',
								//data: data,
								//success: function(data) {
									var html_value = '<li>'  + horizontal_line_text + '<div id="'+ajax_add_id+'">' + highlight_text + add_action_text + delete_action_text + edit_action_text + _addthis.closest('form').find('textarea').val() + '</div></li>';
									_addthis.closest('li').children('ul').length > 0 ? _addthis.closest('li').children('ul').append(html_value) : _addthis.closest('li').append('<ul>'+html_value+'</ul>');
									_addthis.closest('form.add_data').closest('div').children('span.highlight, span.add_action, span.delete_action, span.edit_action').hide();
									_addthis.closest('form.add_data').remove();
									$('li > div.zindex').removeClass('zindex');
									call_structure();
									draggable_event();
									$("img.load").remove();
									$('body').prepend('<div class="add_msg">Add Successfully...</div>');
									$('div.add_msg').animate({
										top : 200
									}, 4000, function() {
										$(this).remove();
									});
								//}
							//});
						} else {
							_addthis.closest('form').find('textarea').addClass('error');
						}
					});
					$('img.close').click(function() {
						$(this).closest('form.add_data').closest('div').children('span.highlight, span.add_action, span.delete_action, span.edit_action').hide();
						$(this).closest('form.add_data').remove();
						$('li > div.zindex').removeClass('zindex');
					});
				});
			}
			if(delete_option && getvalue > 0) {
				$('.'+class_name+' span.delete_action').live('click', function() {
					var _deletethis = $(this);
					var target_element = $(this).closest('li').closest('ul').closest('li');
					confirm_message = 1;
					if(confirm_before_delete) {
						var confirm_text = $(this).closest('li').children('ul').length == 0 ? "Deleat This ?" : "Deleat This with\nAll Child Element ?";
						confirm_message = confirm(confirm_text);
					}
					if(confirm_message) {
						$(this).closest('li').addClass('ajax_delete_all');
						ajax_delete_id = Array();
						ajax_delete_id.push($(this).closest('div').attr('id'));
						$('.ajax_delete_all li').each(function() {
							ajax_delete_id.push($(this).children('div').attr('id'));
						});
						$(this).closest('li').removeClass('ajax_delete_all');
						var data = 'data={"action":"delete", "id":"'+ajax_delete_id+'"}';
						$(this).closest("li").before("<img src='images/load.gif' class='load' />");
						//$.ajax({
							//type: 'POST',
							//url: 'ajax.php',
							//data: data,
							//success: function(data) {
								$("img.load").remove();
								_deletethis.closest('li').fadeOut().remove();
								call_structure();
								if(target_element.children('ul').children('li').length == 0) target_element.children('ul').remove();
								$('body').prepend('<div class="delete_msg">Delete Successfully...</div>');
								$('div.delete_msg').animate({
									top : 200
								}, 4000, function() {
									$(this).remove();
								});
							//}
						//});
					}
				});
			}
			if(edit_option && getvalue > 0) {
				$('.'+class_name+' span.edit_action').live('click', function() {
					if($('form.add_data').length > 0) $('form.add_data').remove();
					if($('form.edit_data').length > 0) $('form.edit_data').remove();
					var edit_string = $(this).closest('div').clone();
					if(edit_string.children('span.highlight').length > 0) edit_string.children('span.highlight').remove();
					if(edit_string.children('span.delete_action').length > 0) edit_string.children('span.delete_action').remove();
					if(edit_string.children('span.add_action').length > 0) edit_string.children('span.add_action').remove();
					if(edit_string.children('span.edit_action').length > 0) edit_string.children('span.edit_action').remove();
					if(edit_string.children('b.hide').length > 0) edit_string.children('b.hide').remove();
					var checked_val = $(this).closest('li').hasClass('hide') ? 'checked' : '';
					var editquery = '<form class="edit_data"><img class="close" src="images/close.png" /><h3>Edit Detail</h3><textarea>'+edit_string.html()+'</textarea><input type="checkbox" '+checked_val+' value="" id="hide" /> <label for="hide">Hide Child Nodes</label><span class="edit">Save</span></form>';
					if($(this).closest('div').children('form.edit_data').length == 0) {
						$(this).closest('div').append(editquery);
						if(($(this).closest('div').children('form').offset().top + $(this).closest('div').children('form').outerHeight()) > $(window).height()) {
							$(this).closest('div').children('form').css({'margin-top':-$(this).closest('div').children('form').outerHeight()});
						}
						if(($(this).closest('div').children('form').offset().left + $(this).closest('div').children('form').outerWidth()) > $(window).width()) {
							$(this).closest('div').children('form').css({'margin-left':-$(this).closest('div').children('form').outerWidth()});
						}
						$(this).closest('div').children('form').children('textarea').select();
						$(this).closest('div').closest('li').closest('ul').children('li').children('div').addClass('zindex');
					}
					$('span.edit').click(function() {
						var _editthis = $(this);
						if(_editthis.closest('form').find('textarea').val() != '') {
							var data = 'data={"action":"edit", "id":"'+_editthis.closest('div').attr('id')+'", "html":"'+_editthis.closest('form').find('textarea').val().replace(/\s+/g, " ")+'", "showhideval":"'+_editthis.closest('form').find('input:checked').length+'"}';
							_editthis.closest("li").before("<img src='images/load.gif' class='load' />");
							//$.ajax({
								//type: 'POST',
								//url: 'ajax.php',
								//data: data,
								//success: function(data) {
									if(_editthis.closest('form').find('input:checked').length > 0) {
										if(_editthis.closest('li').hasClass('hide') == false) {
											_editthis.closest('div').find('b.hide').trigger('click');
										}
									} else {
										if(_editthis.closest('li').hasClass('hide')) {
											_editthis.closest('div').find('b.hide').trigger('click');
										}
									}
									var element_target = _editthis.closest('form.edit_data').closest('div');
									var edit_html = "";
									edit_html = _editthis.closest('form').find('textarea').val();
									element_target.children('span.edit_action').nextAll().remove();
									if(element_target.text().length > 0) element_target.html(element_target.html().replace(element_target.text(), ''));
									element_target.append(edit_html);
									element_target.children('span.highlight, span.add_action, span.delete_action, span.edit_action').hide();
									$('li > div.zindex').removeClass('zindex');
									call_structure();
									$("img.load").remove();
									$('body').prepend('<div class="edit_msg">Edit Successfully...</div>');
									$('div.edit_msg').animate({
										top : 200
									}, 4000, function() {
										$(this).remove();
									});
								//}
							//});
						} else {
							_editthis.closest('form').find('textarea').addClass('error');
						}
					});
					$('img.close').click(function() {
						$(this).closest('form.edit_data').closest('div').children('span.highlight, span.add_action, span.delete_action, span.edit_action').hide();
						$(this).closest('form.edit_data').remove();
						$('li > div.zindex').removeClass('zindex');
					});
				});
			}
			if(draggable_option && getvalue > 0) {
				function draggable_event() {
					$('.'+class_name+' li > div').draggable({
						cursor: 'move',
						distance: 40,
						zIndex: 5,
						revert : true,
						revertDuration: 100,
						snap: '.tree li div',
						snapMode: 'inner',
						start: function(event, ui) {
							$('li.li_children').removeClass('li_children');
							$(this).closest('li').addClass('li_children');
						},
						stop: function(event, ul) {
							var drop_err = droppable_event();
							if(drop_err == undefined) {
								$('body').prepend('<div class="drag_error">Drag it Correctly...</div>');
								$('div.drag_error').animate({
									top : 200
								}, 4000, function() {
									$(this).remove();
								});
							}
						}
					});
				}
				function droppable_event() {
					$('.'+class_name+' li > div').droppable({
						accept: '.tree li div',
						drop: function(event, ui) {
							$('div.check_div').removeClass('check_div');
							$('.li_children div').addClass('check_div');
							if($(this).hasClass('check_div')) {
								alert('Cant Move on Child Element.');
							} else {
								var data = 'data={"action":"drag", "id":"'+$(ui.draggable[0]).attr('id')+'", "parentid":"'+$(this).attr('id')+'"}';
								//$.ajax({
									//type: 'POST',
									//url: 'ajax.php',
									//data: data,
									//success: function(data) {
									//}
								//});
								$(this).next('ul').length == 0 ? $(this).after('<ul><li>'+$(ui.draggable[0]).attr({'style':''}).closest('li').html()+'</li></ul>') : $(this).next('ul').append('<li>'+$(ui.draggable[0]).attr({'style':''}).closest('li').html()+'</li>');
								$(ui.draggable[0]).closest('ul').children('li').length == 1 ? $(ui.draggable[0]).closest('ul').remove() : $(ui.draggable[0]).closest('li').remove();
								call_structure();
								draggable_event();
								$('body').prepend('<div class="drop_msg">Drag Successfully...</div>');
								$('div.drop_msg').animate({
									top : 200
								}, 4000, function() {
									$(this).remove();
								});
							}
						}
					});
				}
				$('.'+class_name+' li > div').disableSelection();
				draggable_event();
			}
		});
	};
})(jQuery);
