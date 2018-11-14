<?php 
if(isset($_POST['save'])):
	if($_POST['save']=='sales'):
		$con=mysqli_connect("mysql-educonet.alwaysdata.net","educonet","adminedc123","educonet_gcsa") or die(mysqli_error($con));
		$q=$con->query("insert ignore into sales(datevente) values('".date('Y-m-d',strtotime($_POST['date_vente']))."')");
		$q=$con->query("select * from sales where datevente='".date('Y-m-d',strtotime($_POST['date_vente']))."'");
		$idsales="";
		while($r=mysqli_fetch_array($q)) $idsales=$r[0];
		$q1=$con->query("select * from sku where idgroupe in (select idgroupe from groupe_sku where nomgroupe='".$_POST['groupe_sku']."')");
		$j=0;
		$k=0;
		while($r1=mysqli_fetch_array($q1)):
		for($i=0;$i<count($_POST['data_'][$r1[0]]);$i++){
			$q=$con->query("insert ignore into daily_sales values('',".$idsales.",".$_POST['van_'][$k].",".$_POST['sku_'][$j].",".$_POST['data_'][$r1[0]][$i].")");
			$k++;
		}
			$q=$con->query("insert ignore into stock_depot values('',".$_POST['id_depot'].",'".date('Y-m-d',strtotime($_POST['date_vente']))."',".$_POST['stock_'][$j].",".$_POST['sku_'][$j].")");
			$q=$con->query("insert ignore into insights_depot_sales('',".$_POST['id_depot'].",".$idsales.")");
			$j++;
		endwhile;
		echo '<div class="w3-panel w3-pale-green">Enregistrement effectu&eacute;</div>';
		echo '<script>setTimeout(function(){location="?page=sales";},3000);</script>';
	endif;
endif;
if(isset($_POST['request'])):
	if($_POST['request']=='sales'):
		$con=mysqli_connect("mysql-educonet.alwaysdata.net","educonet","adminedc123","educonet_gcsa") or die(mysqli_error($con));
		$q=$con->query("insert into request_sales values('','".$_POST['request_text']."',".$_POST['request_id_depot'].",'".date('Y-m-d',strtotime($_POST['request_date_vente']))."',0)");
		echo '<div class="w3-panel w3-pale-green">Requête enregistr&eacute;e</div>';
		echo '<script>setTimeout(function(){location="?page=sales";},3000);</script>';
	endif;
endif;
?>
<!-- Tab links -->
<div class="tab">
  <button class="tablinks" onclick="openCity(event, 'London')" id="tab1"><i class="fa fa-eye"></i>&nbsp;Owerview</button>
  <button class="tablinks" onclick="openCity(event, 'Paris')" id="tab2"><i class="fa fa-save"></i>&nbsp;Sales</button>
  <button class="tablinks" onclick="openCity(event, 'Tokyo')" id="tab3"><i class="fa  fa-warning"></i>&nbsp;Tracker</button>
</div>

<!-- Tab content -->
<div id="London" class="tabcontent">
	<fieldset>
		<legend>Overview</legend>
		<form method="post" action="#">
		<label>Date: </label><input type="date" class="date_vente" name="date_vente" value="<?php if(isset($_POST['date_vente'])) echo $_POST['date_vente']; else echo date('Y-m-d'); ?>">
		<input type="hidden" name="tab" value="tab1">
		<input type="hidden" name="show" value="sales">
		<button>Show&nbsp;<i class="fa fa-eye"></i></button>
		</form>
	</fieldset>
	<?php if(isset($_POST['show'])): ?>
	<div class="accordion">
	<?php $con=mysqli_connect("mysql-educonet.alwaysdata.net","educonet","adminedc123","educonet_gcsa") or die(mysqli_error($con));
		$q=$con->query("select * from depot");
		while($r=mysqli_fetch_array($q)):
		echo "<h3>". $r[1] . "</h3>
		<div>";
			//<?php 
					$q1=$con->query("select *,(select nomdepot from depot where iddepot=".$r[0].") as nomdepot,sum(qtysold) as total from daily_sales where idsales=(select idsales from sales where datevente='".date('Y-m-d',strtotime($_POST['date_vente']))."') AND idvan IN(SELECT idvan FROM van WHERE idwork IN(SELECT idwork FROM work_employee_depot WHERE iddepot=".$r[0]."))group by idsales");
					$ventes=0;
					while($r1=mysqli_fetch_array($q1)):
						$ventes=$r1['total'];
					endwhile;
					$q1=$con->query("select id_period,target,(select salesdays from period where idperiod=period_depot_sku.id_period) as totaldays,sum((select tauxconv from sku where idsku in(select idsku from depot_sku where iddepotsku=period_depot_sku.id_depot_sku))*target) as total from period_depot_sku where id_depot_sku in(select id_depot_sku from depot_sku where iddepot=".$r[0].") group by id_period");
					$target=0;
					$days=0;
					$taux=0;
					while($r1=mysqli_fetch_array($q1)):
						$target=$r1['total'];
						$days=$r1['totaldays'];
					endwhile;
				if($days>0) $dailytarget=round($target/$days,0); else $dailytarget=0;
		
			echo "<p>Daily Sales : <b> $ventes</b></p>
			<p>Daily Target :"; 
			if(isset($dailytarget)) echo $dailytarget; 
			echo "</p>
			<p>Daily % : ";
			if(isset($dailytarget) && $dailytarget>0) echo round($ventes/$dailytarget*100,0)."%"; 
			echo "</p>
			<p>MTD Sales :</p>
			<p>MTD Target :</p>
			<p>MTD % :</p>
		</div>";
	 endwhile; ?>
	</div>
	<?php endif; ?>
</div>

<div id="Paris" class="tabcontent">
  <h3>New Daily Sales</h3>
<div id="main_form">
	<fieldset>
		<legend><i class="fa fa-save"></i></legend>
		<form method="post" action="#">
			<label>Date</label>
			<input type="date" name="date_vente" class="date_vente" id="date_vente" value="<?php echo date('Y-m-d'); ?>" <?php if(isset($_POST['groupe_sku'])) echo "disabled"; ?>>
			<label>Groupe</label>
			<select name="groupe_sku" id="groupe_sku" <?php if(isset($_POST['groupe_sku'])) echo "disabled"; ?>>
				<option>BEER</option>
				<option>SPIRIT</option>
			</select>
			<label>D&eacute;pot</label>
			<select name="id_depot" id="depot" <?php if(isset($_POST['groupe_sku'])) echo "disabled"; ?>>
				<option></option>
				<?php $con=mysqli_connect("mysql-educonet.alwaysdata.net","educonet","adminedc123","educonet_gcsa") or die(mysqli_error($con));
					$q=$con->query("select * from depot");
					while($r=mysqli_fetch_array($q))
						echo '<option value="'.$r[0].'" '.(isset($_POST['id_depot']) && $_POST['id_depot']==$r[0] ? 'selected' : '').' >'.$r[2].'</option>';
				?>
			</select>
			<input type="hidden" name="tab" value="tab2" <?php if(isset($_POST['groupe_sku'])) echo "disabled"; ?>>
			<button <?php if(isset($_POST['groupe_sku'])) echo 'style="display:none"'; ?>>Go</button>
		</form>
	</fieldset>
<?php if(isset($_POST['groupe_sku']) && $_POST['groupe_sku']=="BEER") : ?>
<?php $con=mysqli_connect("mysql-educonet.alwaysdata.net","educonet","adminedc123","educonet_gcsa") or die(mysqli_error($con));
					$q=$con->query("select * from depot where iddepot in(select iddepot from work_employee_depot where idwork in(select idwork from van where idvan in(select idvan from daily_sales where idsales=(select idsales from sales where datevente='".date('Y-m-d',strtotime($_POST['date_vente']))."')))) and iddepot=".$_POST['id_depot']);
					if(mysqli_num_rows($q)==1) : ?>
	<fieldset>
		<legend></legend>
		<form method="post" action="#">
			<input type="hidden" name="tab" value="tab2">
			<input type="hidden" name="date_vente" value="<?php echo $_POST['date_vente']; ?>">
			<input type="hidden" name="id_depot" value="<?php echo $_POST['id_depot']; ?>">
			<input type="hidden" name="groupe_sku" value="<?php echo $_POST['groupe_sku']; ?>">
			<div id="accordion" class="accordion">
			  <?php $con=mysqli_connect("mysql-educonet.alwaysdata.net","educonet","adminedc123","educonet_gcsa") or die(mysqli_error($con));
				$q=$con->query("select *,(select nomdepot from depot where iddepot=".$_POST['id_depot'].") as nomdepot,sum(qtysold) as total from daily_sales where idsales=(select idsales from sales where datevente='".date('Y-m-d',strtotime($_POST['date_vente']))."') group by idsales"); 
				$total=0;
				while($r=mysqli_fetch_array($q)):
					$total=$r['total'];
					$depot=$r['nomdepot'];
				endwhile;
				if(isset($depot)) :
					echo '<h3>'.$depot.'</h3>';
					echo '<div>';
					echo '<h2>Make a request for update</h2>';
					echo '<form method="post" action="#"><textarea name="request_text" placeholder="Veuillez taper le message de votre requête ici. Nous vous promettons d\'en tenir compte" required></textarea><input type="hidden" name="request_id_depot" value="'.$_POST['id_depot'].'"><input type="hidden" name="request_date_vente" value="'.$_POST['date_vente'].'"><input type="hidden" name="request" value="sales"><input type="hidden" name="tab" value="tab2"><button>Send&nbsp;<i class="fa fa-forward"></i></button></form>';
					echo '</div>';
				endif;
			  ?>
			</div>
			<label>Total depot</label>
			<input type="number" disabled id="total_depot" name="total_depot" value="<?php echo $total; ?>">
		</form>
	</fieldset>
					
					<?php else : ?>
	<fieldset>
		<legend></legend>
		<form method="post" action="#">
			<input type="hidden" name="tab" value="tab2">
			<input type="hidden" name="date_vente" value="<?php echo $_POST['date_vente']; ?>">
			<input type="hidden" name="id_depot" value="<?php echo $_POST['id_depot']; ?>">
			<input type="hidden" name="groupe_sku" value="<?php echo $_POST['groupe_sku']; ?>">
			<div id="accordion" class="accordion">
			  <?php $con=mysqli_connect("mysql-educonet.alwaysdata.net","educonet","adminedc123","educonet_gcsa") or die(mysqli_error($con));
					$q=$con->query("select * from sku where idgroupe in(select idgroupe from groupe_sku where nomgroupe='BEER')");
					while($r=mysqli_fetch_array($q)):
						echo '<h3>'.$r[1].' (Crates)<h3>';
						echo '<div>';
							$q1=$con->query("select * from van where idwork in(select idwork from work_employee_depot where iddepot=".$_POST['id_depot'].")");
							while($r1=mysqli_fetch_array($q1)) :
								echo '<label>'.$r1[1].'</label><input type="number" required class="data_'.$r[0].'" id="data_'.$r[0].'_van'.$r1[0].'" name="data_['.$r[0].'][]" value="0">';
								echo '<input type="hidden" name="van_[]" value="'.$r1[0].'">';
							endwhile;
								echo '<input type="hidden" name="sku_[]" value="'.$r[0].'">';
							echo '<label>Total Ventes</label>';
							echo '<input type="text" disabled value="0" class="total" id="total_'.$r[0].'" name="total_'.$r[0].'">';
							echo '<script>';
							echo "$('.data_".$r[0]."').change(function(e){
								 var s=0; 
								$('.data_".$r[0]."').each(function(e){s=s+parseInt($(this).val());});
								$('#total_".$r[0]."').val(s);
								s=0;
								$('.total').each(function(e){s=s+parseInt($(this).val());});
								$('#total_depot').val(s/2);
							});";
							echo "$('.data_".$r[0]."').blur(function(e){
								var s=0;
								$('.data_".$r[0]."').each(function(e){s=s+parseInt($(this).val());});
								$('#total_".$r[0]."').val(s);
								s=0;
								$('.total').each(function(e){s=s+parseInt($(this).val());});
								$('#total_depot').val(s);
							});";
							echo '</script>';
							echo '<labe>Stock '.$r[1].'</label><input type="number" required id="stock_'.$s[0].'" name="stock_[]" value="0">';
						echo '</div>';
					endwhile;
				?>
			</div>
			<label>Total depot</label>
			<input type="number" disabled id="total_depot" name="total_depot">
			<label>Insights</label>
			<textarea name="insights" required></textarea>
			<input type="hidden" name="save" value="sales">
			<button>Save&nbsp;<i class="fa fa-save" class="font-size:1.1em"></i></button>
		</form>
	</fieldset>
	<?php endif; ?>
<?php endif; ?>
</div>
</div>

<div id="Tokyo" class="tabcontent">
  <h3>Tracker</h3>
  <?php $con=mysqli_connect("mysql-educonet.alwaysdata.net","educonet","adminedc123","educonet_gcsa") or die(mysqli_error($con));
		$q1=$con->query("select * from depot order by idregion");
		while($r1=mysqli_fetch_array($q1)):
		$q=$con->query("select * from depot where iddepot in(select iddepot from work_employee_depot where idwork in(select idwork from van where idvan in (select idvan from daily_sales where idsales in(select idsales from sales where datevente='".date('Y-m-d')."') and idsku in(select idsku from sku where idgroupe=(select idgroupe from groupe_sku where nomgroupe='BEER'))))) and iddepot=".$r1[0]."");
		echo "<div>".$r1[2]." (B): ";
		while($r=mysqli_fetch_array($q)):
			echo '<i class="fa fa-check" style="color:green"></i>';
		endwhile;
		if(mysqli_num_rows($q)==0) echo '<i class="fa fa-times" style="color:red"></i>';
		$q=$con->query("select * from depot where iddepot in(select iddepot from work_employee_depot where idwork in(select idwork from van where idvan in (select idvan from daily_sales where idsales in(select idsales from sales where datevente='".date('Y-m-d')."') and idsku in(select idsku from sku where idgroupe=(select idgroupe from groupe_sku where nomgroupe='SPIRIT'))))) and iddepot=".$r1[0]."");
		echo " (S) : ";
		while($r=mysqli_fetch_array($q)):
			echo '<i class="fa fa-check" style="color:green"></i>';
		endwhile;
		if(mysqli_num_rows($q)==0) echo '<i class="fa fa-times" style="color:red"></i>';
		echo "</div>";
		endwhile;
	?>			
</div>
<style>
/* Style the tab */
.tab {
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #000;
}

/* Style the buttons that are used to open the tab content */
.tab button {
    background-color: inherit;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 14px 16px;
    transition: 0.3s;
}

/* Change background color of buttons on hover */
.tab button:hover {
    background-color: #ddd;
}

/* Create an active/current tablink class */
.tab button.active {
    background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
    display: none;
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;
}
</style>
<script>
function openCity(evt, cityName) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}
 $( function() {
    $( ".accordion" ).accordion({
		active:false,
      collapsible: true
    });
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
    $('.date_vente').attr('type','date');
}
else{
	$('.date_vente').attr('type','text').attr('readonly',true);
	$('.date_vente').datepicker({
      changeMonth: true,
      changeYear: true
    });

}
  } );
<?php if(isset($_POST["tab"])) echo "$('#".$_POST['tab']."').click();"; ?>
</script>