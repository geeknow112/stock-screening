	var test = document.getElementsByClassName("sentence");
	//console.log(test[0].innerText);
	
	var dict = document.getElementsByClassName("dictionary");
	//console.log(dict[0].innerText);

	// 問題文
	let Q = [];
	let ids = [];
	let dic = [];
	// objectのforeach
	Object.keys(test).forEach(function (key) {
//			console.log(test[key].id);
		Q.push(test[key].innerText);
		ids.push(test[key].id);
		dic.push(dict[key].innerText);
	});

	//let Q = [test[0].innerText, test[1].innerText, test[2].innerText, test[3].innerText, test[4].innerText, test[5].innerText];
//		"I_ll stand up for what I believe in and won_t yield to any threats."];

	// 問題をランダムで出題する
	//let Q_No = Math.floor( Math.random() * Q.length);
	let Q_No = 0;

	// 回答初期値・現在単語どこまで合っているか判定している文字番号
	let Q_i = 0;

	// 計算用の文字の長さ
	let Q_l = Q[Q_No].length;


window.addEventListener("keydown", push_Keydown);

function push_Keydown(event) {
	let keyCode = event.key;
	
	console.log(ids[Q_No]);
	if (ids[Q_No] === undefined) {
//		window.location.href = '/webhook-keepa-to-slack/';
		var section = document.getElementById("current_section").value;
		section = parseInt(section) + 1;
		console.log('input section : ' + section);
		window.location.href = '/tools/js-typing/?section=' + section;
//		location.reload();

	}

	document.getElementById("number").innerHTML = ids[Q_No];
	//document.getElementById("sens_output").innerHTML = Q[Q_No];
	//console.log(dic[Q_No].replaceAll(';', '<br>'));
//	document.getElementById("dict_output").innerHTML = dic[Q_No].replaceAll(';', '<br>');
	document.getElementById("sidebar").innerHTML = dic[Q_No].replaceAll(';', '<br>');
	document.getElementById("footer").innerHTML = dic[Q_No].replaceAll(';', '<span style="color: red;"> | </span>');
	document.getElementById("img").src = "http://hack-note.com/wp-content/uploads/duo_image/" + ids[Q_No].padStart(2,"0") + ".png";
	
	if (Q_l == Q_l-Q_i) {
		// 問題を書き出す
		document.getElementById("start").innerHTML = Q[Q_No].substring(Q_i, Q_l);
	}


	// 押したキーが合っていたら
	if (Q[Q_No].charAt(Q_i) == keyCode) {
		// 判定する文章に１足す
		Q_i++;

		// 問題を書き出す
		document.getElementById("start").innerHTML = Q[Q_No].substring(Q_i, Q_l);

		// 全部正解したら
		if (Q_l-Q_i === 0) {
			// 問題をランダムで出題する
			console.log("Q_No : " + Q_No);
			Q_No += 1;
			console.log("Q_No : " + Q_No);
			//Q_No = Math.floor( Math.random() * Q.length);

			// 回答初期値・現在どこまで合っているか判定している文字番号
			Q_i = 0;

			// 計算用の文字の長さ
			Q_l = Q[Q_No].length;

			// 新たな問題を書き出す
			document.getElementById("start").innerHTML = Q[Q_No].substring(Q_i, Q_l);
		}
	}
}