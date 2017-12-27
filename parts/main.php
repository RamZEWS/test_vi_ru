<?
require_once($_SERVER["DOCUMENT_ROOT"].'/models/Courier.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/models/Region.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/models/Trip.php');

$date = $_GET['date'] ?: date('Y-m-d');
?>
<h1>Маршруты на <?= $date; ?></h1>
<?
$trips = Trip::getAll(['date_start<=' => $date, 'date_end>=' => $date], 'date_start desc');
$couriers = Courier::getAll();
$regions = Region::getAll();
?>

<form action="" method="get">
	<input type="date" name="date" value="<?= $date; ?>"/>
	<button type="submit">Поиск</button>
</form>

<?
if($trips){
?>
<table cellpadding="5" cellspacing="0">
	<thead>
		<tr>
			<th>Кто</th>
			<th>Куда</th>
			<th>Отбытие</th>
			<th>Возвращение</th>
			<th>Дней в пути</th>
		</tr>
	</thead>
	<tbody>
		<?
		foreach($trips as $t) {
			$c = $couriers[$t['courier_id']];
			$r = $regions[$t['region_id']];
			?>
				<tr>
					<td><?= $c['fio']; ?></td>
					<td><?= $r['name']; ?></td>
					<td><?= $t['date_start']; ?></td>
					<td><?= $t['date_end']; ?></td>
					<td><?= $r['duration']; ?></td>
				</tr>
			<?
		} 
		?>		
	</tbody>
</table>
<?
} else {
?>
	<div>Нет поездок на выбранную дату</div>
<?
}
?>

<a href="/add.php">Добавить поездку</a>
