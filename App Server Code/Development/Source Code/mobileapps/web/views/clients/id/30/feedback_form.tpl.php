<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>FeedBack Form </title>
</head>


<script src="<?php echo $config['LIVE_URL']; ?>mobileapps/web/views/js/jquery.js"></script>

<script src="<?php echo $config['LIVE_URL']; ?>mobileapps/web/views/js/validation.js"></script>

<script type="text/javascript">

var actionUrl = "<?php echo $config['LIVE_URL']; ?>mobileapps/web/feedback/<?php echo $clientId; ?>/action/";

var thanksUrl = "<?php echo $config['LIVE_URL']; ?>mobileapps/web/thanks/";

</script>

<?php //echo "Form";
//$config['LIVE_URL']."/files/clients/".$cliendId."/logo/precoa-logo.png";?> 
<body>

<img src="<?php echo $config['LIVE_URL']; ?>/files/clients/39/logo/precoa-logo.png" alt="precoa logo" height="150" width="200" />
<h3>FeedBack Form</h3>
<form name="form" id="form" action="<?php echo $config['LIVE_URL']."mobileapps/web/feedback/39/action"; ?>" method="post">
<ol>
<li><p>How old are you?</p><input type="hidden" value="How old are you?" name="question_1" id="question_1"/>
<p>
A<input type="radio" name="answer_1" id="answer_1" value="Under 25" />Under 25  &nbsp;  B <input type="radio" name="answer_1"  value="26-40" /> 26-40  &nbsp;    C  <input type="radio" name="answer_1" value="41-65"/> 41-65   &nbsp;  D <input type="radio" name="answer_1" value="66 or older"/> 66 or older 
</p></li>
<li><p>Are you currently: </p><input type="hidden" value="Are you currently:" name="question_2" id="question_2"/>
<p>
 A <input type="radio"  name="answer_2" value="Employed" /> Employed &nbsp;   B <input type="radio" name="answer_2"  value="Retired" /> Retired
 </p></li>
  
 <li><p>
Is there an honorably discharged veteran in your household? </p><input type="hidden" value="Is there an honorably discharged veteran in your household?" name="question_3" id="question_3"/>
<p> A <input type="radio" name="answer_3" value="Yes" /> Yes &nbsp; B <input type="radio" name="answer_3"  value="No" /> No

 </p></li>
 
  <li><p>
  Have you ever been responsible for making funeral arrangements?</p><input type="hidden" value="Have you ever been responsible for making funeral arrangements?" name="question_4" id="question_4"/>
  <p>
 A <input type="radio" name="answer_4"   value="Yes" /> Yes &nbsp; B <input type="radio" name="answer_4"   value="No" /> No

 </p></li>
 
 
  <li><p>
  If yes, was it:</p><input type="hidden" value="If yes, was it:" name="question_5" id="question_5"/>
  <p>
 A <input type="radio"   name="answer_5" value=" Within last 12 months" /> Within last 12 months &nbsp;B <input type="radio"  name="answer_5" value=" 12-24 months ago" /> 12-24 months ago &nbsp; C <input type="radio"  name="answer_5" value="Over 2 years ago" />Over 2 years ago


 </p></li>
   <li><p>
 		Are you aware that prepaid funeral plans exist?</p><input type="hidden" value="Are you aware that prepaid funeral plans exist?" name="question_6" id="question_6"/>
<p>  A <input type="radio" name="answer_6"  value="Yes" /> Yes &nbsp; B <input type="radio" name="answer_6"    value="No" /> No
 </p></li>
 
 
    <li><p>
 		Have you ever considered a prepaid funeral plan?</p><input type="hidden" value="Have you ever considered a prepaid funeral plan?" name="question_7" id="question_7"/>
        <p>
  A <input type="radio"  name="answer_7"   value="Yes" /> Yes &nbsp; B <input type="radio" name="answer_7" value="No" /> No
 </p></li>
 
 
   <li><p>
 		How much might you expect to pay for a funeral?</p><input type="hidden" value="How much might you expect to pay for a funeral?" name="question_8" id="question_8"/>
        
        <p>
        A  <input type="radio" name="answer_8"  value=" $0 - $2,000" /> $0 - $2,000 &nbsp;B <input type="radio" name="answer_8"  value="$2,000 - $4,000" />  $2,000 - $4,000 &nbsp;C  <input type="radio" name="answer_8"   value="$4,000 - $6,000" /> $4,000 - $6,000<br />
D   <input type="radio" name="answer_8"  value="6,000 - $8,000" /> $6,000 - $8,000 &nbsp; E <input type="radio" name="answer_8"  value="$8,000 - $10,000" />  $8,000 - $10,000 &nbsp;F <input type="radio"name="answer_8"   value="Over $10,000" /> Over $10,000
 </p></li>
 
 
  <li><p>  		
  How important to you personally is the location (proximity) of the following:</p>
     <p>Funeral Home</p>   
     <input type="hidden" value="  How important to you personally is the location (proximity) of the following:" name="question_9" id="question_9"/>
     <p>
    A  <input type="radio" name="answer_9"  value=" Very important" />Very important &nbsp; B <input type="radio"  name="answer_9"  value=" Somewhat important" />  Somewhat important &nbsp; C  <input type="radio"  name="answer_9"  value="Not very important" /> Not very important
 &nbsp;D   <input type="radio"  name="answer_9"  value="Not important at all" /> Not important at all<br />
 </p><p>
 <p> Cemetery</p>
  E <input type="radio" name="answer_9"   value=" Very important" />Very important &nbsp; F <input type="radio" name="answer_9"  value=" Somewhat important" />  Somewhat important  &nbsp;G  <input type="radio" name="answer_9"   value="Not very important" /> Not very important
H  <input type="radio" name="answer_9"   value="Not important at all" /> Not important at all
 </p></li>
 
 
 
  
   <li><p>
 		 If you have given thought to this subject, which of the following would you choose 
for yourself?</p>        <input type="hidden" value="If you have given thought to this subject, which of the following would you choose for yourself?" name="question_10" id="question_10"/>
        <p>
        A  <input type="radio" name="answer_10"  value="Burial" /> Burial &nbsp; B <input type="radio" name="answer_10" value="Cremation
" />  Cremation

 </p></li>
  <li><p>
Have you made firm plans and arrangements for:
        <p>
 Cemetery property </p> <input type="hidden" value="
Have you made firm plans and arrangements for: Cemetery property" name="question_11" id="question_11"/>
 <p>
 A <input type="radio" name="answer_11" value="Yes" /> Yes &nbsp; B <input type="radio" name="answer_11"  value="No" /> No

 </p></li>
 
 <li><p>Do you maintain up-to-date biographical information and accurate family records to assist you or a loved one with funeral planning? </p>
 <input type="hidden" value="
Do you maintain up-to-date biographical information and accurate family records to assist you or a loved one with funeral planning?" name="question_12" id="question_12"/>
 A <input type="radio" name="answer_12" value="Yes" /> Yes &nbsp;B <input type="radio" name="answer_12" value="No" /> No

 </p></li>
 
 <li><p>Do you currently have life insurance that is allocated for funeral arrangements in the event 
of your death? </p> <input type="hidden" value="Do you currently have life insurance that is allocated for funeral arrangements in the event of your death?" name="question_13" id="question_13"/>

<p>
 A <input type="radio" name="answer_13" value="Yes" /> Yes &nbsp;B <input type="radio" name="answer_13" value="No" /> No

 </p></li>
 
  <li><p>Is the beneficiary currently on your policy alive? </p><input type="hidden" value="Is the beneficiary currently on your policy alive?" name="question_14" id="question_14"/>
  <p>
 A <input type="radio"  name="answer_14" value="Yes" /> Yes &nbsp;B <input type="radio" name="answer_14"  value="No" /> No

 </p></li>
 
  <li><p>Are your loved ones and family members aware of what you desire and prefer for your own arrangements?</p>
  <input type="hidden" value="Are your loved ones and family members aware of what you desire and prefer for your own arrangements?" name="question_15" id="question_15"/>
<p>
 A <input type="radio" name="answer_15"   value="Yes" /> Yes &nbsp; B <input type="radio" name="answer_15"   value="No" /> No

 </p></li>
 
  
  <li><p>Do you currently have a will?</p><input type="hidden" value="Do you currently have a will?" name="question_16" id="question_16"/>

  <p>
 A <input type="radio" name="answer_16" value="Yes" /> Yes &nbsp; B <input type="radio" name="answer_16"  value="No" /> No

 </p></li>
 
   <li><p>In the event of your death, who would be responsible for making the arrangements?</p><input type="hidden" value="In the event of your death, who would be responsible for making the arrangements?" name="question_17" id="question_17"/>
   <p>
 A <input type="radio" name="answer_17" value="Spouse" /> Spouse &nbsp; B <input type="radio" name="answer_17"  value="Children" /> Children 
&nbsp; C <input type="radio" name="responsible" value="Family Member" /> Family Member &nbsp;D <input type="radio" name="answer_17" value="Other" /> Other
 
 </p></li>
 
 <li><p>Would it give you peace of mind to know that you could do the planning in advance and that your family would not have to make these arrangements themselves?</p><input type="hidden" value="Would it give you peace of mind to know that you could do the planning in advance and that your family would not have to make these arrangements themselves?" name="question_18" id="question_18"/>
<p>
 A <input type="radio" name="answer_18" value="Yes" /> Yes &nbsp; B <input type="radio" name="answer_18" value="No" /> No

 </p></li>
 <li><p>Would you like free information about funeral planning and the types of services that are available?</p><input type="hidden" value="Would you like free information about funeral planning and the types of services that are available?" name="question_19" id="question_19"/>
<p>
 A <input type="radio" name="answer_19"  value="Yes" /> Yes &nbsp;B <input type="radio" name="answer_19" value="No" /> No

 </p></li> 
</ol>
<p>Please give your complete address and telephone number in the spaces provided below. Thank you</p>


<div>Name <span style="color:#E30812">*</span></div>

<div>
<input  type="text"  name="name" id="name" /></div>

<div>Age</div>

<div><input type="text"  name="age" id="age"/></div>

<div  class="label">Spouse’s Age </div>

<div><input type="text"  name="spouse_age" id="spouse_age"/></div>

<div  class="label">Address </div>

<div><input type="text"  name="address" id="address"/></div>
<div  class="label">City </div>

<div><input type="text"  name="city" id="city"/></div>


<div  class="label">State </div>

<div><input type="text"  name="state" id="state"/></div>


<div  class="label">Zip Code  </div>

<div><input type="text"  name="zip_code" id="zip_code"/></div>

<div  class="label">Telephone Number  </div>

<div><input type="text"  name="number" id="number"/></div>


<div id="enterEmail" class="label">Email <span style="color:#E30812">*</span></div>

<div id="afterEmail"><input  type="email"   name="email" id="email" /></div>


<span style="color:#F00" name="spanerr" id="spanerr"></span>
<div><input type="hidden" name="client_name" id="client_name" value="<?php echo $client_name;?>" /></div>
<div ><input type="button" value="Save" class="button" onclick="feedbackSubmit();" style="background-color:<?php echo "#".$arrClientDetails[0]['dark_color'];?>"/> <input type="button" value="Clear" class="button" style="background-color:<?php echo "#".$arrClientDetails[0]['dark_color'];?>" onclick="clearval();"/>


</div>
</form>
</body>
</html>
