<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<h1>@yield ('title') | BladeOne</h1>
{!!$title!!}<br>
<h2>escape</h2>
{!!$fugafuga!!}<br>

@foreach ($msg as $k => $ms)
	{{$k}} : {{$ms}} <br>
@endforeach

<hr>

<h2>現在底値圏　かつ、高配当の銘柄</h2>
<div class="d-grid gap-2">
	@foreach ($bottomStocks as $stock => $haitou)
	<div>{{$stock}} : {{$stocks[$stock]}} : {{$haitou}}%</div>
	@endforeach
</div>