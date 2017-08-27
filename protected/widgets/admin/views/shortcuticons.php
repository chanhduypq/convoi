
<div class="row-fluid">
	<div class="span12">

		<div class="widgetButtons">
		
			<?php if(count($this->items))
			{
				foreach($this->items as $item)
				{
					if(!isset($item['url']) || empty($item['url'])) $item['url'] = '';
					elseif($item['url'] != '#') $item['url'] = Yii::app()->createUrl($item['url'], isset($item['urlparams']) ? $item['urlparams'] : array());
					?>
					<div class="bb">
						<a href="<?php echo $item['url']; ?>" class="tipb" data-original-title="<?php echo isset($item['title']) ? $item['title'] : ''; ?>">
							<span class="ibw-<?php echo isset($item['icon']) ? $item['icon'] : ''; ?>"></span>
						</a>
						<?php if(isset($item['caption'])) { ?>
							<div class="caption <?php echo isset($item['captionclass']) ? $item['captionclass'] : ''; ?>"><?php echo $item['caption']; ?></div>
						<?php } ?>
					</div>
				<?php }
			} ?>
		</div>

	</div>
</div>
