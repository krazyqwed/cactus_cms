$().ready(function(){
	$('.menu-items .wrap > ul').nestedSortable({
		forcePlaceholderSize: true,
		listType: 'ul',
		handle: 'div',
		helper:	'clone',
		items: 'li',
		opacity: .6,
		placeholder: 'placeholder',
		revert: 150,
		tolerance: 'pointer',
		toleranceElement: '> div',
		maxLevels: 2,
		distance: 8,
		errorClass: 'error',
		scroll: false
	});

	$('.btn.new-item').click(function(){
		$('.menu-items .wrap > ul').append('<li rel="item-new"><div class="btn-group"><div class="btn btn-info btn-sm">Új menüpont</div><div class="btn btn-info btn-sm remove"><i class="fa fa-times"></i></div></div></li>');
		
		$('.alert.alert-order').html('<strong>Figyelem!</strong> Változtatások történtek az elrendezésben, melyek még nem lettek elmentve.').stop(true, true).addClass('alert-warning').fadeIn();
	});

	$(document).on('click', '.btn.js-remove', function(){
		$(this).closest('li').remove();

		$('.alert.alert-order').html('<strong>Figyelem!</strong> Változtatások történtek az elrendezésben, melyek még nem lettek elmentve.').stop(true, true).addClass('alert-warning').fadeIn();
	});

	$(document).on('click', '.btn.js-active', function(){
		if ($(this).hasClass('btn-success')){
			$(this).closest('li').data('active', '0');
			$(this).removeClass('btn-success').addClass('btn-danger');
			$(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
		}else{
			$(this).closest('li').data('active', '1');
			$(this).removeClass('btn-danger').addClass('btn-success');
			$(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
		}

		$('.alert.alert-order').html('<strong>Figyelem!</strong> Változtatások történtek az elrendezésben, melyek még nem lettek elmentve.').stop(true, true).addClass('alert-warning').fadeIn();
	});

	$('.btn.order-save').on('click', function(){
		var array = $('.menu-items .wrap > ul').nestedSortable('toArray', { attribute: 'rel' });
		array = JSON.stringify(array);

		$alert = $(this).closest('.panel-footer').find('.alert-order');
		$button = $(this);
		$button.html('<i class="fa fa-refresh"></i>');
		showAjaxCover($('.menu-items'));
		showAjaxCover($('.menu-edit'));

		$.ajax({
			type: "POST",
			url: $(this).attr('rel'),
			data: { 'data': array },
			dataType: "json",
			success: function(data){
				$button.html('Mentés');
				hideAjaxCover($('.menu-items'));
				hideAjaxCover($('.menu-edit'));

				if (data.success == 1)

					$alert.removeClass('alert-warning').addClass('alert-success');
				if (typeof data.reload_url != 'undefined')
					window.location.href = data.reload_url;

				$('.menu-items .wrap').html(data.html);
				$('.menu-edit .wrap').html(data.html_table);

				$('.menu-items .wrap > ul').nestedSortable({
					forcePlaceholderSize: true,
					listType: 'ul',
					handle: 'div',
					helper:	'clone',
					items: 'li',
					opacity: .6,
					placeholder: 'placeholder',
					revert: 150,
					tolerance: 'pointer',
					toleranceElement: '> div',
					maxLevels: 2,
					distance: 8,
					errorClass: 'error',
					scroll: false
				});

				$alert.html(data.message);
				$alert.fadeIn().delay(2000).fadeOut(function(){ $alert.removeClass('alert-success'); });
			}
		});
	});
});