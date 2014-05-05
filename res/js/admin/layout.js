$().ready(function(){
	$('.draggable').draggable({
		connectToSortable: 'ul[class^="sortable-position-"]',
		helper: 'clone',
		revert: 'invalid',
		appendTo: 'body',
		scroll: false
	});

	$('ul[class^="sortable-position-"]').sortable({
		connectWith: '.connected',
		placeholder: 'sortable-placeholder',
		tolerance: 'cursor',
		dropOnEmpty: true,
		distance: 8
	}).disableSelection();

	$('.droppable').droppable({
		drop: function(event, ui){
			if ($(ui.draggable).find('.btn-group').length == 0){
				$clone = $(ui.draggable).find('.btn').clone();
				$clone.html('<i class="fa fa-times"></i>');
				$clone.addClass('js-remove');
				$(ui.draggable).wrapInner('<div class="btn-group" />');
				$wrap = $(ui.draggable).find('.btn-group');
				$(ui.draggable).find('.btn').appendTo($wrap);
				$wrap.append($clone);
				$wrap.append('<div class="btn btn-danger btn-sm js-active"><i class="fa fa-eye-slash"></i></div>')
				$(ui.draggable).removeAttr('class');
			}

			$('.alert.alert-layout-save').html('<strong>Figyelem!</strong> Változtatások történtek az elrendezésben, melyek még nem lettek elmentve.').stop(true, true).addClass('alert-warning').fadeIn();
		}
	});

	$(document).on('click', '.btn.js-remove', function(){
		$(this).closest('li').remove();

		$('.alert.alert-layout-save').html('<strong>Figyelem!</strong> Változtatások történtek az elrendezésben, melyek még nem lettek elmentve.').stop(true, true).addClass('alert-warning').fadeIn();
	});

	$(document).on('click', '.btn.js-active', function(){
		if ($(this).hasClass('btn-success')){
			$(this).closest('li').attr('data-active', '0');
			$(this).removeClass('btn-success').addClass('btn-danger');
			$(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
		}else{
			$(this).closest('li').attr('data-active', '1');
			$(this).removeClass('btn-danger').addClass('btn-success');
			$(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
		}

		$('.alert.alert-layout-save').html('<strong>Figyelem!</strong> Változtatások történtek az elrendezésben, melyek még nem lettek elmentve.').stop(true, true).addClass('alert-warning').fadeIn();
	});

	$('.layout-save-btn').click(function(e){
		e.preventDefault();
		
		var layout = {};

		$('ul.connected.droppable').each(function(){
			var $list = $(this);
			layout[$list.attr('name')] = {};

			$(this).find('li').each(function(i){
				layout[$list.attr('name')][i] = {};
				layout[$list.attr('name')][i]['part-type'] = $(this).data('part-type');
				layout[$list.attr('name')][i]['part-id'] = $(this).data('part-id');
				layout[$list.attr('name')][i]['active'] = $(this).attr('data-active');

				if (typeof $(this).data('layout-part-id') != 'undefined')
					layout[$list.attr('name')][i]['layout-part-id'] = $(this).data('layout-part-id');

				if (typeof $(this).data('part-name') != 'undefined')
					layout[$list.attr('name')][i]['part-name'] = $(this).data('part-name');
			});
		});

		var json = JSON.stringify(layout);

		$alert = $(this).closest('.section').find('.alert-layout-save');
		$button = $(this);
		$button.html('<i class="fa fa-refresh"></i>');
		$cover = $('.ajax-cover');
		$cover.css({ 'top': 0 }).html('<i class="fa fa-refresh"></i>');

		$.ajax({
			type: "POST",
			url: $(this).attr('href'),
			data: { data: json },
			dataType: "json",
			success: function(data){
				$button.html('Elrendezés mentése');
				$cover.empty().css({ 'top': '-10000px' });

				if (data.success == 1){
					$alert.removeClass('alert-warning').addClass('alert-success');
					$('.part-list .wrap').html(data.html_table);
				}

				$alert.html(data.message);
				$alert.fadeIn().delay(2000).fadeOut(function(){ $alert.removeClass('alert-success'); });
			}
		});

	});
});