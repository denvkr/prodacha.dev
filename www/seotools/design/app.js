$(document).ready(function(){
	
	// Очистить столбец при клике на заголовок
	$('#data th.delete_all_col').live('dblclick', function(e){
		e.preventDefault();
		if (confirm('Вы уверены, что хотите очистить этот столбец?'))
		{
			var i = $(this).index();
			$('#data tr').each(function(){
				$(this).find('td:eq(' + i + ')').children().val('');
			});
		}
		
	});
	
	// Показать/скрыть блок
	$('.expander').live('click', function(e){
		e.preventDefault();
		var o = $(this).attr('obj-target');
		$(o).toggle();
	});
	
	
	// Расширяемый textarea
	$('textarea.expandable').live('focusin', function(){
		$(this).css('height', '300px');
	});
	$('textarea.expandable').live('focusout', function(e){
		//console.log(e);
		//$(this).css('height', '16px');
	});
	
	// добавить новые настройки
	$('#add_row').click(function(){
		var l 	= $('#data tr').length;
		var _tr	= $('#data tr[oid="0"]').html();
		var tr	= _tr.replace(/name=\"items\[0\]/g, 'name="items[' + l + ']');
		$('#data tbody').append('<tr oid=' + l + '>' + tr + '</tr>');
	});
	
	// url
	$('#data td:nth-child(2)').live('change', function(){
		// вырезаем адрес сайта http://.../
		var inp 	= $(this).find('input');
		var _url	= $(inp).val();
		var url 	= $.url(_url);
		var path 	= url.attr('relative');
		
		if (_url == '') return;
		
		$(inp).val(path);
		
		//
		//	проверка на дубли
		//
		var vals = [ _url ];
		var vals_dub_text = '';
		var vals_dub = [];
		var source_i = $(this).parent().index();
		
		$('#data tr').each(function(i){
			var val = $(this).find('td:eq(1) input').removeClass('dublicate').val();
			for (key in vals)
			{
				if (vals[key] != '' && vals[key] == val && (i - 1) != source_i) 
				{
					//console.log(vals[key] + ' -> повтор на tr:' + i);
					vals_dub_text += '"' + vals[key] + '" повторяется на строке №' + i + '  ';
					vals_dub.push(vals[key]);
					break;
				}
			}
			vals.push(val);
		});
		if (vals_dub.length > 0)
		{
			alert(vals_dub_text);
			
			for (z in vals_dub)
			{
				//console.log(vals_dub[z]);
				$('#data tr').each(function(){
					var inp = $(this).find('td:eq(1) input');
					var val = $(inp).val();
					if (val == vals_dub[z]) $(inp).addClass('dublicate');
					else $(inp).removeClass('dublicate');
				});
			}
		}
	});
	
	// title => h1 via dblclick
	
	$('#data td:nth-child(4)').live('dblclick', function(e){
		//alert (1);
		e.preventDefault();
		var title_value = $(this).prev().find('input').val();
		if ($(this).find('input').val() == '') h1 = $(this).find('input').val(title_value);
	});
	
	/*var repiter_mem = new Array; // запоминаем в каких отключали
	
	$('#data td:nth-child(3)').live('keyup', function(){
		var repiter = true; // дублировать значение в H1
		var v = $(this).parent().attr('oid');
		var h1 = $(this).next().find('input');
		var h1_value = $(h1).val();
		var title = $(this).find('input');
		var title_value = $(title).val();
		var title_value_before = title_value.substr(0, (title_value.length - 1));
		for (var z in repiter_mem)
		{
			if (repiter_mem[z] == v) repiter = false;
		}
		
		//if ((title_value_before.length == h1_value.length) && title_value_before == h1_value) repiter = true;
		if (title_value != h1_value) repiter = false;
		
		console.log(title_value_before + '!=' + h1_value + ' repiter = ' + repiter);
		
		if (repiter)
		{
			var title_value = $(title).val();
			$(h1).val(title_value);
		}
	}).keyup;
	
	// h1
	$('#data td:nth-child(4)').live('keyup', function(){
		//
		//	Если редактируем h1, то убираем дублирование
		//	в этой строке по title
		//
		var v = $(this).parent().attr('oid');
		var add = true;
		for(var i in repiter_mem) if (repiter_mem[i] == v) add = false;
		if (add) repiter_mem.push (v);
	}).keyup;*/
	
	
	// in_array
	/*Array.prototype.in_array = function(_value){
		for(var i = 0, l = this.length; i < l; i++) if (this[i] == _value) return i;
		return undefined;
	}*/
});

