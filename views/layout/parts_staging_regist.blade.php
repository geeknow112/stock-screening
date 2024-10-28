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

<script>
/**
 * 確認画面でform要素をreadOnlyにする
 *
 **/
window.onload = function() {
	const action = "{{$get->action}}";
	if (action == 'confirm') {
		document.getElementById('customer').readOnly = true;
		document.getElementById('contents_code').readOnly = true;
	}
}

/**
 * addCustomerTankRow: テーブルに行を追加
 **/
function addCustomerTankRow(cnt = null)
{
	const cRow = document.getElementById("customerTankRow");
	if (!cRow) return;

	cnt = parseInt(cnt) + 1;
	console.log(cnt);

	cRow.innerHTML += '<div id="addRow' + cnt + '"></div>';

	const addRow = document.getElementById("addRow" + cnt);
	addRow.innerHTML += '	<label class="col-sm-2 col-form-label w-5" id="label_' + cnt + '">配送先 槽（タンク）: ' + cnt + '</label>';
	addRow.innerHTML += '	<input type="text" class="col-sm-2 col-form-control w-auto" id="tank_' + cnt + '" name="tank[]" aria-describedby="tankHelp" value="">&emsp;';
	addRow.innerHTML += '	<input type="button" class="col-sm-2 col-form-control w-auto" id="add_tank_' + cnt + '" name="add_tank_' + cnt + '" value="追加" onclick="addCustomerTankRow(' + cnt + ')">&emsp;';

	did = parseInt(cnt) - 1;
	console.log(did);
	document.getElementById("add_tank_" + did).remove();
}

/**
 * addCustomerAddrRow: テーブルに行を追加
 **/
function addCustomerAddrRow(cnt = null)
{
	const cRow = document.getElementById("customerAddrRow");
	if (!cRow) return;

	cnt = parseInt(cnt) + 1;
	console.log(cnt);

	cRow.innerHTML += '<div id="addRow' + cnt + '"></div>';

	const addRow = document.getElementById("addRow" + cnt);
	addRow.innerHTML += '	<label class="col-sm-2 col-form-label w-5" id="label_' + cnt + '">住所: ' + cnt + '</label>';
	addRow.innerHTML += '	<input type="text" class="col-sm-2 col-form-control w-auto" id="pref_' + cnt + '" name="pref[]" aria-describedby="prefHelp" value="">&emsp;';
	addRow.innerHTML += '	<input type="text" class="col-sm-2 col-form-control w-auto" id="addr1_' + cnt + '" name="addr1[]" aria-describedby="addr1Help" value="">&emsp;';
	addRow.innerHTML += '	<input type="text" class="col-sm-2 col-form-control w-auto" id="addr2_' + cnt + '" name="addr2[]" aria-describedby="addr2Help" value="">&emsp;';
	addRow.innerHTML += '	<input type="text" class="col-sm-2 col-form-control w-auto" id="addr3_' + cnt + '" name="addr3[]" aria-describedby="addr3Help" value="">&emsp;';
//	addRow.innerHTML += '	<input type="button" class="col-sm-2 col-form-control w-auto" id="del' + cnt + '" name="del' + cnt + '" value="削除" onclick="delCustomerAddrRow(' + cnt + ')">&emsp;';
	addRow.innerHTML += '	<input type="button" class="col-sm-2 col-form-control w-auto" id="add' + cnt + '" name="add' + cnt + '" value="追加" onclick="addCustomerAddrRow(' + cnt + ')">&emsp;';

	did = parseInt(cnt) - 1;
	console.log(did);
	document.getElementById("add" + did).remove();
}

/**
 * delCustomerAddrRow: 削除ボタン該当行を削除
 **/
function delCustomerAddrRow(cnt)
{
    // 確認
	if (!confirm("この行を削除しますか？")) { return; }
//	const cAddr = document.getElementById("customerAddr");

	var e = [];
//	e.push(document.getElementById("customerAddr"));
	e.push(document.getElementById("label_" + cnt));
	e.push(document.getElementById("pref_" + cnt));
	e.push(document.getElementById("addr1_" + cnt));
	e.push(document.getElementById("addr2_" + cnt));
	e.push(document.getElementById("addr3_" + cnt));
	e.push(document.getElementById("add" + cnt));
	e.push(document.getElementById("del" + cnt));

//	if (!cAddr) { return; }
//console.log(cAddr);

//	if (!obj) { return; }
//console.log(obj);

	//console.log(e);
	e.forEach((element) => element.remove());
}
</script>