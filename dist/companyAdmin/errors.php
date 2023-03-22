<?php if (count($errors) > 0) : ?>
	<div class="alert alert-danger">
		<?php foreach ($errors as $error) : ?>
			<p class="p-0 m-0"><?php echo $error ?></p>
		<?php endforeach ?>
	</div>
<?php endif ?>