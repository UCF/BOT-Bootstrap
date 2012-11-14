<form role="search" method="get" class="form-search" action="<?=home_url( '/' )?>">
	<div>
		<label for="s">Search:</label>
		<input type="text" value="<?=htmlentities($_GET['s'])?>" name="s" class="search-field" id="s" />
		<br />
		<button type="submit" class="btn">Search</button>
	</div>
</form>