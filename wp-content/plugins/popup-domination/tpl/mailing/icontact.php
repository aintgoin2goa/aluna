<div class="mainbox" id="popup_domination_tab_icontact" style="display:none;">
	<div class="inside twodivs">
		<div class="popdom_contentbox the_help_box">
			<h3 class="help">Help</h3>
			<div class="popdom_contentbox_inside">
				<p>Once Logged into your account, using your browser, navigate to: https://app.icontact.com/icp/core/externallogin</p>
				<p>Using the AppID (AJueEV2f4gWJmAKbXgG4SZVhLzISrijR), register the plugin to your account with a password to access it.</p>
				<p>Once the app is registered, you should have a screen like this:</p>
				<img src="<?php echo $this->plugin_url;?>css/img/apiconnect.jpg" alt="" />
				<p>Using the fields below, enter your Username, the chosen password, and the AppID.</p>
			</div>
			<div class="clear"></div>
		</div>
		<div class="popdom-inner-sidebar">
		<h3>Please Fill in the Following Details:</h3>
			<div class="ic">
    			<input type="hidden" name="ic[apikey]" alt='ic' value="AJueEV2f4gWJmAKbXgG4SZVhLzISrijR" id="ic_apikey" />
    			<span class="example">iContact Username</span>
				<input class="required" type="text" name="ic[username]" alt='ic' placeholder="Your Username..." value="<?php if($provider == 'ic'){ echo $username;}else{ echo '';} ?>" id="ic_username" />
				<span class="example">iContact Application Password (Note: This is not the password you use to sign into iContact. Read above for more information.)</span>
    			<input class="required" type="text" name="ic[password]" alt='ic' placeholder="Your Password…" value="<?php if($provider == 'ic'){ echo $password;}else{ echo '';} ?>" id="ic_password" />
				<span class="example">iContact Application AppID (This should not be changed)</span>
				<input class="required" type="text" name="ic[apikey]" alt='ic' placeholder="Your App-ID" value="AJueEV2f4gWJmAKbXgG4SZVhLzISrijR" id="ic_apikey" disabled="disabled" />
				<h3>Please Select a Mailing List</h3>
				<a href="#" alt='ic_apikey' class="ic_getlist getlist"><span>Grab Mailing List</span></a><span class="mailing-ajax-waiting">waiting</span>
				<div class="clear"></div>
				<div class="mc_custom_fields">
				</div>
    		</div>
		</div>
	</div>
</div>