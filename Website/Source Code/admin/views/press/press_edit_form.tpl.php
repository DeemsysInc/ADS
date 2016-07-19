
			
			<noscript>
				<div class="alert alert-block span10">
					<h4 class="alert-heading">Warning!</h4>
					<p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a> enabled to use this site.</p>
				</div>
			</noscript>
			
			<div id="content" class="span10">
			<!-- content starts -->
			

			<div>
				<ul class="breadcrumb">
					<li>
						<a href="<?php echo $config['LIVE_ADMIN_URL']?>">Home</a> <span class="divider">/</span>
					</li>
					<li>
						<a href="#">Press</a>
					</li>
				</ul>
			</div>
			
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-edit"></i> Edit Press Release</h2>
						<div class="box-icon">
							<a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a>
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
							<a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<form class="form-horizontal">
						  <fieldset>
							<div id="success_msg" style="display: none;">
							<div class="alert alert-success" >
							<button type="button" class="close" data-dismiss="alert">X</button>
							<strong>Well done!</strong> You successfully updated.
						       </div>
							</div>
							<div id="error_msg" style="display: none;">
							<div class="alert alert-error">
							<button type="button" class="close" data-dismiss="alert">X</button>
							<strong>Oh snap!</strong> Change a few things up and try submitting again.
						         </div>
							</div>
							<div class="control-group">
								<label class="control-label" for="focusedInput">Title</label>
								<div class="controls">
								  <input class="input-xlarge focused" id="press_title" name="press_title" type="text" value="<?php echo isset($outArray[0]['title']) ? $outArray[0]['title'] : ''?>">
								</div>
							</div>
							<div class="control-group">
							  <label class="control-label" for="date01">Date Time</label>
							  <div class="controls">
								<input type="text" class="input-xlarge datepicker" id="press_date" value="<?php echo isset($outArray[0]['created_date']) ? $outArray[0]['created_date'] : ''?>">
							  </div>
							</div>
							<div class="control-group">
								<label class="control-label" for="focusedInput">Url</label>
								<div class="controls">
								  <input class="input-xlarge focused" id="press_url" name="press_url" type="text" value="<?php echo isset($outArray[0]['url']) ? $outArray[0]['url'] : ''?>">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="focusedInput">Publication</label>
								<div class="controls">
								  <input class="input-xlarge focused" id="press_publication" name="press_publication" type="text" value="<?php echo isset($outArray[0]['publication']) ? $outArray[0]['publication'] : ''?>">
								</div>
							</div>

							<input type="hidden" name="press_id" id="press_id" value="<?php echo isset($outArray[0]['id']) ? $outArray[0]['id'] : ''?>">
							<div class="form-actions">
							  <button type="submit" class="btn btn-primary" onclick="return updatePressReleases();">Save changes</button>
						          <button type="reset" class="btn">Cancel</button>
  							</div>
						  </fieldset>
						</form>   

					</div>
				</div><!--/span-->

			</div><!--/row-->


    
					<!-- content ends -->
			</div><!--/#content.span10-->
				</div><!--/fluid-row-->
				
		
