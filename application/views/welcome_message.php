<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Zomato API</title>

	<style type="text/css">

	::selection { background-color: #E13300; color: white; }
	::-moz-selection { background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body {
		margin: 0 15px 0 15px;
	}

	p.footer {
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}

	#container {
		margin: 10px;
		border: 1px solid #D0D0D0;
		box-shadow: 0 0 8px #D0D0D0;
	}
.error{
	color: red;
	padding:10px;
}
.success{
	color: #00CC00;
	padding:10px;
}
	</style>
</head>
<body>

<div id="container">
	<h1>Zomato API interface</h1>

	<?php
	$message = null;
	if((null !=$this->session->flashdata('msg'))) {
		$message = $this->session->flashdata('msg');
		?>
		<div class="<?php echo $message['class']?>"><?php echo $message['message']; ?></div>

		<?php
	}
	?>

	<div id="body">
		<p><button onclick="location.href='<?php echo base_url('welcome/save_categories')?>'">Import Categories</button> </p>

		<hr>
		<form method="get" action="<?php echo base_url('welcome/get_locations')?>">
			<h2>Get city details</h2>
			<table>
				<tr>
					<td>
						<input placeholder="Enter city name" title="Enter city name" type="text" name="city_name" value="">
					</td>
					<td colspan="2">
						<input type="submit" value="Get city">
					</td>
				</tr>
			</table>
		</form>
		<hr>

		<form method="get" action="<?php echo base_url('welcome/get_collections')?>">
			<h2>Get collection details in a city</h2>
			<table>
				<tr>
					<td>
						<?php //print_r($cities);exit; ?>
						<select title="Select City" name="param">
							<option value="">Select City</option>
							<?php foreach ($cities as $city): ?>
								<option value="<?php echo $city['id']?>"><?php echo $city['name']?></option>
							<?php endforeach; ?>
						</select>
					</td>
					<td colspan="2">
						<input type="submit" value="Get Collection Data">
					</td>
				</tr>
			</table>
		</form>
		<hr>

		<form method="get" action="<?php echo base_url('welcome/get_cuisines')?> ">
			<h2>Get Cuisines details in a city</h2>
			<table>
				<tr>
					<td>
						<?php //print_r($cities);exit; ?>
						<select title="Select City" name="param">
							<option value="">Select City</option>
							<?php foreach ($cities as $city): ?>
								<option value="<?php echo $city['id']?>"><?php echo $city['name']?></option>
							<?php endforeach; ?>
						</select>
					</td>
					<td colspan="2">
						<input type="submit" value="Get Cuisines Data">
					</td>
				</tr>
			</table>
		</form>
		<hr>
		<form method="get" action="<?php echo base_url('welcome/get_establishments')?>">
			<h2>Get Establishments details in a city</h2>
			<table>
				<tr>
					<td>
						<?php //print_r($cities);exit; ?>
						<select title="Select City" name="param">
							<option value="">Select City</option>
							<?php foreach ($cities as $city): ?>
								<option value="<?php echo $city['id']?>"><?php echo $city['name']?></option>
							<?php endforeach; ?>
						</select>
					</td>
					<td colspan="2">
						<input type="submit" value="Get Establishments Data">
					</td>
				</tr>
			</table>
		</form>
		<hr>
		<form method="get" action="<?php echo base_url('welcome/get_geo_locations')?>">
			<h2>Get location details based on coordinates</h2>
			<table>
				<tr>
					<td>
						<input type="text" name="param1" value="" placeholder="Enter latitude">
						<input type="text" name="param2" value="" placeholder="Enter longitude">
					</td>
					<td colspan="2">
						<input type="submit" value="Get location details">
					</td>
				</tr>
			</table>
		</form>
		<hr>

		<form method="post" action="<?php echo base_url('welcome/search')?>">
			<h2>Search</h2>
			<table>
				<tr>
					<td>
						<input type="text" name="param1" value="" placeholder="Enter latitude">
						<input type="text" name="param2" value="" placeholder="Enter longitude">
					</td>
					<td>
						<?php //print_r($cities);exit; ?>
						<select title="Select City" name="param">
							<option value="">Select City</option>
							<?php foreach ($cities as $city): ?>
								<option value="<?php echo $city['name']?>"><?php echo $city['name']?></option>
							<?php endforeach; ?>
						</select>
					</td>
					<td>
						<?php //print_r($cities);exit; ?>
						<select title="Select cuisines" name="param3[]" multiple="multiple">
							<option value="">Select cuisines</option>
							<?php foreach ($cuisines as $cuisine): ?>
								<option value="<?php echo $cuisine['cuisine_name']?>"><?php echo $cuisine['cuisine_name']?></option>
							<?php endforeach; ?>
						</select>
					</td>
					<td>
						<?php //print_r($cities);exit; ?>
						<select title="Select Categories" name="param4[]" multiple="multiple">
							<option value="">Select Categories</option>
							<?php foreach ($categories as $Category): ?>
								<option value="<?php echo $Category['id']?>"><?php echo $Category['name']?></option>
							<?php endforeach; ?>
						</select>
					</td>
					<td colspan="2">
						<input type="submit" value="Search">
					</td>
				</tr>
			</table>
		</form>
		<hr>

		<form method="get" action="<?php echo base_url('welcome/get_reviews')?>">
			<h2>Get reviews</h2>
			<table>
				<tr>
					<td>

						<select title="Select Categories" name="param4">
							<option value="">Select restaurant</option>
							<?php foreach ($restaurants as $restaurant): ?>
								<option value="<?php echo $restaurant['id']?>"><?php echo $restaurant['name'].', '.$restaurant['locality'].', '.$restaurant['city']?></option>
							<?php endforeach; ?>
						</select>
					</td>
					<td colspan="2">
						<input type="submit" value="Get Restaurant Reviews">
					</td>
				</tr>
			</table>
		</form>
		<hr>
	</div>


	<p class="footer"><strong>Powered by</strong> <a href="http://www.ideaapplied.com" target="_blank">IdeaApplied Technologies Pvt ltd.</a> </p>
</div>

</body>
</html>