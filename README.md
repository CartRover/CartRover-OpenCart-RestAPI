# CartRover OpenCart RestAPI 
### -Chen Z. *CIO Tech, Inc* 
			
This extension allows users to retrieve orders and order_status_id from OpenCart without using order_id. 
Also, users can obtain and update inventory levels. 

# PLEASE READ #

This extension has been tested on CopenCart **V2.3.0.2**. 

###:bangbang:ENSURE YOU BACKUP YOUR SITE BEFORE INSTALLING THIS.### 


###:bangbang:CartRover-OpenCart-RestAPI EXTENSION MIGHT CONFLICT WITH OTHER EXISTING EXTENSIONS.###

###:bangbang:IT IS HIGHLY RECOMMENDED TO GET YOUR WEBSITE ADMIN TO REVIEW ALL FILES.###

###:bangbang:INSTALLATION IS AT YOUR OWN RISK.###

# INSTALLATION #

######You may need to install [QuickFix](https://www.opencart.com/index.php?route=marketplace/extension/info&extension_id=18892&filter_search=quick+fix) in order to resolve FTP issue (Highly suggested).

1. Download **upload.ocmod.zip** file from this repository. 
2. Navigate to Extensions > Extension Installer inside your OpenCart admin area.
3. Select **upload.ocmod.zip** zip file and upload it.
4. Go to your System > Users > User Groups to modify extension access permission.
   Edit user groups that allowed to access/edit extension permission. 
   Make sure that fields **feed/gss_api** have been checked under both **Access Permission** and **Modify Permission**.
5. Go to Extensions > Extensions > Feeds. Select Feed, and Install & Enable your GSS API extension.
   If it shows that you have no permission, redo step 4. Uninstall this extension, and follow step 5 again.  

You will notice that the folders are in the same structure as your Opencart installation.

# USAGE #
REST API Endpoint: 
```
1. [GET] YOUR.STORE_URL.com/index.php?route=feed/gss_api/orders
							&token=XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX (Obtain your session token from /index.php?route=api/login endpoint)
							&limit=100 (default 200)
							&offset=0 (default 0)
							&status=2  (order_status code, default 2)
							&date_from=2016-11-12+00:00:00
							&date_to=2017-11-12+00:00:00

2. [GET] YOUR.STORE_URL.com/index.php?route=feed/gss_api/order_status
							&token=XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX (Obtain your session token from /index.php?route=api/login endpoint)
							&status=Shipped (Find order status by going to System > Localisation > Order Statuses)

3. [GET] YOUR.STORE_URL.com/index.php?route=feed/gss_api/obtain_inv_levels
							&token=XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX (Obtain your session token from /index.php?route=api/login endpoint)
							&limit=200 (default 200)
							&offset=0 (default 0)
							&enabled=0 (default 1. [1 => enabled product, 0 => disabled product])

4. [POST] YOUR.STORE_URL.com/index.php?route=feed/gss_api/update_inv_level
							&token=XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX (Obtain your session token from /index.php?route=api/login endpoint)
							POST_FIELDS: array(
											'product_id' => 50 (Obtain product id from route=feed/gss_api/obtain_inv_levels)
											'quantity => 100 (New quantity)
										); 

```