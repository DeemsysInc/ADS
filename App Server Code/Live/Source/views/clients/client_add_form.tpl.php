<div id="control-bar" class="grey-bg clearfix" style="opacity: 1;">
  <div class="container_12">
    <div class="float-left">
      <button type="button" onclick="history.go(-1);return false;"><img src="<?php echo $config['LIVE_URL']; ?>views/images/icons/fugue/navigation-180.png" width="16" height="16" > Back</button>
    </div>
    <div id='loadingmessage' style='display:none'> <img src='<?php echo $config['LIVE_URL']."views/images/loading-cafe.gif";?>'/> </div>
    <?php /*?>	<div class="float-right"> 
			<button type="button" onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/add'">Add Client</button> 
		</div><?php */?>
  </div>
</div>
<style type="text/css">
#form_content ul li, #form_content ul li input{
	margin-bottom:10px;
}
#form_content ul li input{
	width:70%;
	margin-top:6px;
}
#form_content ul li select{
	width:73%;
	margin-top:6px;
}
.colx2-left_m {
width: 48%;

margin-bottom: 0;
}
.colx2-right {
width: 59%;

margin-bottom: 0;
}

</style>
<section class="grid_12">
  <div class="block-border">
    
    <form id="frm_client_add" name="frm_client_add" method="post" action="" class="block-content form" enctype="multipart/form-data">
      
      <h1>Add Client Details</h1>
      <div id="form_content">
      <p><span style="color:red;">*</span> Fields are mandatory</p>
      
        <ul>
          <li class="colx2-right">
            <fieldset style="height:215px;" class="block-content form">
              <h1>Upload Logo:</h1>
              <br />
              <div id="frm_error2" style="display:none;">
                  <p style="">&nbsp;</p>
              </div>
                      
              
              <p style="float:left;padding:25px;"> <img id="img_prev"  src="<?php echo $config['LIVE_URL']."views/images/no_logo.png";?>" alt="client logo" width="253px" height="115px" /> </p>
              <p style="float:right;padding:12px;">
                <input type="file" onchange="previewLogo(this);" id="c_logo" name="c_logo" />
                <?php /*?><button class="blue" type="button">Modify Logo</button><?php */?>
              </p>
            </fieldset>
            <fieldset style="height:280px;" class="block-content form">
              <h1>Upload Background Image:</h1>
              <br />
               <div id="frm_error3" style="display:none;">
                  <p style="">&nbsp;</p>
              </div>
              <p style="float:left;padding:25px;"> <img id="img_prev_bgimage"  src="<?php echo $config['LIVE_URL']."views/images/no-product.png"; ?>" alt="Client Background image" width="253px" height="230px"/> </p>
              <p style="float:right;padding:12px;">
                <input type="file" onchange="previewBgImage(this);" id="c_bgimage" name="c_bgimage" />
                <?php /*?><button class="blue" type="button">Modify Logo</button><?php */?>
              </p>
            </fieldset>
          </li>
          <li class="colx2-left_m"><span style="color:red;">*</span> <span>Client Name:</span><br />
            <input type="text" id="c_name" name="c_name" value=""/>
          </li>
          <li class="colx2-left_m"> <span>Prefix:</span><br />
            <input type="text" id="c_prefix" name="c_prefix" value=""/>
          </li>
          <li class="colx2-left_m"> <span>Status:</span><br />
            <select name="c_status" id="c_status">
              <option value="0" >Please Select</option>
              <option value="1" >Active</option>
              <option value="0">In Active</option>
            </select>
          </li>
          <li class="colx2-left_m"><span style="color:red;">*</span> <span>Website:</span><br />
            <input type="text" id="c_website" name="c_website" value=""/>
          </li>
          <li class="colx2-left_m"> <span>Background Color:</span><br />
            <input id="bcid" class="minicolors" type="text" style="margin-bottom: 6px;">
            <input type="hidden" id="hdn_bcid" name="hdn_bcid" value="" />
          </li>
          <li class="colx2-left_m"> <span>Light Color:</span><br />
            <input id="lcid" class="minicolors" type="text" style="margin-bottom: 6px;">
            <input type="hidden" id="hdn_lcid" name="hdn_lcid" value="" />
          </li>
          <li class="colx2-left_m"> <span>Dark Color:</span><br />
            <input id="dcid" class="minicolors" type="text" style="margin-bottom: 6px;">
            <input type="hidden" id="hdn_dcid" name="hdn_dcid" value="" />
          </li>
          <li class="colx2-left_m">
            <span>Is Demo:</span><br />
            <select name="c_is_demo" id="c_is_demo" class="full-width">
              <option value="">Select</option>
              <option value="1">YES</option>
              <option value="0">NO</option>  
            </select>
          </li>
          <li class="colx2-left_m">
              <span>Client Veriticals:</span><br />
              <select name="c_client_vertical" id="c_client_vertical">
              <option value="" >Please Select</option>
               <?php for($i=0;$i<count($outArrAllVerticalClients);$i++){?>
                <option value="<?php echo $outArrAllVerticalClients[$i]['client_vertical_id'];?>" ><?php echo $outArrAllVerticalClients[$i]['client_vertical_name'];?></option>
              <?php }?>
              </select>
          </li>
          <li class="colx2-left_m">
              <span>Store notify message:</span><br />
              <textarea id="store_notify_msg" name="store_notify_msg" style="width: 413px; height: 100px;"></textarea>
          </li>
        </ul>

        <div class="clear"></div>
        <fieldset class="grey-bg no-margin">
          <p style="float:right;">
            <button>Save</button>
            <a href="<?php echo $config['LIVE_URL'].'clients';?>">
            <button type="button" style="margin-left:2px;">Cancel</button>
            </a> </p>
        </fieldset>
      </div>
    </form>
  </div>
</section>
