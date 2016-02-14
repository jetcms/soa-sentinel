$(function ()
{
	$.ajaxSetup({
		headers: {
			'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
		}
	});

	$('.nestable').nestable({
		maxDepth: $('.nestable').data('max-depth')
	}).on('change', function (e) {
		var url = $(this).data('url');
		var list = e.length ? e : $(e.target);
		var data = list.nestable('serialize');
		$.post(url, {data : data});
	});
	$('.nestable').nestable('collapseAll');
});