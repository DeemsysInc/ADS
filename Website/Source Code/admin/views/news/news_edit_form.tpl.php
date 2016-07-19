
			
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
						<a href="#">News edit</a>
					</li>
				</ul>
			</div>
			
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-edit"></i> Edit News</h2>
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
								  <input class="input-xlarge focused" id="news_title" name="news_title" type="text" value="<?php echo isset($outArray[0]['title']) ? $outArray[0]['title'] : ''?>">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="focusedInput">Subtitle</label>
								<div class="controls">
								  <input class="input-xlarge focused" id="news_subtitle" name="news_subtitle" type="text" value="<?php echo isset($outArray[0]['subtitle']) ? $outArray[0]['subtitle'] : ''?>">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="focusedInput">Location</label>
								<div class="controls">
								  <input class="input-xlarge focused" id="news_location" name="news_location" type="text" value="<?php echo isset($outArray[0]['location']) ? $outArray[0]['location'] : ''?>">
								</div>
							</div>
							<div class="control-group">
							  <label class="control-label" for="date01">Date Time</label>
							  <div class="controls">
								<input type="text" class="input-xlarge datepicker" id="news_date" value="<?php echo isset($outArray[0]['createddate']) ? $outArray[0]['createddate'] : ''?>">
							  </div>
							</div>
							<div class="control-group">
								<label class="control-label">Featured</label>
								<div class="controls">
								  <label class="checkbox inline">
									<div class="checker" id="uniform-inlineCheckbox1">
									 <span class="checked">
									   <?php if(isset($outArray[0]['featured']) && $outArray[0]['featured']==1){?> 
									    <input type="checkbox" id="news_featured" checked="checked" value="1" style="opacity: 0;">
									   <?php }else{?>
									    <input type="checkbox" id="news_featured" value="0" style="opacity: 0;">
									  <?php }?>
									 </span>
									</div> 
								  </label>
								  
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Include About?</label>
								<div class="controls">
								  <label class="checkbox inline">
									<div class="checker" id="uniform-inlineCheckbox1">
									 <span class="checked">
									   <?php if(isset($outArray[0]['include_about']) && $outArray[0]['include_about']==1){?> 
									    <input type="checkbox" id="news_include_about" checked="checked" value="1" style="opacity: 0;">
									   <?php }else{?>
									    <input type="checkbox" id="news_include_about" value="0" style="opacity: 0;">
									  <?php }?>
									 </span>
									</div> 
								  </label>
								  
								</div>
							</div>
							
							<div class="control-group">
							  <label class="control-label" for="textarea2">Excerpt</label>
							  <div class="controls">
								<textarea class="cleditor" id="news_excerpt" rows="3"><?php echo isset($outArray[0]['excerpt']) ? $outArray[0]['excerpt'] : ''?></textarea>
							  </div>
							</div>
							<div class="control-group">
							  <label class="control-label" for="textarea2">Content</label>
							  <div class="controls">
								<textarea class="cleditor" id="news_content" rows="3"><?php echo isset($outArray[0]['content']) ? $outArray[0]['content'] : ''?></textarea>
							  </div>
							</div>
							

							<input type="hidden" name="news_id" id="news_id" value="<?php echo isset($outArray[0]['id']) ? $outArray[0]['id'] : ''?>">
							<div class="form-actions">
							  <button type="submit" class="btn btn-primary" onclick="return updateNews();">Save changes</button>
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
				
		
