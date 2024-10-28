	<p class="">
	<legend>【コード投稿】</legend>
	</p>

	<div class="row mb-3" style="height: 300px;">
		<label for="contents_code" class="col-sm-2 col-form-label w-5">コード (PHP)</label>
		<textarea type="text" class="col-sm-2 col-form-control w-50"id="contents_code" name="contents_code">{{$post->contents_code}}</textarea>
	</div>

	<div class="row mb-3" style="height: 300px;">
		<label for="contents_code" class="col-sm-2 col-form-label w-5">コード (Ruby)</label>
		<textarea type="text" class="col-sm-2 col-form-control w-50"id="code_ruby" name="code_ruby">{{$post->code_ruby}}</textarea>
	</div>

	<div class="row mb-3">
		<label for="contents_code" class="col-sm-2 col-form-label w-5"></label>
		<input type="button" name="cmd_regist" id="cmd_regist" class="btn btn-success w-auto" value="実行画面確認" onclick="jumpStaging();">
	</div>
<script>
/**
 * 
 **/
function jumpStaging()
{
	window.open('/staging-test/');
}
</script>