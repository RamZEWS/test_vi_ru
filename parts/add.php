<?
require_once($_SERVER["DOCUMENT_ROOT"].'/models/Courier.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/models/Region.php');

$couriers = Courier::getAll([], 'fio asc');
$regions = Region::getAll([], 'name asc');
?>
<h1>Добавить поездку</h1>
<form>
	<select name="courier_id" id="courier_id">
		<?
		foreach ($couriers as $id => $i) {
		?>
			<option value="<?= $id; ?>"><?= $i['fio']; ?></option>
		<?
		}
		?>
	</select>
	<select name="region_id" id="region_id">
		<?
		foreach ($regions as $id => $i) {
		?>
			<option value="<?= $id; ?>"><?= $i['name']; ?></option>
		<?
		}
		?>
	</select>
	<input type="date" id="date_start" name="date_start"/>
	<button type="submit" id="submit" disabled>Добавить</button>
	<div class="ajax-result"></div>
</form>

<script>
	$(document).ready(function(){
		$('#date_start').on('change', function(){
			var c = $('#courier_id').val();
			var r = $('#region_id').val();
			var d = $(this).val();
			$('.ajax-result').empty();
			$.ajax({
				'url':'/ajax.php',
				'data': {
					'action': 'AddRoute',
					'date_start': d,
					'courier_id': c,
					'region_id': r
				},
				'success': function(res) {
					res = $.parseJSON(res);
					if(res.status) {
						$('#submit').prop('disabled', false);
						$('.ajax-result').css({'color': 'green'}).text(res.msg);
					} else {
						$('#submit').prop('disabled', true);
						$('.ajax-result').css({'color': 'red'}).text(res.msg);
					}
				}
			});
		});
	});
</script>