
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0014)about:internet -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="<?php echo $config['LIVE_URL']; ?>mobileapps/web/views/js/jquery.js"></script>
<script src="<?php echo $config['LIVE_URL']; ?>mobileapps/web/views/js/validation.js"></script>
<script type="text/javascript">
var actionUrl = "<?php echo $config['LIVE_URL']; ?>mobileapps/web/credit_app/<?php echo $arrClientDetails[0]['id']?>/action/";
var thanksUrl = "<?php echo $config['LIVE_URL']; ?>mobileapps/web/thanks/";
</script>
<title>ShopWell_Mobile_Credit_App</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=display-width, initial-scale=1">


   <link href="<?php echo $config['LIVE_URL']; ?>mobileapps/web/views/css/MoreThan640Media.css" rel="stylesheet" type="text/css" media="screen and (min-width: 640px)"/>
   
     <link href="<?php echo $config['LIVE_URL']; ?>mobileapps/web/views/css/MoreThan640Media.css" rel="stylesheet" type="text/css" media="screen and (max-width: 720px)"/>
     
    <link href="<?php echo $config['LIVE_URL']; ?>mobileapps/web/views/css/LessThan639Media.css" rel="stylesheet" type="text/css" media="screen and (max-width: 639px)"/>
    <link href="<?php echo $config['LIVE_URL']; ?>mobileapps/web/views/css/LessThan480Media.css" rel="stylesheet" type="text/css" media="screen and (max-width: 480px)"/>



    <link rel='stylesheet' id='Varela-font-css'  href='http://fonts.googleapis.com/css?family=Varela&#038;subset=latin%2Ccyrillic%2Cgreek&#038;ver=3.4.2' type='text/css' media='all' />
    <link rel='stylesheet' id='Droid-google-font-css'  href='http://fonts.googleapis.com/css?family=Droid+Sans&#038;ver=3.4.2' type='text/css' media='all' />
    <link rel='stylesheet' id='Yanone-google-font-css'  href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz%3A200%2C400&#038;ver=3.4.2' type='text/css' media='all' />
    
    
</head>
<body  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?php /*?><div id="head"  class="head" style="background-color:<?php echo "#".$arrClientDetails[0]['background_color'];?>">
<?php 

if($arrClientDetails[0]['logo'] != ''){?>
<img src="<?php echo  $config['LIVE_URL']."files/clients/".$arrClientDetails[0]['id']."/logo/".$arrClientDetails[0]['logo']?>" />
<?php }else{ echo $arrClientDetails[0]['name'];}?>

</div><?php */?>
<br />
<br />
<br />
<div class="maindiv" id="mainDiv">
<div></div>
<div class="heading"><b>Complete Credit Application</b></div><br />
<div class="desc">
We must obtain and verify information that identifies each person who opens an account. Please fill in the following fields (those marked * are required fields).<br />
</div>
<?php /*?><p bgcolor="#19594F" class="head">SHOP WELL</p><?php */?>
<form name="form" id="form" action="<?php echo $config['LIVE_URL']."mobileapps/web/credit_app/".$pUrl[3]."/action"; ?>" method="post">
<div id="enterFirstName" class="label">First Name <span style="color:#E30812">*</span></div>

<div id="afterFirstName">
<input onfocus="if (this.value == 'Enter your first name.'){this.className='boxes', this.value=''}" value="<?php echo $first_name;?>"  onblur="if (this.value == ''){this.value = 'Enter your first name.',this.className='hint'}" type="text" class="<?php echo $fclassname;?>" name="first_name" id="first_name" /></div>

<div id="enterLastName" class="label">Last Name <span style="color:#E30812">*</span></div>

<div id="afterLastName"><input onfocus="if (this.value == 'Enter your last name.'){ this.value='', this.className='boxes'}" value="<?php echo $last_name;?>"   onblur="if (this.value == ''){this.value = 'Enter your last name.',this.className='hint'}" type="text" class="<?php echo $lclassname;?>" name="last_name" id="last_name"/></div>

<div id="enterBirthdate" class="label">Birth Date <span style="color:#E30812">*</span></div>

<div id="afterBirthdate"><input  value="<?php echo $date_of_birth;?>"  placeholder="Enter your date of birth" type="date" class="<?php echo $bclassname;?>"  name="date_of_birth" id="date_of_birth" /></div>


<div id="enterEmail" class="label">Email <span style="color:#E30812">*</span></div>

<div id="afterEmail"><input onfocus="if (this.value == 'Enter your email address.'){ this.value='',this.className='boxes'}" value="<?php echo $email;?>" onblur="if (this.value == ''){this.value = 'Enter your email address.',this.className='hint'}" type="email" class="<?php echo $eclassname;?>"  name="email" id="email" /></div>
<span style="color:#F00" name="spanerr" id="spanerr"></span>
<div><input type="hidden" name="client_name" id="client_name" value="<?php echo $client_name;?>" /></div>
<div ><input type="button" value="Save" class="button" onclick="validate();" style="background-color:<?php echo "#".$arrClientDetails[0]['dark_color'];?>"/> <input type="button" value="Clear" class="button" style="background-color:<?php echo "#".$arrClientDetails[0]['dark_color'];?>" onclick="clearval();"/>


</div>
</form>

<br />
<br />
</div>

</body>
</html>
