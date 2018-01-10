<?php
$l = icl_get_languages('skip_missing=N&orderby=code&order=asc');

?>

 <div id="languages">
        	<h4>Languages</h4>
			
            <a id="english" href="<?php echo $l['en']['url']; ?>" <?php if($l['en']['active']): ?>class="active"<?php endif; ?>><span>English</span></a>
            <a id="spanish" href="<?php echo $l['es']['url']; ?>" <?php if($l['es']['active']): ?>class="active"<?php endif; ?>><span>Spanish</span></a>
            <a id="french" href="<?php echo $l['fr']['url']; ?>" <?php if($l['fr']['active']): ?>class="active"<?php endif; ?>><span>French</span></a>
        </div><!-- #languages -->