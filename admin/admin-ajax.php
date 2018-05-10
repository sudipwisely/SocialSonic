<?php require_once(dirname(dirname(__FILE__)) . "/config/config.php");

$case = isset($_REQUEST['case']) ? $_REQUEST['case'] : '';
switch($case) {
	case 'AddproductCategory':
		AddproductCategory();
		break;
	case 'ExpireErrorReport':
		ExpireErrorReport();
		break;
	case 'GetproductCategory':
		GetproductCategory();
		break;
	case 'DeleteproductCategory':
		DeleteproductCategory();
		break;
	case 'Addproduct':
		Addproduct();
		break;
	case 'Getproduct':
		Getproduct();
		break;
	case 'Deleteproduct':
		Deleteproduct();
		break;
	case 'AddNewBlog':
		AddNewBlog();
		break;
	case 'GetProductsByCategory':
		GetProductsByCategory();
		break;
	case 'GetBlog':
		GetBlog();
		break;
	case 'Deleteblog':
		Deleteblog();
		break;
	case 'GetAllCustomersForAdminDashboard';
		GetAllCustomersForAdminDashboard();
		break;
	case 'CustomerModalData':
		CustomerModalData();
		break;
	case 'UpgradeCustomerStatus':
		UpgradeCustomerStatus();
		break;
	default:
		echo '404 not found!';
		break;
}

function AddproductCategory(){
	global $helper;
	if ( empty(trim($_REQUEST['categoryname'])) ) {
		$_SESSION['success_text'] = '';
		$_SESSION['error_text'] = "Category Name cannot be blank.";
		return false;
	}
	if ( empty($_REQUEST['categoryID']) ) {
		if ( empty(trim($_FILES['categoryInput']['name'])) ) {
			$_SESSION['success_text'] = '';
			$_SESSION['error_text'] = "Category Icon cannot be blank.";
			return false;
		}
	}

	$category_file = $_FILES['categoryInput'];
	$file_name = $category_file['name'];
	$file_tmp_name = $category_file['tmp_name'];
	$file_ext = strtolower(end(explode('.',$file_name)));
	$newfile_name = "Categoty_" . rand() . "." . $file_ext; 
	$target = dirname(__FILE__) . '/uploads/categories/'. $newfile_name;
	if ( empty($_REQUEST['categoryID']) ) {
		$checkCatSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "product_categories` WHERE `category_Name` = '" . addslashes(trim($_REQUEST['categoryname'])) . "'");
		if ( $checkCatSQL ) {
			if ( mysql_num_rows($checkCatSQL) > 0 ) {
				$_SESSION['success_text'] = '';
				$_SESSION['error_text'] = "Category name is already exist.";
			} else {
				$productCategorySQL = mysql_query("INSERT INTO `" . DB_PREFIX . "product_categories`(`category_slug`, `category_Name`, `category_Description`, `category_Icon`) VALUES ('" . $helper->create_slug($_REQUEST['categoryname']) . "', '" . addslashes(trim($_REQUEST['categoryname'])) . "', '" . addslashes(trim($_REQUEST['categoryDesc'])) . "', '" . $newfile_name . "')");
				if ( $productCategorySQL ) {
					$buzznew_connection = mysql_connect(BUZZNEWS_DB_SERVER_NAME, BUZZNEWS_DB_USER_NAME, BUZZNEWS_DB_PASSWORD);
					$buzznews_selected = mysql_select_db(BUZZNEWS_DB_NAME, $buzznew_connection);
					$buzznews_table_sql = mysql_query("INSERT INTO `" . BUZZDB_PREFIX . "product_categories`(`category_slug`, `category_Name`, `category_Description`, `category_Icon`) VALUES ('" . $helper->create_slug($_REQUEST['categoryname']) . "', '" . addslashes(trim($_REQUEST['categoryname'])) . "', '" . addslashes(trim($_REQUEST['categoryDesc'])) . "', '" . $newfile_name . "')");
					if ( $buzznews_table_sql ) {
						mysql_close($buzznew_connection);
					}
					move_uploaded_file($file_tmp_name, $target);
					$_SESSION['error_text'] = '';
					$_SESSION['success_text'] = "Category is successfully created.";
				} else {
					$_SESSION['success_text'] = '';
					$_SESSION['error_text'] = "Sorry! server is busy, please try again later.";
				}
			}
		}
	} else {
		if ( !empty($_FILES['categoryInput']) ) {
			$productCategorySQL = mysql_query("UPDATE `" . DB_PREFIX . "product_categories` SET `category_Name` = '" . addslashes(trim($_REQUEST['categoryname'])) . "', `category_Description` = '" . addslashes(trim($_REQUEST['categoryDesc'])) . "', `category_Icon` = '" . $newfile_name . "' WHERE `category_ID` = " . $_REQUEST['categoryID'] . "");
			if ( $productCategorySQL ) {
				$buzznew_connection = mysql_connect(BUZZNEWS_DB_SERVER_NAME, BUZZNEWS_DB_USER_NAME, BUZZNEWS_DB_PASSWORD);
				$buzznews_selected = mysql_select_db(BUZZNEWS_DB_NAME, $buzznew_connection);
				$buzznews_table_sql = mysql_query("UPDATE `" . BUZZDB_PREFIX . "product_categories` SET `category_Name` = '" . addslashes(trim($_REQUEST['categoryname'])) . "', `category_Description` = '" . addslashes(trim($_REQUEST['categoryDesc'])) . "', `category_Icon` = '" . $newfile_name . "' WHERE `category_ID` = " . $_REQUEST['categoryID'] . "");
				if ( $buzznews_table_sql ) {
					mysql_close($buzznew_connection);
				}
			}
			move_uploaded_file( $file_tmp_name, $target);
		} else {
			$productCategorySQL = mysql_query("UPDATE `" . DB_PREFIX . "product_categories` SET `category_Name` = '" . addslashes(trim($_REQUEST['categoryname'])) . "', `category_Description` = '" . addslashes(trim($_REQUEST['categoryDesc'])) . "' WHERE `category_ID` = " . $_REQUEST['categoryID'] . "");
		}
		if ( $productCategorySQL ) {
				$buzznew_connection = mysql_connect(BUZZNEWS_DB_SERVER_NAME, BUZZNEWS_DB_USER_NAME, BUZZNEWS_DB_PASSWORD);
				$buzznews_selected = mysql_select_db(BUZZNEWS_DB_NAME, $buzznew_connection);
				$buzznews_table_sql = mysql_query("UPDATE `" . BUZZDB_PREFIX . "product_categories` SET `category_Name` = '" . addslashes(trim($_REQUEST['categoryname'])) . "', `category_Description` = '" . addslashes(trim($_REQUEST['categoryDesc'])) . "' WHERE `category_ID` = " . $_REQUEST['categoryID'] . "");
				if ( $buzznews_table_sql ) {
					mysql_close($buzznew_connection);
				}
			$_SESSION['error_text'] = '';
			$_SESSION['success_text'] = "Category is successfully updated.";
		} else {
			$_SESSION['success_text'] = '';
			$_SESSION['error_text'] = "Sorry! server is busy, please try again later.";
		}
	}
}

function GetproductCategory() {
	$categoryArr = array();
	$productCategorySQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "product_categories` WHERE `category_ID` = " . $_REQUEST['category_id'] . "");
	if ( $productCategorySQL ) {
		if ( mysql_num_rows($productCategorySQL) > 0 ) {
			$resultset = mysql_fetch_assoc($productCategorySQL);
			$categoryArr[0] = $resultset['category_ID'];
			$categoryArr[1] = $resultset['category_Name'];
			$categoryArr[2] = $resultset['category_Description'];
			$categoryArr[3] = $resultset['category_Icon'];
		}
		echo json_encode($categoryArr);
		exit();
	}
}

function DeleteproductCategory(){
	$productCategorySQL = mysql_query("DELETE FROM `" . DB_PREFIX . "product_categories` WHERE `category_ID` = " . $_REQUEST['category_id'] . "");
	if ( $productCategorySQL ) {
		$buzznew_connection = mysql_connect(BUZZNEWS_DB_SERVER_NAME, BUZZNEWS_DB_USER_NAME, BUZZNEWS_DB_PASSWORD);
		$buzznews_selected = mysql_select_db(BUZZNEWS_DB_NAME, $buzznew_connection);
		$buzznews_table_sql = mysql_query("DELETE FROM `" . BUZZDB_PREFIX . "product_categories` WHERE `category_ID` = " . $_REQUEST['category_id'] . "");
		if ( $buzznews_table_sql ) {
			mysql_close($buzznew_connection);
		}
		$_SESSION['error_text'] = '';
		$_SESSION['success_text'] = "Category is successfully deleted.";
	} else {
		$_SESSION['success_text'] = '';
		$_SESSION['error_text'] = "Sorry! server is busy, please try again later.";
	}
}

function Addproduct() {
	global $helper;
	if ( empty(trim($_REQUEST['productName'])) ) {
		$_SESSION['success_text'] = '';
		$_SESSION['error_text'] = "Product Name cannot be blank.";
		return false;
	}
	if ( empty(trim($_REQUEST['vendorID'])) ) {
		$_SESSION['success_text'] = '';
		$_SESSION['error_text'] = "Please mention Product Vendor ID.";
		return false;
	}
	if ( empty(trim($_REQUEST['category_select'])) ) {
		$_SESSION['success_text'] = '';
		$_SESSION['error_text'] = "Product should be present in any of the category.";
		return false;
	}
	if ( empty(trim($_REQUEST['funnelUrl'])) ) {
		$_SESSION['success_text'] = '';
		$_SESSION['error_text'] = "Please specify your funnel URL.";
		return false;
	} elseif (filter_var($_REQUEST['funnelUrl'], FILTER_VALIDATE_URL) === false) {
		$_SESSION['success_text'] = '';
		$_SESSION['error_text'] = "Please enter a valid funnel URL.";
		return false;
	}
	if ( empty($_REQUEST['productID']) ) {
		if ( empty(trim($_FILES['productFile']['name'])) ) {
			$_SESSION['success_text'] = '';
			$_SESSION['error_text'] = "Product Image cannot be blank.";
			return false;
		}
	}
	
	$product_file = $_FILES['productFile'];
	$file_name = $product_file['name'];
	$file_tmp_name = $product_file['tmp_name'];
	$file_ext = strtolower(end(explode('.', $file_name)));
	$newfile_name = "Product_" . rand() . "." . $file_ext; 
	$target = dirname(__FILE__) . '/uploads/products/' . $newfile_name;

	if ( empty($_REQUEST['productID']) ) {
		$checkPdctSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "products` WHERE `product_name` = '" . addslashes(trim($_REQUEST['productName'])) . "'");
		if ( $checkPdctSQL ) {
			if ( mysql_num_rows($checkPdctSQL) > 0 ) {
				$_SESSION['success_text'] = '';
				$_SESSION['error_text'] = "Product Name is already exist.";
			} else {
				$productSql = mysql_query("INSERT INTO `" . DB_PREFIX . "products`(`product_slug`, `category_ID`, `vendor_Id`, `product_name`, `product_image`, `funnel_url`, `product_created_time`) VALUES ('" . $helper->create_slug($_REQUEST['productName']) . "', " . $_REQUEST['category_select'] . ", '" . addslashes($_REQUEST['vendorID']) . "', '" . addslashes(trim($_REQUEST['productName'])) . "', '" . $newfile_name . "', '" . addslashes(trim($_REQUEST['funnelUrl'])) . "', '" . date('Y-m-d H:i:s') . "')");
				if ( $productSql ) {
					$buzznew_connection = mysql_connect(BUZZNEWS_DB_SERVER_NAME, BUZZNEWS_DB_USER_NAME, BUZZNEWS_DB_PASSWORD);
					$buzznews_selected = mysql_select_db(BUZZNEWS_DB_NAME, $buzznew_connection);
					$buzznews_table_sql = mysql_query("INSERT INTO `" . BUZZDB_PREFIX . "products`(`product_slug`, `category_ID`, `vendor_Id`, `product_name`, `product_image`, `funnel_url`, `product_created_time`) VALUES ('" . $helper->create_slug($_REQUEST['productName']) . "', " . $_REQUEST['category_select'] . ", '" . addslashes($_REQUEST['vendorID']) . "', '" . addslashes(trim($_REQUEST['productName'])) . "', '" . $newfile_name . "', '" . addslashes(trim($_REQUEST['funnelUrl'])) . "', '" . date('Y-m-d H:i:s') . "')");
					if ( $buzznews_table_sql ) {
						mysql_close($buzznew_connection);
					}
					move_uploaded_file($file_tmp_name, $target);
					$_SESSION['error_text'] = '';
					$_SESSION['success_text'] = "Product is successfully created.";
				} else {
					$_SESSION['success_text'] = '';
					$_SESSION['error_text'] = "Sorry! server is busy, please try again later.";
				}
			}
		}
	} else {
		if ( !empty($_FILES['productFile']) ) {
			$productSql = mysql_query("UPDATE `" . DB_PREFIX . "products` SET `category_ID` = " . $_REQUEST['category_select'] . ", `vendor_Id` = '" . addslashes($_REQUEST['vendorID']) . "', `product_name` = '" . addslashes(trim($_REQUEST['productName'])) . "', `product_image` = '" . $newfile_name . "', `funnel_url` = '" . addslashes(trim($_REQUEST['funnelUrl'])) . "', `product_created_time` = '" . date('Y-m-d H:i:s') . "' WHERE `product_ID` = " . $_REQUEST['productID'] . "");
			
			$buzznew_connection = mysql_connect(BUZZNEWS_DB_SERVER_NAME, BUZZNEWS_DB_USER_NAME, BUZZNEWS_DB_PASSWORD);
			$buzznews_selected = mysql_select_db(BUZZNEWS_DB_NAME, $buzznew_connection);
			$buzznews_table_sql = mysql_query("UPDATE `" . BUZZDB_PREFIX . "products` SET `category_ID` = " . $_REQUEST['category_select'] . ", `vendor_Id` = '" . addslashes($_REQUEST['vendorID']) . "', `product_name` = '" . addslashes(trim($_REQUEST['productName'])) . "', `product_image` = '" . $newfile_name . "', `funnel_url` = '" . addslashes(trim($_REQUEST['funnelUrl'])) . "', `product_created_time` = '" . date('Y-m-d H:i:s') . "' WHERE `product_ID` = " . $_REQUEST['productID'] . "");
			if ( $buzznews_table_sql ) {
				mysql_close($buzznew_connection);
			}
			move_uploaded_file($file_tmp_name, $target);
		} else {
			$productSql = mysql_query("UPDATE `" . DB_PREFIX . "products` SET `category_ID` = " . $_REQUEST['category_select'] . ", `vendor_Id` = '" . addslashes($_REQUEST['vendorID']) . "', `product_name` = '" . addslashes(trim($_REQUEST['productName'])) . "', `funnel_url` = '" . addslashes(trim($_REQUEST['funnelUrl'])) . "', `product_created_time` = '" . date('Y-m-d H:i:s') . "' WHERE `product_ID` = " . $_REQUEST['productID'] . "");
			
			$buzznew_connection = mysql_connect(BUZZNEWS_DB_SERVER_NAME, BUZZNEWS_DB_USER_NAME, BUZZNEWS_DB_PASSWORD);
			$buzznews_selected = mysql_select_db(BUZZNEWS_DB_NAME, $buzznew_connection);
			$buzznews_table_sql = mysql_query("UPDATE `" . BUZZDB_PREFIX . "products` SET `category_ID` = " . $_REQUEST['category_select'] . ", `vendor_Id` = '" . addslashes($_REQUEST['vendorID']) . "', `product_name` = '" . addslashes(trim($_REQUEST['productName'])) . "', `funnel_url` = '" . addslashes(trim($_REQUEST['funnelUrl'])) . "', `product_created_time` = '" . date('Y-m-d H:i:s') . "' WHERE `product_ID` = " . $_REQUEST['productID'] . "");
			if ( $buzznews_table_sql ) {
				mysql_close($buzznew_connection);
			}
		}
		if ( $productSql ) {
			$_SESSION['error_text'] = '';
			$_SESSION['success_text'] = "Product is successfully updated.";
		} else {
			$_SESSION['success_text'] = '';
			$_SESSION['error_text'] = "Sorry! server is busy, please try again later.";
		}
	}
}

function Getproduct() {
	$productArr = array();
	$productSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "products` WHERE `product_ID` = " . $_REQUEST['product_id'] . "");
	if ( $productSQL ) {
		if ( mysql_num_rows($productSQL) > 0 ) {
			$resultset = mysql_fetch_assoc($productSQL);
			$productArr[0] = $resultset['product_ID'];
			$productArr[1] = $resultset['category_ID'];
			$productArr[2] = $resultset['vendor_Id'];
			$productArr[3] = $resultset['product_name'];
			$productArr[4] = $resultset['product_image'];
			$productArr[5] = $resultset['funnel_url'];
		}
		echo json_encode($productArr);
		exit();
	}
}

function Deleteproduct() {
	$productSQL = mysql_query("DELETE FROM `" . DB_PREFIX . "products` WHERE `product_ID` = " . $_REQUEST['product_id'] . "");
	if ( $productSQL ) {
			$buzznew_connection = mysql_connect(BUZZNEWS_DB_SERVER_NAME, BUZZNEWS_DB_USER_NAME, BUZZNEWS_DB_PASSWORD);
			$buzznews_selected = mysql_select_db(BUZZNEWS_DB_NAME, $buzznew_connection);
			$buzznews_table_sql = mysql_query("DELETE FROM `" . BUZZDB_PREFIX . "products` WHERE `product_ID` = " . $_REQUEST['product_id'] . "");
			if ( $buzznews_table_sql ) {
				mysql_close($buzznew_connection);
			}
		$_SESSION['error_text'] = '';
		$_SESSION['success_text'] = "Product is successfully deleted.";
	} else {
		$_SESSION['success_text'] = '';
		$_SESSION['error_text'] = "Sorry! server is busy, please try again later.";
	}
}

function AddNewBlog() {
	global $helper;
	if ( $_REQUEST['blogDate'] != '' ) {
		$create_date = date('Y-m-d', strtotime($_REQUEST['blogDate']));
	} else {
		$create_date = date('Y-m-d');
	}
	if ( $_REQUEST['blogID'] == '' ) {
		$blogSQL = mysql_query("INSERT INTO `" . DB_PREFIX . "blogs`(`Blog_Slug`, `Blog_Title`, `Blog_Content`, `Product_ID`, `Category_ID`, `Blog_Date`) VALUES ('" . $helper->create_slug($_REQUEST['blogName']) . "', '" . addslashes(trim($_REQUEST['blogName'])) . "', '" . htmlentities($_REQUEST['ckdata']) . "', '" . addslashes($_REQUEST['blogProduct']) . "', '" . addslashes($_REQUEST['blogCategory']) . "', '" . $create_date . "')");
		if ( $blogSQL ) {
			$buzznew_connection = mysql_connect(BUZZNEWS_DB_SERVER_NAME, BUZZNEWS_DB_USER_NAME, BUZZNEWS_DB_PASSWORD);
			$buzznews_selected = mysql_select_db(BUZZNEWS_DB_NAME, $buzznew_connection);
			$buzznews_table_sql = mysql_query("INSERT INTO `" . DB_PREFIX . "blogs`(`Blog_Slug`, `Blog_Title`, `Blog_Content`, `Product_ID`, `Category_ID`, `Blog_Date`) VALUES ('" . $helper->create_slug($_REQUEST['blogName']) . "', '" . addslashes(trim($_REQUEST['blogName'])) . "', '" . htmlentities($_REQUEST['ckdata']) . "', '" . addslashes($_REQUEST['blogProduct']) . "', '" . addslashes($_REQUEST['blogCategory']) . "', '" . $create_date . "')");
			if ( $buzznews_table_sql ) {
				mysql_close($buzznew_connection);
			}
			$_SESSION['error_text'] = '';
			$_SESSION['success_text'] = "Blog is successfully created.";
		} else {
			$_SESSION['success_text'] = '';
			$_SESSION['error_text'] = "Sorry! server is busy, please try again later.";
		}
	} else {
		if ( $_REQUEST['blogDate'] != '' ) {
			$blogSQL = mysql_query("UPDATE `" . DB_PREFIX . "blogs` SET `Blog_Title` = '" . addslashes(trim($_REQUEST['blogName'])) . "', `Blog_Content` = '" . htmlentities($_REQUEST['ckdata']) . "', `Product_ID` = '" . addslashes($_REQUEST['blogProduct']) . "', `Category_ID` = '" . addslashes($_REQUEST['blogCategory']) . "', `Blog_Date` = '" . $create_date . "' WHERE `Blog_ID` = " . $_REQUEST['blogID']);
			$buzznew_connection = mysql_connect(BUZZNEWS_DB_SERVER_NAME, BUZZNEWS_DB_USER_NAME, BUZZNEWS_DB_PASSWORD);
			$buzznews_selected = mysql_select_db(BUZZNEWS_DB_NAME, $buzznew_connection);
			$buzznews_table_sql = mysql_query("UPDATE `" . DB_PREFIX . "blogs` SET `Blog_Title` = '" . addslashes(trim($_REQUEST['blogName'])) . "', `Blog_Content` = '" . htmlentities($_REQUEST['ckdata']) . "', `Product_ID` = '" . addslashes($_REQUEST['blogProduct']) . "', `Category_ID` = '" . addslashes($_REQUEST['blogCategory']) . "', `Blog_Date` = '" . $create_date . "' WHERE `Blog_ID` = " . $_REQUEST['blogID']);
			if ( $buzznews_table_sql ) {
				mysql_close($buzznew_connection);
			}
		} else {
			$blogSQL = mysql_query("UPDATE `" . DB_PREFIX . "blogs` SET `Blog_Title` = '" . addslashes(trim($_REQUEST['blogName'])) . "', `Blog_Content` = '" . htmlentities($_REQUEST['ckdata']) . "', `Product_ID` = '" . addslashes($_REQUEST['blogProduct']) . "', `Category_ID` = '" . addslashes($_REQUEST['blogCategory']) . "', `Blog_Date` = CURDATE() WHERE `Blog_ID` = " . $_REQUEST['blogID']);
			$buzznew_connection = mysql_connect(BUZZNEWS_DB_SERVER_NAME, BUZZNEWS_DB_USER_NAME, BUZZNEWS_DB_PASSWORD);
			$buzznews_selected = mysql_select_db(BUZZNEWS_DB_NAME, $buzznew_connection);
			$buzznews_table_sql = mysql_query("UPDATE `" . DB_PREFIX . "blogs` SET `Blog_Title` = '" . addslashes(trim($_REQUEST['blogName'])) . "', `Blog_Content` = '" . htmlentities($_REQUEST['ckdata']) . "', `Product_ID` = '" . addslashes($_REQUEST['blogProduct']) . "', `Category_ID` = '" . addslashes($_REQUEST['blogCategory']) . "', `Blog_Date` = CURDATE() WHERE `Blog_ID` = " . $_REQUEST['blogID']);
			if ( $buzznews_table_sql ) {
				mysql_close($buzznew_connection);
			}
		}
		if ( $blogSQL ) {
			$_SESSION['error_text'] = '';
			$_SESSION['success_text'] = "Blog is successfully updated.";
		} else {
			$_SESSION['success_text'] = '';
			$_SESSION['error_text'] = "Sorry! server is busy, please try again later.";
		}
	}
}

function GetProductsByCategory() {
	$products = '';
	$cat_id = $_REQUEST['cat_id'];
	$productSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "products` WHERE `category_ID` = $cat_id");
	if ( $productSQL ) {
		if ( mysql_num_rows($productSQL) > 0 ) {
			while ( $productsData = mysql_fetch_assoc($productSQL) ) {
				$products .= $productsData['product_ID'] . '<=>' . $productsData['product_name'] . '###';
			}
		} else {
			$products = '';
		}
	}
	$products = substr($products, 0, -3);
	echo $products;
}

function GetBlog() {
	$blogArr = array();
	$blogSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "blogs` WHERE `Blog_ID` = " . $_REQUEST['blog_id'] . "");
	if ( $blogSQL ) {
		if ( mysql_num_rows($blogSQL) > 0 ) {
			$resultset = mysql_fetch_assoc($blogSQL);
			$blogArr[0] = $resultset['Blog_ID'];
		}
		echo json_encode($blogArr);
		exit();
	}
}

function Deleteblog() {
	$blogSQL = mysql_query("DELETE FROM `" . DB_PREFIX . "blogs` WHERE `Blog_ID` = " . $_REQUEST['blog_id'] . "");
	if ( $blogSQL ) {
			$buzznew_connection = mysql_connect(BUZZNEWS_DB_SERVER_NAME, BUZZNEWS_DB_USER_NAME, BUZZNEWS_DB_PASSWORD);
			$buzznews_selected = mysql_select_db(BUZZNEWS_DB_NAME, $buzznew_connection);
			$buzznews_table_sql = mysql_query("DELETE FROM `" . BUZZDB_PREFIX . "blogs` WHERE `Blog_ID` = " . $_REQUEST['blog_id'] . "");
			if ( $buzznews_table_sql ) {
				mysql_close($buzznew_connection);
			}
		$_SESSION['error_text'] = '';
		$_SESSION['success_text'] = "Blog is successfully deleted.";
	} else {
		$_SESSION['success_text'] = '';
		$_SESSION['error_text'] = "Sorry Server Busy try again later ...";
	}
}

function ExpireErrorReport() {
	$_SESSION['error_text'] = '';
	$_SESSION['success_text'] = '';
}

function GetAllCustomersForAdminDashboard() {
	$requestData = $_REQUEST;
	$columns = array(
		0 => 'Cust_ID',
		1 => 'Cust_FirstName',
		2 => 'Cust_Screen_Name'
	);

	$sql  = "SELECT `Cust_ID` ";
	$sql .= " FROM `" . DB_PREFIX . "customers`";
	$query = mysql_query($sql);
	$totalData = mysql_num_rows($query);
	$totalFiltered = $totalData;

	$sql  = "SELECT *";
	$sql .= " FROM `" . DB_PREFIX . "customers` WHERE 1 = 1";
	if ( !empty($requestData['search']['value']) ) {
		$sql .= " AND (`Cust_ID` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR CONCAT(TRIM(`Cust_FirstName`), ' ', TRIM(`Cust_LastName`)) LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `Cust_Screen_Name` LIKE '" . $requestData['search']['value'] . "%')";
	}
	$query = mysql_query($sql);
	$totalFiltered = mysql_num_rows($query);
	$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
	$query = mysql_query($sql);

	$data = array();
	$i = 1 + $requestData['start'];
	while ( $row = mysql_fetch_assoc($query) ) {
		$nestedData = array();

		// zero column
		$nestedData[] = $row["Cust_ID"];

		// first column
		$nestedData[] = $row["Cust_FirstName"] . ' ' . $row["Cust_LastName"];

		// second column
		$nestedData[] = '<a href="https://twitter.com/' . $row['Cust_Screen_Name'] . '" target="_blank">' . $row["Cust_Screen_Name"] . '</a>';

		// third column
		$completeSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "schedule_tweet` WHERE `user_id` = " . $row['Cust_ID'] . " AND `status` = 'complete'");
		if ( $completeSQL ) {
			$sentNum = mysql_num_rows($completeSQL);
		} else {
			$sentNum = 0;
		}
		$pendingSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "schedule_tweet` WHERE `user_id` = " . $row['Cust_ID'] . " AND `status` = 'pending'");
		if ( $pendingSQL ) {
			$upNum = mysql_num_rows($pendingSQL);
		} else {
			$upNum = 0;
		}
		$nestedData[] = $sentNum . '/' . $upNum;

		// fourth column
		$dmCSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "dm_status` WHERE `user_id` = '" . $row['Cust_ID'] . "' AND `message_status` = 4");
		if ( $dmCSQL ) {
			$dmCNum = mysql_num_rows($dmCSQL);
		} else {
			$dmCNum = 0;
		}
		$dmUSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "dm_status` WHERE `user_id` = '" . $row['Cust_ID'] . "' AND `message_status` != 4");
		if ( $dmUSQL ) {
			$dmUNum = mysql_num_rows($dmUSQL);
		} else {
			$dmUNum = 0;
		}
		$nestedData[] = $dmCNum . '/' . $dmUNum;

		// fifth column
		$keywordSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "prospects` WHERE `Cust_ID` = " . $row['Cust_ID']);
		if (  $keywordSQL ) {
			$keywordNum = mysql_num_rows($keywordSQL);
		} else {
			$keywordNum = 0;
		}
		if ( $keywordNum > 0 ) {
			$keywords = mysql_fetch_assoc($keywordSQL);
		} else {
			$keywords = array();
		}
		$categorySQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "prospects` WHERE `Cust_ID` = " . $row['Cust_ID']);
		if (  $categorySQL ) {
			$categoryNum = mysql_num_rows($categorySQL);
		} else {
			$categoryNum = 0;
		}
		if ( $categoryNum > 0 ) {
			$categories = mysql_fetch_assoc($categorySQL);
		} else {
			$categories = array();
		}
		$fourthCol = '<strong>';
		$fourthCol .= !empty($keywords['Talks_About']) ? '<font color="green">Keyword</font>' : '<font color="red">Keyword</font>';
		$fourthCol .= '/';
		$fourthCol .=  !empty($categories['Influencers']) ? '<font color="green">Category</font>' : '<font color="red">Category</font>';
		$fourthCol .= '</strong>';
		$nestedData[] = $fourthCol;

		// sixth column
		$twitteroauth = new TwitterOAuth($row['Cust_API_Key'], $row['Cust_API_Secret'], $row['Cust_Access_Token'], $row['Cust_Token_Secret']);
		$content = $twitteroauth->get('account/verify_credentials');
		if ( isset($content->errors) ) {
			$nestedData[] = '<font color="red"><strong>' . $content->errors[0]->message . '</strong></font>';
		} else {
			if ( !empty($row['Cust_App_Error']) ) {
				$nestedData[] = '<font color="red"><strong>' . $resultset['Cust_App_Error'] . '</strong></font>';
			} else {
				$nestedData[] = '<font color="green"><strong>Active</strong></font>';
			}
		}

		$data[] = $nestedData;
		$i++;
	}

	$json_data = array(
		"draw"            => intval($requestData['draw']),
		"recordsTotal"    => intval($totalData),
		"recordsFiltered" => intval($totalFiltered),
		"data"            => $data
	);
	echo json_encode($json_data);
}

function CustomerModalData() {
	global $mongoDb;

	$output = array();
	$Cust_ID = $_REQUEST['Cust_ID'];
	$customersSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "customers` WHERE `Cust_ID` = $Cust_ID LIMIT 0, 1");
	$resultset = mysql_fetch_assoc($customersSQL);

	$output['general']  = '<table class="table">';
	$output['general'] .= '<tr>';
	$output['general'] .= '<td width="30%">Full Name: </td>';
	$output['general'] .= '<td width="70%">';
	$output['general'] .= $resultset['Cust_FirstName'] . ' ' . $resultset['Cust_LastName'];
	$output['general'] .= '</td>';
	$output['general'] .= '</tr>';
	$output['general'] .= '<tr>';
	$output['general'] .= '<td>Username: </td>';
	$output['general'] .= '<td>' . $resultset['Cust_UserName'] . '</td>';
	$output['general'] .= '</tr>';
	$output['general'] .= '<tr>';
	$output['general'] .= '<td>Email Address: </td>';
	$output['general'] .= '<td>' . $resultset['Cust_Email'] . '</td>';
	$output['general'] .= '</tr>';
	$output['general'] .= '<tr>';
	$output['general'] .= '<td>Clickbank Hop Code: </td>';
	$output['general'] .= '<td>' . $resultset['Cust_hopCode'] . '</td>';
	$output['general'] .= '</tr>';
	$output['general'] .= '<tr>';
	$output['general'] .= '<td>Order ID: </td>';
	$output['general'] .= '<td>' . $resultset['Cust_Order_ID'] . '</td>';
	$output['general'] .= '</tr>';
	$output['general'] .= '<tr>';
	$output['general'] .= '<td>Last Login: </td>';
	$output['general'] .= '<td>' . $resultset['Cust_Last_Login_Time'] . '</td>';
	$output['general'] .= '</tr>';
	$output['general'] .= '</table>';
	
	$output['twitter']  = '<table class="table">';
	$output['twitter'] .= '<tr>';
	$output['twitter'] .= '<td width="30%">Twitter ID: </td>';
	$output['twitter'] .= '<td width="70%">' . $resultset['Cust_Twitter_ID'] . '</td>';
	$output['twitter'] .= '</tr>';
	$output['twitter'] .= '<tr>';
	$output['twitter'] .= '<td>Screen Name: </td>';
	$output['twitter'] .= '<td>' . $resultset['Cust_Screen_Name'] . '</td>';
	$output['twitter'] .= '</tr>';
	$output['twitter'] .= '<tr>';
	$output['twitter'] .= '<td>Consumer Key: </td>';
	$output['twitter'] .= '<td>' . $resultset['Cust_API_Key'] . '</td>';
	$output['twitter'] .= '</tr>';
	$output['twitter'] .= '<tr>';
	$output['twitter'] .= '<td>Consumer Secret: </td>';
	$output['twitter'] .= '<td>' . $resultset['Cust_API_Secret'] . '</td>';
	$output['twitter'] .= '</tr>';
	$output['twitter'] .= '<tr>';
	$output['twitter'] .= '<td>Access Token: </td>';
	$output['twitter'] .= '<td>' . $resultset['Cust_Access_Token'] . '</td>';
	$output['twitter'] .= '</tr>';
	$output['twitter'] .= '<tr>';
	$output['twitter'] .= '<td>Access Token Secret: </td>';
	$output['twitter'] .= '<td>' . $resultset['Cust_Token_Secret'] . '</td>';
	$output['twitter'] .= '</tr>';
	$output['twitter'] .= '<tr>';
	$output['twitter'] .= '<td>App Status</td>';
	$twitteroauth = new TwitterOAuth($resultset['Cust_API_Key'], $resultset['Cust_API_Secret'], $resultset['Cust_Access_Token'], $resultset['Cust_Token_Secret']);
	$content = $twitteroauth->get('account/verify_credentials');
	$output['twitter'] .= '<td>';
	if ( isset($content->errors) ) {
		$output['twitter'] .= '<font color="red"><strong>' . $content->errors[0]->message . '</strong></font>';
	} else {
		if ( !empty($resultset['Cust_App_Error']) ) {
			$output['twitter'] .= '<font color="red"><strong>' . $resultset['Cust_App_Error'] . '</strong></font>';
		} else {
			$output['twitter'] .= '<font color="green"><strong>Active</strong></font>';
		}
	}
	$output['twitter'] .= '</td>';
	$output['twitter'] .= '</tr>';
	$output['twitter'] .= '</table>';

	$output['keyword']  = '<table class="table">';
	$output['keyword'] .= '<tr>';
	$output['keyword'] .= '<td width="30%">Campaign Status: </td>';
	$keywordSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "prospects` WHERE `Cust_ID` = " . $resultset['Cust_ID']);
	if (  $keywordSQL ) {
		$keywordNum = mysql_num_rows($keywordSQL);
	} else {
		$keywordNum = 0;
	}
	if ( $keywordNum > 0 ) {
		$keywords = mysql_fetch_assoc($keywordSQL);
	} else {
		$keywords = array();
	}
	$output['keyword'] .= '<td width="70%">';
	$output['keyword'] .= !empty($keywords['Talks_About']) ? 'Running' : 'No Running Campaign';
	$output['keyword'] .= '</td>';
	$output['keyword'] .= '</tr>';
	$output['keyword'] .= '<tr>';
	$output['keyword'] .= '<td>Keywords: </td>';
	$output['keyword'] .= '<td>';
	$output['keyword'] .= !empty($keywords['Talks_About']) ? $keywords['Talks_About'] : 'No Keywords';
	$output['keyword'] .= '</td>';
	$output['keyword'] .= '</tr>';
	$output['keyword'] .= '<tr>';
	$output['keyword'] .= '<td>Prospect Found: </td>';
	$document = $mongoDb->prospect_keywords->find(array('search_user_id' => $resultset['Cust_ID']));
	$output['keyword'] .= '<td>' . $document->count() . '</td>';
	$output['keyword'] .= '</tr>';
	$output['keyword'] .= '<tr>';
	$output['keyword'] .= '<td>Prospect Added: </td>';
	$document = $mongoDb->prospect_keywords->find(array('search_user_id' => $resultset['Cust_ID'], 'status' => 'complete'));
	$output['keyword'] .= '<td>' . $document->count() . '</td>';
	$output['keyword'] .= '</tr>';
	$output['keyword'] .= '<tr>';
	$output['keyword'] .= '<td>Prospect Follow: </td>';
	$document = $mongoDb->prospect_keywords->find(array('search_user_id' => $resultset['Cust_ID'], 'status' => 'follow'));
	$output['keyword'] .= '<td>' . $document->count() . '</td>';
	$output['keyword'] .= '</tr>';
	$output['keyword'] .= '</table>';
	
	$output['category']  = '<table class="table">';
	$output['category'] .= '<tr>';
	$output['category'] .= '<td width="30%">Campaign Status: </td>';
	$categorySQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "prospects` WHERE `Cust_ID` = " . $resultset['Cust_ID']);
	if (  $categorySQL ) {
		$categoryNum = mysql_num_rows($categorySQL);
	} else {
		$categoryNum = 0;
	}
	if ( $categoryNum > 0 ) {
		$categories = mysql_fetch_assoc($categorySQL);
	} else {
		$categories = array();
	}
	$output['category'] .= '<td width="70%">';
	$output['category'] .= !empty($categories['Influencers']) ? 'Running' : 'No Running Campaign';
	$output['category'] .= '</td>';
	$output['category'] .= '</tr>';
	$output['category'] .= '<tr>';
	$output['category'] .= '<td>Influencers: </td>';
	if ( !empty($categories['Influencers']) ) {
		$influencers = explode(',', $categories['Influencers']);
		$screennames = '';
		foreach ( $influencers as $influencer ) {
			$influencerQuery = mysql_query("SELECT * FROM `" . DB_PREFIX . "influencers` WHERE `influncer_twitter_id` = '$influencer'");
			if ( $influencerQuery ) {
				$influencerData = mysql_fetch_assoc($influencerQuery);
				$screennames .= $influencerData['influncer_twitter_screenname'] . ', ';
			} 
		}
		$screennames = substr($screennames, 0, -2);
	} else {
		$screennames = 'No Influencers';
	}
	$output['category'] .= '<td>' . $screennames . '</td>';
	$output['category'] .= '</tr>';
	$output['category'] .= '<tr>';
	$output['category'] .= '<td>Category: </td>';
	if ( !empty($categories['Influencers']) ) {
		if ( $categories['Category'] == 0 ) {
			$category = 'Own Search';
		} else {
			$categoryQuery = mysql_query("SELECT * FROM `" . DB_PREFIX . "influencer_categories` WHERE `twscrapp_category_id` = " . $categories['Category']);
			if ( $categoryQuery ) {
				$categoryData = mysql_fetch_assoc($categoryQuery);
				$category = $categoryData['twscrapp_category_name'];
			} else {
				$category = 'No Category';
			}
		}
	} else {
		$category = 'No Category';
	}
	$output['category'] .= '<td>' . $category . '</td>';
	$output['category'] .= '</tr>';
	$output['category'] .= '<tr>';
	$output['category'] .= '<td>Prospect Found: </td>';
	$document = $mongoDb->prospect_influencers->find(array('search_user_id' => $resultset['Cust_ID']));
	$output['category'] .= '<td>' . $document->count() . '</td>';
	$output['category'] .= '</tr>';
	$output['category'] .= '<tr>';
	$output['category'] .= '<td>Prospect Added: </td>';
	$document = $mongoDb->prospect_influencers->find(array('search_user_id' => $resultset['Cust_ID'], 'status' => 'complete'));
	$output['category'] .= '<td>' . $document->count() . '</td>';
	$output['category'] .= '</tr>';
	$output['category'] .= '<tr>';
	$output['category'] .= '<td>Prospect Follow: </td>';
	$document = $mongoDb->prospect_influencers->find(array('search_user_id' => $resultset['Cust_ID'], 'status' => 'follow'));
	$output['category'] .= '<td>' . $document->count() . '</td>';
	$output['category'] .= '</tr>';
	$output['category'] .= '</table>';

	$output['tweets']  = '<table class="table">';
	$output['tweets'] .= '<tr>';
	$output['tweets'] .= '<td width="30%">Tweet Sent: </td>';
	$completeSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "schedule_tweet` WHERE `user_id` = " . $resultset['Cust_ID'] . " AND `status` = 'complete'");
	if ( $completeSQL ) {
		$sentNum = mysql_num_rows($completeSQL);
	} else {
		$sentNum = 0;
	}
	$output['tweets'] .= '<td width="70%">' . $sentNum . '</td>';
	$output['tweets'] .= '</tr>';
	$output['tweets'] .= '<tr>';
	$output['tweets'] .= '<td width="30%">Upcoming Tweets: </td>';
	$pendingSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "schedule_tweet` WHERE `user_id` = " . $resultset['Cust_ID'] . " AND `status` = 'pending'");
	if ( $pendingSQL ) {
		$upNum = mysql_num_rows($pendingSQL);
	} else {
		$upNum = 0;
	}
	$output['tweets'] .= '<td width="70%">' . $upNum . '</td>';
	$output['tweets'] .= '</tr>';
	$output['tweets'] .= '</table>';

	$output['dm']  = '<table class="table">';
	$output['dm'] .= '<tr>';
	$output['dm'] .= '<td width="30%">DM Sent: </td>';
	$dmCSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "dm_status` WHERE `user_id` = '" . $resultset['Cust_ID'] . "' AND `message_status` = 4");
	if ( $dmCSQL ) {
		$dmCNum = mysql_num_rows($dmCSQL);
	} else {
		$dmCNum = 0;
	}
	$output['dm'] .= '<td width="70%">' . $dmCNum . '</td>';
	$output['dm'] .= '</tr>';
	$output['dm'] .= '<tr>';
	$output['dm'] .= '<td>Upcoming DM: </td>';
	$dmUSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "dm_status` WHERE `user_id` = '" . $resultset['Cust_ID'] . "' AND `message_status` != 4");
	if ( $dmUSQL ) {
		$dmUNum = mysql_num_rows($dmUSQL);
	} else {
		$dmUNum = 0;
	}
	$output['dm'] .= '<td>' . $dmUNum . '</td>';
	$output['dm'] .= '</tr>';
	$output['dm'] .= '</table>';

	$output['upgrade']  = '<table class="table">';
	if ( $resultset['Cust_Payment_Type'] == 'trip' ) {
		$output['upgrade'] .= '<tr>';
		$output['upgrade'] .= '<td width="30%">Customer Status: </td>';
		$output['upgrade'] .= '<td width="70%">LIMITED</td>';
		$output['upgrade'] .= '</tr>';
		$output['upgrade'] .= '<tr>';
		$output['upgrade'] .= '<td>Update Status: </td>';
		$output['upgrade'] .= '<td><button type="button" data-custid="' . $resultset['Cust_ID'] . '" class="btn btn-primary upgrade_cust_status">UPDATE</button></td>';
		$output['upgrade'] .= '</tr>';
	} else {
		$output['upgrade'] .= '<tr>';
		$output['upgrade'] .= '<td width="30%">Customer Status: </td>';
		$output['upgrade'] .= '<td width="70%">UNLIMITED</td>';
		$output['upgrade'] .= '</tr>';
	}
	$output['upgrade'] .= '</table>';

	echo json_encode($output);
}

function UpgradeCustomerStatus() {
	$Cust_ID = $_GET['Cust_ID'];
	$update = mysql_query("UPDATE `" . DB_PREFIX . "customers` SET `Cust_Payment_Type` = 'normal' WHERE `Cust_ID` = $Cust_ID");
	if ( $update ) {
		$_SESSION['error_text'] = '';
		$_SESSION['success_text'] = "Customer Account is successfully updated.";
	} else {
		$_SESSION['success_text'] = '';
		$_SESSION['error_text'] = "Sorry! server is busy, please try again later.";
	}
}