<?php
session_name('siteLogin');
session_start();

$conn = mysqli_connect("localhost", "root", "", "u_shop");

// ADD Client To DB


if (isset($_POST['RegClnt-form'])) {
	$_SESSION['name'] = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
	$name = $_SESSION['name'];
	$_SESSION['user_email'] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
	$email = $_SESSION['user_email'];
	$_SESSION['password'] = $_POST['password'];
	$password = $_SESSION['password'];
	$shapassword = sha1($password);
	$_SESSION['phone'] = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
	$phone = $_SESSION['phone'];
	$insert_client = "INSERT INTO client (name,email,password,phone,lastSeen) VALUES ('$name','$email','$shapassword','$phone',now())";
	$result = mysqli_query($conn, $insert_client);
	echo "<div class='text-center mt-5 alert alert-success error'> تم اضافة البيانات بنجاح قم بتسجيل الدخول </div>";
}


// ADD Vendor To DB 


if (isset($_POST['RegVen-form'])) {


	$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
	$password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
	$shapassword = sha1($password);
	$name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
	$phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);

	$check = "SELECT * FROM vendor where email = '$email' ";

	$res = mysqli_query($conn, $check);

	if (mysqli_num_rows($res)) {
		echo ("الايميل موجود");
		die("انقلع برا يا كلب");
	} else {
		$insert =
			"INSERT INTO vendor (name,email,password,phone,lastseen) VALUES ('$name','$email','$shapassword','$phone', now())";
		$result = mysqli_query($conn, $insert);
		echo "<div class='text-center mt-5 alert alert-success error'> تم اضافة البيانات بنجاح قم بتسجيل الدخول </div>";
	}
}


// Login 

if (isset($_POST['login-form'])) {
	$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
	$password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
	$shapassword = sha1($password);


	$check_vendor = "SELECT * FROM vendor where email = '$email' and password= '$shapassword'";
	$check_client = "SELECT * FROM client where email = '$email' and password= '$shapassword'";
	$check_admin = "SELECT * FROM admin where email = '$email' and password=' $shapassword'";

	$res_admin = mysqli_query($conn, $check_admin);
	$res_vendor = mysqli_query($conn, $check_vendor);
	$res_client = mysqli_query($conn, $check_client);

	if (mysqli_num_rows($res_admin)) {
		error("يرجى الدخول من بوابة الادمن");
	}

	if (mysqli_num_rows($res_client)) {
		$row = mysqli_fetch_array($res_client);
		$_SESSION['id'] = $row['id_client'];
		$_SESSION['name'] = $row['name'];
		$_SESSION['user_email'] = $row['email'];
		$_SESSION['password'] = $row['password'];
		$st = "UPDATE client SET lastseen=now() where id_client=$row[id_client]";
		mysqli_query($conn, $st);
		echo "تم تسجيل الدخول بصفة عميل";
	}

	if (mysqli_num_rows($res_vendor)) {
		$row = mysqli_fetch_array($res_vendor);
		$_SESSION['id'] = $row['id_vendor'];
		$_SESSION['name'] = $row['name'];
		$_SESSION['user_email'] = $row['email'];
		$_SESSION['password'] = $row['password'];
		$_SESSION['regstatus'] = $row['regstatus'];

		if ($row['regstatus'] == 0) {
			echo("لم يتم قبول عضويتك بعد . .");
		} else {
			echo "تم قبول العضويه وسيتم التحويل";
		}
		$st = "UPDATE vendor SET lastSeen=now() where id_vendor=$row[id_vendor]";
		mysqli_query($conn, $st);
		echo "تم تسجيل الدخول بصفة تاجر";

	}

	if (mysqli_num_rows($res_vendor) <= 0 && mysqli_num_rows($res_client) <= 0 && mysqli_num_rows($res_admin) <= 0) {
		echo (" نأسف لم يتم العثور على هذا الحساب يرجى التسجيل حتى تتمكن من الدخول . . ");
	}
}


?>
<!DOCTYPE HTML>

<head>
	<title>Free Home Shoppe Website Template | Home :: w3layouts</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
	<link href="css/slider.css" rel="stylesheet" type="text/css" media="all" />
	<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="js/move-top.js"></script>
	<script type="text/javascript" src="js/easing.js"></script>
	<script type="text/javascript" src="js/startstop-slider.js"></script>
</head>

<body>
	<div class="wrap">
		<div class="header">
			<div class="headertop_desc">
				<div class="call">
					<p><span>Need help?</span> call us <span class="number">079-1125-284</span></span></p>
				</div>
				<div class="account_desc">
					<ul>
						<li><a href="RegVendor.php">Register As Vendor</a></li>
						<li><a href="RegClient.php">Register As Client</a></li>
						<li><a href="login.php">Login</a></li>
					</ul>
				</div>
				<div class="clear"></div>
			</div>
			<div class="header_top">
				<div class="logo">
					<a href="index.html"><img src="images/logo.png" alt="" /></a>
				</div>

				<script type="text/javascript">
					function DropDown(el) {
						this.dd = el;
						this.initEvents();
					}
					DropDown.prototype = {
						initEvents: function() {
							var obj = this;

							obj.dd.on('click', function(event) {
								$(this).toggleClass('active');
								event.stopPropagation();
							});
						}
					}

					$(function() {

						var dd = new DropDown($('#dd'));

						$(document).click(function() {
							// all dropdowns
							$('.wrapper-dropdown-2').removeClass('active');
						});

					});
				</script>
				<div class="clear"></div>
			</div>
			<div class="header_bottom">
				<div class="menu">
					<ul>
						<li class="active"><a href="index.html">Home</a></li>
						<li><a href="about.html">About</a></li>
						<li><a href="delivery.html">Delivery</a></li>
						<li><a href="news.html">News</a></li>
						<li><a href="contact.html">Contact</a></li>
						<div class="clear"></div>
					</ul>
				</div>
				<div class="search_box">
					<form>
						<input type="text" value="Search" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Search';}"><input type="submit" value="">
					</form>
				</div>
				<div class="clear"></div>
			</div>
			<div class="header_slide">
				<div class="header_bottom_left">
					<div class="categories">
						<ul>
							<h3>Categories</h3>
							<li><a href="#">Mobile Phones</a></li>
							<li><a href="#">Desktop</a></li>
							<li><a href="#">Laptop</a></li>
							<li><a href="#">Accessories</a></li>
							<li><a href="#">Software</a></li>
							<li><a href="#">Sports &amp; Fitness</a></li>
							<li><a href="#">Footwear</a></li>
							<li><a href="#">Jewellery</a></li>
							<li><a href="#">Clothing</a></li>
							<li><a href="#">Home Decor &amp; Kitchen</a></li>
							<li><a href="#">Beauty &amp; Healthcare</a></li>
							<li><a href="#">Toys, Kids &amp; Babies</a></li>
						</ul>
					</div>
				</div>
				<div class="header_bottom_right">
					<div class="slider">
						<div id="slider">
							<div id="mover">
								<div id="slide-1" class="slide">
									<div class="slider-img">
										<a href="preview.html"><img src="images/slide-1-image.png" alt="learn more" /></a>
									</div>
									<div class="slider-text">
										<h1>Clearance<br><span>SALE</span></h1>
										<h2>UPTo 20% OFF</h2>
										<div class="features_list">
											<h4>Get to Know More About Our Memorable Services Lorem Ipsum is simply dummy text</h4>
										</div>
										<a href="preview.html" class="button">Shop Now</a>
									</div>
									<div class="clear"></div>
								</div>
								<div class="slide">
									<div class="slider-text">
										<h1>Clearance<br><span>SALE</span></h1>
										<h2>UPTo 40% OFF</h2>
										<div class="features_list">
											<h4>Get to Know More About Our Memorable Services</h4>
										</div>
										<a href="preview.html" class="button">Shop Now</a>
									</div>
									<div class="slider-img">
										<a href="preview.html"><img src="images/slide-3-image.jpg" alt="learn more" /></a>
									</div>
									<div class="clear"></div>
								</div>
								<div class="slide">
									<div class="slider-img">
										<a href="preview.html"><img src="images/slide-2-image.jpg" alt="learn more" /></a>
									</div>
									<div class="slider-text">
										<h1>Clearance<br><span>SALE</span></h1>
										<h2>UPTo 10% OFF</h2>
										<div class="features_list">
											<h4>Get to Know More About Our Memorable Services Lorem Ipsum is simply dummy text</h4>
										</div>
										<a href="preview.html" class="button">Shop Now</a>
									</div>
									<div class="clear"></div>
								</div>
							</div>
						</div>
						<div class="clear"></div>
					</div>
				</div>
				<div class="clear"></div>
			</div>
		</div>
		<div class="main">
			<div class="content">
				<div class="content_top">
					<div class="heading">
						<h3>New Products</h3>
					</div>
					<div class="see">
						<p><a href="#">See all Products</a></p>
					</div>
					<div class="clear"></div>
				</div>
				<div class="section group">
					<div class="grid_1_of_4 images_1_of_4">
						<a href="preview.html"><img src="images/feature-pic1.jpg" alt="" /></a>
						<h2>Lorem Ipsum is simply </h2>
						<div class="price-details">
							<div class="price-number">
								<p><span class="rupees">$620.87</span></p>
							</div>
							<div class="add-cart">
								<h4><a href="preview.html">Add to Cart</a></h4>
							</div>
							<div class="clear"></div>
						</div>

					</div>
					<div class="grid_1_of_4 images_1_of_4">
						<a href="preview.html"><img src="images/feature-pic2.jpg" alt="" /></a>
						<h2>Lorem Ipsum is simply </h2>
						<div class="price-details">
							<div class="price-number">
								<p><span class="rupees">$899.75</span></p>
							</div>
							<div class="add-cart">
								<h4><a href="preview.html">Add to Cart</a></h4>
							</div>
							<div class="clear"></div>
						</div>

					</div>
					<div class="grid_1_of_4 images_1_of_4">
						<a href="preview.html"><img src="images/feature-pic3.jpg" alt="" /></a>
						<h2>Lorem Ipsum is simply </h2>
						<div class="price-details">
							<div class="price-number">
								<p><span class="rupees">$599.00</span></p>
							</div>
							<div class="add-cart">
								<h4><a href="preview.html">Add to Cart</a></h4>
							</div>
							<div class="clear"></div>
						</div>
					</div>
					<div class="grid_1_of_4 images_1_of_4">
						<a href="preview.html"><img src="images/feature-pic4.jpg" alt="" /></a>
						<h2>Lorem Ipsum is simply </h2>
						<div class="price-details">
							<div class="price-number">
								<p><span class="rupees">$679.87</span></p>
							</div>
							<div class="add-cart">
								<h4><a href="preview.html">Add to Cart</a></h4>
							</div>
							<div class="clear"></div>
						</div>
					</div>
				</div>
				<div class="content_bottom">
					<div class="heading">
						<h3>Feature Products</h3>
					</div>
					<div class="see">
						<p><a href="#">See all Products</a></p>
					</div>
					<div class="clear"></div>
				</div>
				<div class="section group">
					<div class="grid_1_of_4 images_1_of_4">
						<a href="preview.html"><img src="images/new-pic1.jpg" alt="" /></a>
						<h2>Lorem Ipsum is simply </h2>
						<div class="price-details">
							<div class="price-number">
								<p><span class="rupees">$849.99</span></p>
							</div>
							<div class="add-cart">
								<h4><a href="preview.html">Add to Cart</a></h4>
							</div>
							<div class="clear"></div>
						</div>
					</div>
					<div class="grid_1_of_4 images_1_of_4">
						<a href="preview.html"><img src="images/new-pic2.jpg" alt="" /></a>
						<h2>Lorem Ipsum is simply </h2>
						<div class="price-details">
							<div class="price-number">
								<p><span class="rupees">$599.99</span></p>
							</div>
							<div class="add-cart">
								<h4><a href="preview.html">Add to Cart</a></h4>
							</div>
							<div class="clear"></div>
						</div>
					</div>
					<div class="grid_1_of_4 images_1_of_4">
						<a href="preview.html"><img src="images/new-pic4.jpg" alt="" /></a>
						<h2>Lorem Ipsum is simply </h2>
						<div class="price-details">
							<div class="price-number">
								<p><span class="rupees">$799.99</span></p>
							</div>
							<div class="add-cart">
								<h4><a href="preview.html">Add to Cart</a></h4>
							</div>
							<div class="clear"></div>
						</div>
					</div>
					<div class="grid_1_of_4 images_1_of_4">
						<a href="preview.html"><img src="images/new-pic3.jpg" alt="" /></a>
						<h2>Lorem Ipsum is simply </h2>
						<div class="price-details">
							<div class="price-number">
								<p><span class="rupees">$899.99</span></p>
							</div>
							<div class="add-cart">
								<h4><a href="preview.html">Add to Cart</a></h4>
							</div>
							<div class="clear"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="footer">
		<div class="wrap">
			<div class="section group">
				<div class="col_1_of_4 span_1_of_4">
					<h4>Information</h4>
					<ul>
						<li><a href="about.html">About Us</a></li>
						<li><a href="contact.html">Customer Service</a></li>
						<li><a href="#">Advanced Search</a></li>
						<li><a href="delivery.html">Orders and Returns</a></li>
						<li><a href="contact.html">Contact Us</a></li>
					</ul>
				</div>
				<div class="col_1_of_4 span_1_of_4">
					<h4>Why buy from us</h4>
					<ul>
						<li><a href="about.html">About Us</a></li>
						<li><a href="contact.html">Customer Service</a></li>
						<li><a href="#">Privacy Policy</a></li>
						<li><a href="contact.html">Site Map</a></li>
						<li><a href="#">Search Terms</a></li>
					</ul>
				</div>
				<div class="col_1_of_4 span_1_of_4">
					<h4>My account</h4>
					<ul>
						<li><a href="contact.html">Sign In</a></li>
						<li><a href="index.html">View Cart</a></li>
						<li><a href="#">My Wishlist</a></li>
						<li><a href="#">Track My Order</a></li>
						<li><a href="contact.html">Help</a></li>
					</ul>
				</div>
				<div class="col_1_of_4 span_1_of_4">
					<h4>Contact</h4>
					<ul>
						<li><span>+91-123-456789</span></li>
						<li><span>+00-123-000000</span></li>
					</ul>
					<div class="social-icons">
						<h4>Follow Us</h4>
						<ul>
							<li><a href="#" target="_blank"><img src="images/facebook.png" alt="" /></a></li>
							<li><a href="#" target="_blank"><img src="images/twitter.png" alt="" /></a></li>
							<li><a href="#" target="_blank"><img src="images/skype.png" alt="" /> </a></li>
							<li><a href="#" target="_blank"> <img src="images/dribbble.png" alt="" /></a></li>
							<li><a href="#" target="_blank"> <img src="images/linkedin.png" alt="" /></a></li>
							<div class="clear"></div>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="copy_right">
		</div>
	</div>
	<script type="text/javascript">
		$(document).ready(function() {
			$().UItoTop({
				easingType: 'easeOutQuart'
			});

		});
	</script>
	<a href="#" id="toTop"><span id="toTopHover"> </span></a>
</body>

</html>