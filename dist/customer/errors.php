<?php if (count($errors) > 0) : ?>
	<div class="error">
		<div class="alert alert-danger rounded" role="alert">
			<?php foreach ($errors as $error) : ?>
				<span><?php echo $error ?></span>
			<?php endforeach ?>
		</div>
	</div>
<?php endif ?>