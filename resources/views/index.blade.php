<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>Tratta</title>
		<script src="{{ mix('/js/app.js') }}" defer></script>
		<link rel="stylesheet" href="{{ asset('css/app.css') }}">
		<!-- Fonts -->
		<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

	</head>
	<body class="antialiased">
		<div id="app">
			<div class="shadow-md bg-white fixed left-0 top-0 w-full">
				<div class="max-w-xxl mx-auto">
					<div class="h-16 flex flex-col justify-center">
						<h1 class="text-center font-bold text-3xl">Tratta</h1>
					</div>
				</div>
			</div>
			<div>
				<data-component/>
			</div>
		</div>
		
	</body>
</html>
