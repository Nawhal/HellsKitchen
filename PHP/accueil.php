<html>
<head>
	<!--<?php /*require_once('./header.php');*/ ?>-->
</head>

<body>

	<div>
		<h2>Restaurants</h2>
		<ul>
			<?php
				foreach($res as $key => $val)
				{
					echo '<li> <a href=\'./view/cartes.php?id='.$val['idrestaurant'].'\'>'.$val['nomrestaurant'].'</a> a '.$val['ville'].', '.$val['pays'].' - '.$val['adresse'].'</li>';
				}
			?>
		</ul>
	</div>

</body>

</html>
