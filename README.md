# OpenCartRestApiV2 
### -Chen Z. *CIO Tech, Inc* 
			
This extension allows users to retrieve orders/order_status_id from OpenCart without using order_id. 

# README / INSTALLATION  #

This extension has been tested on CopenCart V2.3.0.2. Update will be provided in the future. 

###:bangbang:ENSURE YOU BACKUP YOUR SITE BEFORE INSTALLING THIS ###

###:bangbang:OpenCartRestApiV2 EXTENSION MIGHT CONFLICT WITH OTHER EXISTING EXTENSIONS.###

###:bangbang:IT IS HIGHLY RECOMMENDED TO GET YOUR WEBSITE ADMIN TO REVIEW ALL FILES.###

###:bangbang:INSTALLATION IS AT YOUR OWN RISK.###

######You may need to install [QuickFix](https://www.opencart.com/index.php?route=marketplace/extension/info&extension_id=18892&filter_search=quick+fix) in order to resolve FTP issue (Highly suggested).

1. Download **upload.ocmod.zip** file from this repository. 
2. Navigate to Extensions > Extension Installer inside your OpenCart admin area.
3. Select **upload.ocmod.zip** zip file and upload it.
4. Go to your System > Users > User Groups to modify extension access permission.
   Edit user groups that allowed to access/edit extension permission. 
   Make sure that fields **feed/gss_api** have been checked under both **Access Permission** and **Modify Permission**.
5. Go to Extensions > Extensions > Feeds. Select Feed, and Install & Enable your GSS API extension
   If it shows that you have no permission, redo step 4. Uninstall this extension, and follow step 5 again.  

You will notice that the folders are in the same structure as your Opencart installation.

# Usage #
Endpoint: 
```
1. YOUR.STORE_URL.com/index.php?route=feed/gss_api/orders
							&token=zX3msz5Yd74FmlCs86SxhsRG4aa32r6W (Obtain your session token from /index.php?route=api/login endpoint)
							&limit=100 (default 200)
							&offset=0 (default 0)
							&status=2  (order_status code, default 2)
							&date_from=2016-11-12+00:00:00
							&date_to=2017-11-12+00:00:00

2. YOUR.STORE_URL.com/index.php?route=feed/gss_api/order_status
							&token=zX3msz5Yd74FmlCs86SxhsRG4aa32r6W (Obtain your session token from /index.php?route=api/login endpoint)
							&status=Shipped (Find order status by going to System > Localisation > Order Statuses)
```